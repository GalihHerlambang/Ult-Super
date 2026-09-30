<?php
require_once APP_PATH . '/core/Controller.php';

class AduanController extends Controller
{
    private Aduan $aduanModel;
    private Layanan $layananModel;
    private LayananField $fieldModel;
    private LayananTahapan $tahapanModel;
    private AduanLampiran $lampiranModel;
    private AduanApproval $approvalModel;
    private AduanRiwayat $riwayatModel;
    private WorkflowEngine $workflow;
    private User $userModel;

    public function __construct()
    {
        $this->aduanModel    = new Aduan();
        $this->layananModel  = new Layanan();
        $this->fieldModel    = new LayananField();
        $this->tahapanModel  = new LayananTahapan();
        $this->lampiranModel = new AduanLampiran();
        $this->approvalModel = new AduanApproval();
        $this->riwayatModel  = new AduanRiwayat();
        $this->userModel     = new User();
        $this->workflow      = new WorkflowEngine();
    }

    public function index(): void
    {
        $this->requireRole('pengadu');
        $semua       = $this->aduanModel->where(['user_id' => $_SESSION['user_id']]);
        $aduanSaya   = array_reverse($semua);
        $layananMap  = array_column($this->layananModel->all(), null, 'id');
        $tahapanMap  = array_column($this->tahapanModel->all(), null, 'id');
        $pageTitle   = 'Aduan Saya';
        $this->view('pengadu/views/aduan/index', compact('aduanSaya', 'layananMap', 'tahapanMap', 'pageTitle'));
    }

    public function buat(): void
    {
        $this->requireRole('pengadu');
        $layanans  = $this->layananModel->getActive();
        $pageTitle = 'Buat Aduan Baru';
        $this->view('pengadu/views/aduan/buat', compact('layanans', 'pageTitle'));
    }

    public function getFields(): void
    {
        $this->requireRole('pengadu');
        $layananId = (int)($_GET['layanan_id'] ?? 0);
        $layanan   = $this->layananModel->find($layananId);
        $fields    = $this->fieldModel->getByLayanan($layananId);

        $this->json([
            'fields'  => $fields,
            'layanan' => $layanan ? [
                'id'                 => $layanan['id'],
                'nama'               => $layanan['nama'],
                'file_template'      => $layanan['file_template'] ?? null,
                'nama_file_template'=> $layanan['nama_file_template'] ?? null,
                'is_template_wajib'  => !empty($layanan['is_template_wajib']),
            ] : null
        ]);
    }

    public function simpan(): void
    {
        $this->requireRole('pengadu');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/pengadu/aduan/buat');
        }

        $layananId = (int)$this->post('layanan_id');
        $layanan   = $this->layananModel->find($layananId);
        if (!$layanan || !$layanan['is_active']) {
            $this->flash('error', 'Layanan tidak valid.');
            $this->redirect('/pengadu/aduan/buat');
        }

        // Validasi unggah berkas template jika wajib
        $isTemplateWajib = !empty($layanan['is_template_wajib']) && !empty($layanan['file_template']);
        $hasBerkasTemplate = !empty($_FILES['berkas_template']['name']) && $_FILES['berkas_template']['error'] === UPLOAD_ERR_OK;

        if ($isTemplateWajib && !$hasBerkasTemplate) {
            $this->flash('error', 'Unggah berkas syarat/template wajib diisi untuk layanan ini.');
            $this->redirect('/pengadu/aduan/buat');
        }

        // Kumpulkan data formulir dinamis
        $fields       = $this->fieldModel->getByLayanan($layananId);
        $dataFormulir = [];
        foreach ($fields as $field) {
            $val = $this->post($field['nama_field'], '');
            if ($field['is_required'] && $val === '') {
                $this->flash('error', "Field '{$field['label']}' wajib diisi.");
                $this->redirect('/pengadu/aduan/buat');
            }
            $dataFormulir[$field['nama_field']] = $val;
        }

        // Ambil tahapan pertama
        $tahapanPertama = $this->tahapanModel->getTahapanPertama($layananId);
        if (!$tahapanPertama) {
            $this->flash('error', 'Layanan belum memiliki alur tahapan. Hubungi admin.');
            $this->redirect('/pengadu/aduan/buat');
        }

        // Generate kode aduan
        $allAduan  = $this->aduanModel->all();
        $year      = date('Y');
        $count     = count($allAduan) + 1;
        $kode      = 'ADU-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $aduan = $this->aduanModel->insert([
            'kode_aduan'          => $kode,
            'user_id'             => $_SESSION['user_id'],
            'layanan_id'          => $layananId,
            'data_formulir'       => $dataFormulir,
            'tahapan_saat_ini_id' => $tahapanPertama['id'],
            'status'              => 'berjalan',
        ]);

        // Catat riwayat submit awal
        $this->riwayatModel->insert([
            'aduan_id'          => $aduan['id'],
            'arah'              => 'maju',
            'tahapan_asal_id'   => null,
            'tahapan_tujuan_id' => $tahapanPertama['id'],
            'aksi'              => 'diajukan',
            'catatan'           => 'Aduan diajukan oleh pengadu.',
            'diproses_oleh'     => $_SESSION['user_id'],
        ]);

        // Generate approval untuk tahapan pertama
        $this->workflow->generateApproval($aduan['id'], $tahapanPertama);

        // Handle upload berkas template pengadu jika ada
        if ($hasBerkasTemplate) {
            $this->handleUploadBerkasTemplate($aduan['id'], $_FILES['berkas_template']);
        }

        // Handle upload lampiran opsional lainnya
        if (!empty($_FILES['lampiran']['name'][0])) {
            $this->handleUploadLampiran($aduan['id'], $_FILES['lampiran']);
        }

        $this->flash('success', "Aduan berhasil diajukan dengan kode {$kode}.");
        $this->redirect('/pengadu/aduan/detail/' . $aduan['id']);
    }

    public function detail(string $id): void
    {
        $this->requireRole('pengadu');
        $aduan = $this->aduanModel->find((int)$id);
        if (!$aduan || $aduan['user_id'] != $_SESSION['user_id']) {
            $this->flash('error', 'Aduan tidak ditemukan.');
            $this->redirect('/pengadu/aduan');
        }

        $layanan     = $this->layananModel->find($aduan['layanan_id']);
        $tahapan     = $this->tahapanModel->getByLayanan($aduan['layanan_id']);
        $tahapanMap  = array_column($tahapan, null, 'id');
        $tahapanSkrg = $tahapanMap[$aduan['tahapan_saat_ini_id']] ?? null;
        $riwayat     = $this->riwayatModel->where(['aduan_id' => (int)$id]);
        $lampiran    = $this->lampiranModel->where(['aduan_id' => (int)$id]);
        $fields      = $this->fieldModel->getByLayanan($aduan['layanan_id']);
        $fieldMap    = array_column($fields, null, 'nama_field');
        $userMap     = array_column($this->userModel->all(), null, 'id');

        $pageTitle = 'Detail Aduan: ' . $aduan['kode_aduan'];
        $this->view('pengadu/views/aduan/detail',
            compact('aduan', 'layanan', 'tahapan', 'tahapanMap', 'tahapanSkrg',
                    'riwayat', 'lampiran', 'fieldMap', 'userMap', 'pageTitle'));
    }

    private function handleUploadLampiran(int $aduanId, array $files): void
    {
        $dir = PUBLIC_PATH . '/uploads/aduan/' . $aduanId;
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $allowed = ['jpg','jpeg','png','gif','pdf','doc','docx','zip'];
        $count   = count($files['name']);

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] !== 0) continue;
            $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) continue;

            $fname = uniqid() . '.' . $ext;
            move_uploaded_file($files['tmp_name'][$i], $dir . '/' . $fname);

            $this->lampiranModel->insert([
                'aduan_id'     => $aduanId,
                'nama_file'    => $files['name'][$i],
                'path_file'    => '/uploads/aduan/' . $aduanId . '/' . $fname,
                'uploaded_by'  => $_SESSION['user_id'],
            ]);
        }
    }

    private function handleUploadBerkasTemplate(int $aduanId, array $file): void
    {
        $dir = PUBLIC_PATH . '/uploads/aduan/' . $aduanId;
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $allowed = ['jpg','jpeg','png','gif','pdf','doc','docx','xls','xlsx','zip'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) return;

        $fname = uniqid('syarat_') . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'], $dir . '/' . $fname)) {
            $this->lampiranModel->insert([
                'aduan_id'     => $aduanId,
                'nama_file'    => '[Syarat Berkas] ' . $file['name'],
                'path_file'    => '/uploads/aduan/' . $aduanId . '/' . $fname,
                'uploaded_by'  => $_SESSION['user_id'],
            ]);
        }
    }
}

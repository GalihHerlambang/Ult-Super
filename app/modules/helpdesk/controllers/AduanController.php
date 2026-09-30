<?php
require_once APP_PATH . '/core/Controller.php';

class AduanController extends Controller
{
    private Aduan $aduanModel;
    private AduanApproval $approvalModel;
    private AduanRiwayat $riwayatModel;
    private LayananTahapan $tahapanModel;
    private LayananField $fieldModel;
    private AduanLampiran $lampiranModel;
    private User $userModel;
    private Layanan $layananModel;
    private WorkflowEngine $workflow;

    public function __construct()
    {
        $this->aduanModel    = new Aduan();
        $this->approvalModel = new AduanApproval();
        $this->riwayatModel  = new AduanRiwayat();
        $this->tahapanModel  = new LayananTahapan();
        $this->fieldModel    = new LayananField();
        $this->lampiranModel = new AduanLampiran();
        $this->userModel     = new User();
        $this->layananModel  = new Layanan();
        $this->workflow      = new WorkflowEngine();
    }

    public function index(): void
    {
        $this->requireRole('helpdesk');
        $tahapanRole = $this->tahapanModel->where(['role_pelaksana' => 'helpdesk']);
        $tahapanIds  = array_column($tahapanRole, 'id');

        $semua     = $this->aduanModel->where(['status' => 'berjalan']);
        $aduanList = array_filter($semua, fn($a) => in_array($a['tahapan_saat_ini_id'], $tahapanIds));
        $aduanList = array_reverse(array_values($aduanList));

        $layananMap = array_column($this->layananModel->all(), null, 'id');
        $tahapanMap = array_column($this->tahapanModel->all(), null, 'id');
        $userMap    = array_column($this->userModel->all(), null, 'id');

        $pageTitle = 'Aduan Masuk — Helpdesk';
        $this->view('helpdesk/views/aduan/index',
            compact('aduanList', 'layananMap', 'tahapanMap', 'userMap', 'pageTitle'));
    }

    public function detail(string $id): void
    {
        $this->requireRole('helpdesk');
        $aduan = $this->aduanModel->find((int)$id);
        if (!$aduan) { $this->flash('error', 'Aduan tidak ditemukan.'); $this->redirect('/helpdesk/aduan'); }

        $layanan     = $this->layananModel->find($aduan['layanan_id']);
        $tahapan     = $this->tahapanModel->getByLayanan($aduan['layanan_id']);
        $tahapanMap  = array_column($tahapan, null, 'id');
        $tahapanSkrg = $tahapanMap[$aduan['tahapan_saat_ini_id']] ?? null;

        $riwayat     = $this->riwayatModel->where(['aduan_id' => (int)$id]);
        $lampiran    = $this->lampiranModel->where(['aduan_id' => (int)$id]);
        $approvals   = $this->approvalModel->where(['aduan_id' => (int)$id, 'tahapan_id' => $aduan['tahapan_saat_ini_id']]);
        $fields      = $this->fieldModel->getByLayanan($aduan['layanan_id']);
        $fieldMap    = array_column($fields, null, 'nama_field');
        $userMap     = array_column($this->userModel->all(), null, 'id');

        // Cek apakah user ini punya approval menunggu
        $myApproval = null;
        foreach ($approvals as $ap) {
            if ($ap['approver_user_id'] == $_SESSION['user_id'] && $ap['status'] === 'menunggu') {
                $myApproval = $ap;
                break;
            }
        }

        $pageTitle = 'Detail: ' . $aduan['kode_aduan'];
        $this->view('helpdesk/views/aduan/detail',
            compact('aduan', 'layanan', 'tahapan', 'tahapanMap', 'tahapanSkrg',
                    'riwayat', 'lampiran', 'approvals', 'fieldMap', 'userMap', 'myApproval', 'pageTitle'));
    }

    public function setujui(string $id): void
    {
        $this->requireRole('helpdesk');
        $catatan = $this->post('catatan', '');
        $result  = $this->workflow->prosesSetuju((int)$id, $_SESSION['user_id'], $catatan);
        $this->flash($result['success'] ? 'success' : 'error', $result['message']);
        $this->redirect('/helpdesk/aduan/detail/' . $id);
    }

    public function tolak(string $id): void
    {
        $this->requireRole('helpdesk');
        $catatan = $this->post('catatan', '');
        $result  = $this->workflow->prosesTolak((int)$id, $_SESSION['user_id'], $catatan);
        $this->flash($result['success'] ? 'success' : 'error', $result['message']);
        $this->redirect('/helpdesk/aduan');
    }

    public function kembalikan(string $id): void
    {
        $this->requireRole('helpdesk');
        $tahapanTujuanId = (int)$this->post('tahapan_tujuan_id');
        $catatan         = $this->post('catatan', '');
        $result = $this->workflow->prosesKembalikan((int)$id, $tahapanTujuanId, $_SESSION['user_id'], $catatan);
        $this->flash($result['success'] ? 'success' : 'error', $result['message']);
        $this->redirect('/helpdesk/aduan/detail/' . $id);
    }

    public function uploadHasil(string $id): void
    {
        $this->requireRole('helpdesk');
        $aduanId = (int)$id;
        $aduan   = $this->aduanModel->find($aduanId);
        if (!$aduan) {
            $this->flash('error', 'Aduan tidak ditemukan.');
            $this->redirect('/helpdesk/aduan');
        }

        if (empty($_FILES['file_hasil']['name']) || $_FILES['file_hasil']['error'] !== UPLOAD_ERR_OK) {
            $this->flash('error', 'File SK / Berkas Hasil wajib diunggah.');
            $this->redirect('/helpdesk/aduan/detail/' . $id);
        }

        $dir = PUBLIC_PATH . '/uploads/hasil/' . $aduanId;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $originalName = $_FILES['file_hasil']['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['pdf','doc','docx','zip','png','jpg','jpeg'];
        if (!in_array($ext, $allowed)) {
            $this->flash('error', 'Format file tidak diizinkan. Hanya PDF, DOC, DOCX, ZIP, PNG, JPG.');
            $this->redirect('/helpdesk/aduan/detail/' . $id);
        }

        $filename = 'SK_HASIL_' . uniqid() . '.' . $ext;
        $pathFile = '/uploads/hasil/' . $aduanId . '/' . $filename;

        if (move_uploaded_file($_FILES['file_hasil']['tmp_name'], $dir . '/' . $filename)) {
            $this->lampiranModel->insert([
                'aduan_id'       => $aduanId,
                'nama_file'      => '[SK / Hasil] ' . $originalName,
                'path_file'      => $pathFile,
                'uploaded_by'     => $_SESSION['user_id'],
                'jenis_lampiran' => 'file_hasil',
                'created_at'     => date('Y-m-d H:i:s')
            ]);

            $catatan = $this->post('catatan') ?: 'File SK / Berkas Hasil pengajuan telah diunggah oleh Helpdesk.';
            $result  = $this->workflow->prosesSetuju($aduanId, $_SESSION['user_id'], $catatan);

            $this->flash('success', 'File SK / Berkas Hasil berhasil diunggah. ' . $result['message']);
        } else {
            $this->flash('error', 'Gagal mengunggah file berkas hasil.');
        }

        $this->redirect('/helpdesk/aduan/detail/' . $id);
    }
}

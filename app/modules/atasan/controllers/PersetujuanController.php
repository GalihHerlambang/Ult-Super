<?php
require_once APP_PATH . '/core/Controller.php';

class PersetujuanController extends Controller
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
        $this->requireRole('atasan');
        $tahapanRole = $this->tahapanModel->where(['role_pelaksana' => 'atasan']);
        $tahapanIds  = array_column($tahapanRole, 'id');

        $semua     = $this->aduanModel->where(['status' => 'berjalan']);
        $aduanList = array_filter($semua, fn($a) => in_array($a['tahapan_saat_ini_id'], $tahapanIds));
        $aduanList = array_reverse(array_values($aduanList));

        $layananMap = array_column($this->layananModel->all(), null, 'id');
        $tahapanMap = array_column($this->tahapanModel->all(), null, 'id');
        $userMap    = array_column($this->userModel->all(), null, 'id');

        $pageTitle = 'Persetujuan — Atasan';
        $this->view('atasan/views/persetujuan/index',
            compact('aduanList', 'layananMap', 'tahapanMap', 'userMap', 'pageTitle'));
    }

    public function detail(string $id): void
    {
        $this->requireRole('atasan');
        $aduan = $this->aduanModel->find((int)$id);
        if (!$aduan) { $this->flash('error', 'Aduan tidak ditemukan.'); $this->redirect('/atasan/persetujuan'); }

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

        $myApproval = null;
        foreach ($approvals as $ap) {
            if ($ap['approver_user_id'] == $_SESSION['user_id'] && $ap['status'] === 'menunggu') {
                $myApproval = $ap;
                break;
            }
        }

        $tahapanSebelumnya = [];
        foreach ($tahapan as $t) {
            if ($tahapanSkrg && $t['urutan'] < $tahapanSkrg['urutan']) {
                $tahapanSebelumnya[] = $t;
            }
        }

        $pageTitle = 'Persetujuan: ' . $aduan['kode_aduan'];
        $this->view('atasan/views/persetujuan/detail',
            compact('aduan', 'layanan', 'tahapan', 'tahapanMap', 'tahapanSkrg',
                    'riwayat', 'lampiran', 'approvals', 'fieldMap', 'userMap',
                    'myApproval', 'tahapanSebelumnya', 'pageTitle'));
    }

    public function setujui(string $id): void
    {
        $this->requireRole('atasan');
        $catatan = $this->post('catatan', '');
        $result  = $this->workflow->prosesSetuju((int)$id, $_SESSION['user_id'], $catatan);
        $this->flash($result['success'] ? 'success' : 'error', $result['message']);
        $this->redirect('/atasan/persetujuan/detail/' . $id);
    }

    public function tolak(string $id): void
    {
        $this->requireRole('atasan');
        $catatan = $this->post('catatan', '');
        $result  = $this->workflow->prosesTolak((int)$id, $_SESSION['user_id'], $catatan);
        $this->flash($result['success'] ? 'success' : 'error', $result['message']);
        $this->redirect('/atasan/persetujuan');
    }

    public function kembalikan(string $id): void
    {
        $this->requireRole('atasan');
        $tahapanTujuanId = (int)$this->post('tahapan_tujuan_id');
        $catatan         = $this->post('catatan', '');
        $result = $this->workflow->prosesKembalikan((int)$id, $tahapanTujuanId, $_SESSION['user_id'], $catatan);
        $this->flash($result['success'] ? 'success' : 'error', $result['message']);
        $this->redirect('/atasan/persetujuan/detail/' . $id);
    }
}

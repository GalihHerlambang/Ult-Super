<?php
require_once APP_PATH . '/core/Controller.php';

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireRole('atasan');

        $aduanModel   = new Aduan();
        $tahapanModel = new LayananTahapan();

        $tahapanRole = $tahapanModel->where(['role_pelaksana' => 'atasan']);
        $tahapanIds  = array_column($tahapanRole, 'id');

        $semua     = $aduanModel->where(['status' => 'berjalan']);
        $aduanSaya = array_filter($semua, fn($a) => in_array($a['tahapan_saat_ini_id'], $tahapanIds));

        $stats = [
            'menunggu' => count($aduanSaya),
            'selesai'  => $aduanModel->count(['status' => 'selesai']),
            'ditolak'  => $aduanModel->count(['status' => 'ditolak']),
        ];

        $layananMap = array_column((new Layanan())->all(), null, 'id');
        $userMap    = array_column((new User())->all(), null, 'id');

        $pageTitle = 'Dashboard Atasan';
        $this->view('atasan/views/dashboard/index', compact('stats', 'aduanSaya', 'layananMap', 'userMap', 'pageTitle'));
    }
}

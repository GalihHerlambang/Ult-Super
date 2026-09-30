<?php
require_once APP_PATH . '/core/Controller.php';

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();
        $role = $_SESSION['role'];

        // Redirect ke modul sesuai role jika bukan pengadu
        if ($role !== 'pengadu') {
            $this->redirect('/' . $role . '/dashboard');
        }

        $aduanModel = new Aduan();
        $aduanSaya  = $aduanModel->where(['user_id' => $_SESSION['user_id']]);

        $stats = [
            'total'     => count($aduanSaya),
            'berjalan'  => count(array_filter($aduanSaya, fn($a) => $a['status'] === 'berjalan')),
            'selesai'   => count(array_filter($aduanSaya, fn($a) => $a['status'] === 'selesai')),
            'ditolak'   => count(array_filter($aduanSaya, fn($a) => $a['status'] === 'ditolak')),
        ];

        $aduanTerbaru = array_slice(array_reverse($aduanSaya), 0, 5);
        $layananModel = new Layanan();
        $layananMap   = array_column($layananModel->all(), null, 'id');

        $pageTitle = 'Dashboard Saya';
        $this->view('pengadu/views/dashboard/index', compact('stats', 'aduanTerbaru', 'layananMap', 'pageTitle'));
    }
}

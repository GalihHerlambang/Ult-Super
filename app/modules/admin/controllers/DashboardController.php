<?php
require_once APP_PATH . '/core/Controller.php';

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireRole('admin');

        $aduanModel   = new Aduan();
        $userModel    = new User();
        $layananModel = new Layanan();

        $stats = [
            'total_aduan'     => $aduanModel->count(),
            'berjalan'        => $aduanModel->count(['status' => 'berjalan']),
            'selesai'         => $aduanModel->count(['status' => 'selesai']),
            'ditolak'         => $aduanModel->count(['status' => 'ditolak']),
            'total_user'      => $userModel->count(),
            'total_layanan'   => $layananModel->count(),
        ];

        // Aduan terbaru
        $aduanTerbaru = array_slice(array_reverse($aduanModel->all()), 0, 10);
        $users    = $userModel->all();
        $layanans = $layananModel->all();

        // Map user & layanan
        $userMap    = array_column($users, null, 'id');
        $layananMap = array_column($layanans, null, 'id');

        $pageTitle = 'Dashboard Admin';
        $this->view('admin/views/dashboard/index', compact('stats', 'aduanTerbaru', 'userMap', 'layananMap', 'pageTitle'));
    }
}

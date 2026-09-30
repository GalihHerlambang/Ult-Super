<?php
require_once APP_PATH . '/core/Controller.php';

class SettingController extends Controller
{
    private Setting $settingModel;

    public function __construct()
    {
        $this->settingModel = new Setting();
    }

    public function index(): void
    {
        $this->requireRole('admin');
        $settingData = $this->settingModel->getSetting();
        $pageTitle   = 'Pengaturan Aplikasi';
        $this->view('admin/views/setting/index', compact('settingData', 'pageTitle'));
    }

    public function simpan(): void
    {
        $this->requireRole('admin');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/setting');
        }

        $current = $this->settingModel->getSetting();

        $logoPath       = $current['logo'] ?? '';
        $logoFooterPath = $current['logo_footer'] ?? '';
        $faviconPath    = $current['favicon'] ?? '';

        $dir = PUBLIC_PATH . '/uploads/setting';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        // Hapus Logo Utama
        if ($this->post('hapus_logo') === '1') {
            if ($logoPath && file_exists(PUBLIC_PATH . $logoPath)) @unlink(PUBLIC_PATH . $logoPath);
            $logoPath = '';
        }

        // Hapus Logo Footer
        if ($this->post('hapus_logo_footer') === '1') {
            if ($logoFooterPath && file_exists(PUBLIC_PATH . $logoFooterPath)) @unlink(PUBLIC_PATH . $logoFooterPath);
            $logoFooterPath = '';
        }

        // Hapus Favicon
        if ($this->post('hapus_favicon') === '1') {
            if ($faviconPath && file_exists(PUBLIC_PATH . $faviconPath)) @unlink(PUBLIC_PATH . $faviconPath);
            $faviconPath = '';
        }

        // 1. Upload Logo Utama
        if (!empty($_FILES['logo']['name']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $ext  = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
            if (in_array($ext, $allowed)) {
                $fname = 'logo_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $dir . '/' . $fname)) {
                    if ($logoPath && file_exists(PUBLIC_PATH . $logoPath)) @unlink(PUBLIC_PATH . $logoPath);
                    $logoPath = '/uploads/setting/' . $fname;
                }
            }
        }

        // 2. Upload Logo Footer
        if (!empty($_FILES['logo_footer']['name']) && $_FILES['logo_footer']['error'] === UPLOAD_ERR_OK) {
            $ext  = strtolower(pathinfo($_FILES['logo_footer']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
            if (in_array($ext, $allowed)) {
                $fname = 'logo_footer_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['logo_footer']['tmp_name'], $dir . '/' . $fname)) {
                    if ($logoFooterPath && file_exists(PUBLIC_PATH . $logoFooterPath)) @unlink(PUBLIC_PATH . $logoFooterPath);
                    $logoFooterPath = '/uploads/setting/' . $fname;
                }
            }
        }

        // 3. Upload Icon Taskbar (.ico, .png, .svg)
        if (!empty($_FILES['favicon']['name']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK) {
            $ext  = strtolower(pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION));
            $allowed = ['ico', 'png', 'svg', 'cur'];
            if (in_array($ext, $allowed)) {
                $fname = 'favicon_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['favicon']['tmp_name'], $dir . '/' . $fname)) {
                    if ($faviconPath && file_exists(PUBLIC_PATH . $faviconPath)) @unlink(PUBLIC_PATH . $faviconPath);
                    $faviconPath = '/uploads/setting/' . $fname;
                }
            }
        }

        $this->settingModel->saveSetting([
            'nama_aplikasi' => $this->post('nama_aplikasi'),
            'singkatan'     => $this->post('singkatan'),
            'alamat'        => $this->post('alamat'),
            'telepon'       => $this->post('telepon'),
            'email'         => $this->post('email'),
            'website'       => $this->post('website'),
            'deskripsi'     => $this->post('deskripsi'),
            'footer_text'   => $this->post('footer_text'),
            'logo'          => $logoPath,
            'logo_footer'   => $logoFooterPath,
            'favicon'       => $faviconPath,
        ]);

        $this->flash('success', 'Pengaturan aplikasi & logo berhasil disimpan.');
        $this->redirect('/admin/setting');
    }
}

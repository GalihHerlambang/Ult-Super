<?php
require_once APP_PATH . '/core/Controller.php';

class LoginController extends Controller
{
    private User $userModel;
    private Setting $settingModel;

    public function __construct()
    {
        $this->userModel    = new User();
        $this->settingModel = new Setting();
    }

    public function index(): void
    {
        $this->login();
    }

    public function login(): void
    {
        // Jika sudah login, redirect ke modul sesuai role
        if (!empty($_SESSION['user_id'])) {
            $this->redirectByRole($_SESSION['role']);
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $user = $this->userModel->findByUsername($username);

            if ($user && ($user['is_active'] ?? true) && $this->userModel->verifyPassword($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama']    = $user['nama'];
                $_SESSION['role']    = $user['role'];
                $_SESSION['email']   = $user['email'];

                $this->flash('success', 'Selamat datang kembali, ' . $user['nama'] . '!');
                $this->redirectByRole($user['role']);
            } else {
                $error = 'Username atau password salah, atau akun tidak aktif.';
            }
        }

        $setting   = $this->settingModel->getSetting();
        $pageTitle = 'Masuk / Login';
        $this->view('auth/views/login', compact('error', 'setting', 'pageTitle'), 'auth');
    }

    public function register(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirectByRole($_SESSION['role']);
        }

        $error   = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama            = trim($_POST['nama'] ?? '');
            $username        = strtolower(trim($_POST['username'] ?? ''));
            $email           = strtolower(trim($_POST['email'] ?? ''));
            $password        = trim($_POST['password'] ?? '');
            $passwordConfirm = trim($_POST['password_confirm'] ?? '');

            // Validasi Input
            if (empty($nama) || empty($username) || empty($email) || empty($password)) {
                $error = 'Semua bidang form wajib diisi.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Format alamat email tidak valid.';
            } elseif (!preg_match('/^[a-z0-9_]{3,20}$/', $username)) {
                $error = 'Username hanya boleh huruf kecil, angka, dan underscore (3-20 karakter).';
            } elseif (strlen($password) < 4) {
                $error = 'Password minimal 4 karakter.';
            } elseif ($password !== $passwordConfirm) {
                $error = 'Konfirmasi password tidak cocok.';
            } else {
                // Cek keunikan username & email
                $existingUsername = $this->userModel->findByUsername($username);
                $existingEmail    = !empty($this->userModel->where(['email' => $email]));

                if ($existingUsername) {
                    $error = 'Username sudah digunakan, silakan pilih username lain.';
                } elseif ($existingEmail) {
                    $error = 'Email sudah terdaftar, silakan gunakan email lain.';
                } else {
                    // Buat akun baru dengan role default: PENGADU
                    $newUser = $this->userModel->insert([
                        'nama'      => $nama,
                        'username'  => $username,
                        'email'     => $email,
                        'password'  => password_hash($password, PASSWORD_DEFAULT),
                        'role'      => 'pengadu', // Default role pengadu
                        'is_active' => true,
                    ]);

                    // Direct auto-login setelah registrasi
                    $_SESSION['user_id'] = $newUser['id'];
                    $_SESSION['nama']    = $newUser['nama'];
                    $_SESSION['role']    = 'pengadu';
                    $_SESSION['email']   = $newUser['email'];

                    $this->flash('success', 'Registrasi berhasil! Akun Pengadu Anda telah aktif.');
                    $this->redirect('pengadu/aduan/buat');
                }
            }
        }

        $setting   = $this->settingModel->getSetting();
        $pageTitle = 'Registrasi Akun Pengadu';
        $this->view('auth/views/register', compact('error', 'setting', 'pageTitle'), 'auth');
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();
        }

        session_start();
        $_SESSION['flash'] = ['type' => 'info', 'message' => 'Anda telah berhasil keluar dari akun.'];
        $this->redirect('login');
    }

    private function redirectByRole(string $role): void
    {
        $map = [
            'admin'    => 'admin/dashboard',
            'helpdesk' => 'helpdesk/dashboard',
            'atasan'   => 'atasan/dashboard',
            'pengadu'  => 'pengadu/dashboard',
        ];
        $this->redirect($map[$role] ?? 'pengadu/dashboard');
    }
}

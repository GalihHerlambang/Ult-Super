<?php
require_once APP_PATH . '/core/Controller.php';

class UserController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index(): void
    {
        $this->requireRole('admin');
        $users     = $this->userModel->all();
        $pageTitle = 'Manajemen Pengguna';
        $this->view('admin/views/user/index', compact('users', 'pageTitle'));
    }

    public function tambah(): void
    {
        $this->requireRole('admin');
        $pageTitle = 'Tambah Pengguna';
        $this->view('admin/views/user/form', compact('pageTitle'));
    }

    public function simpan(): void
    {
        $this->requireRole('admin');
        $username = $this->post('username');
        $existing = $this->userModel->findByUsername($username);
        if ($existing) {
            $this->flash('error', 'Username sudah digunakan.');
            $this->redirect('/admin/user/tambah');
        }

        $this->userModel->insert([
            'nama'      => $this->post('nama'),
            'username'  => $username,
            'password'  => password_hash($this->post('password'), PASSWORD_DEFAULT),
            'role'      => $this->post('role'),
            'email'     => $this->post('email'),
            'is_active' => $this->post('is_active') === '1',
        ]);

        $this->flash('success', 'Pengguna berhasil ditambahkan.');
        $this->redirect('/admin/user');
    }

    public function edit(string $id): void
    {
        $this->requireRole('admin');
        $user = $this->userModel->find((int)$id);
        if (!$user) { $this->redirect('/admin/user'); }
        $pageTitle = 'Edit Pengguna: ' . $user['nama'];
        $this->view('admin/views/user/form', compact('user', 'pageTitle'));
    }

    public function update(string $id): void
    {
        $this->requireRole('admin');
        $data = [
            'nama'      => $this->post('nama'),
            'role'      => $this->post('role'),
            'email'     => $this->post('email'),
            'is_active' => $this->post('is_active') === '1',
        ];
        $newPassword = $this->post('password');
        if ($newPassword) {
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }
        $this->userModel->update((int)$id, $data);
        $this->flash('success', 'Pengguna berhasil diperbarui.');
        $this->redirect('/admin/user');
    }

    public function hapus(string $id): void
    {
        $this->requireRole('admin');
        if ((int)$id === $_SESSION['user_id']) {
            $this->flash('error', 'Tidak dapat menghapus akun sendiri.');
            $this->redirect('/admin/user');
        }
        $this->userModel->delete((int)$id);
        $this->flash('success', 'Pengguna berhasil dihapus.');
        $this->redirect('/admin/user');
    }
}

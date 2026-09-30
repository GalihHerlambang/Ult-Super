<?php
/**
 * Controller.php — Base controller dengan helper umum
 */
abstract class Controller
{
    /**
     * Render view dengan layout
     */
    protected function view(string $viewPath, array $data = [], string $layout = 'main'): void
    {
        View::render($viewPath, $data, $layout);
    }

    /**
     * Redirect ke URL (otomatis menambahkan BASE_URL)
     */
    protected function redirect(string $url): void
    {
        if (!preg_match('/^https?:\/\//i', $url)) {
            $url = url($url);
        }
        header('Location: ' . $url);
        exit;
    }

    /**
     * Pastikan user sudah login
     */
    protected function requireLogin(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Pastikan user memiliki role tertentu
     */
    protected function requireRole(string|array $roles): void
    {
        $this->requireLogin();
        $userRole = $_SESSION['role'] ?? '';
        $allowed  = is_array($roles) ? $roles : [$roles];
        if (!in_array($userRole, $allowed)) {
            http_response_code(403);
            echo "<h1>403 — Akses Ditolak</h1><p>Anda tidak memiliki hak akses ke halaman ini.</p>";
            exit;
        }
    }

    /**
     * Ambil data POST, sanitize
     */
    protected function post(string $key, mixed $default = null): mixed
    {
        return isset($_POST[$key]) ? htmlspecialchars_decode(trim($_POST[$key])) : $default;
    }

    /**
     * Set flash message
     */
    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    /**
     * Ambil dan hapus flash message
     */
    protected function getFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    /**
     * Return JSON response
     */
    protected function json(mixed $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

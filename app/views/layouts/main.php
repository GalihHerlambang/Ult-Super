<?php
// Tentukan module dari URL untuk sidebar active state
$segments = array_filter(explode('/', trim($_GET['url'] ?? '', '/')));
$segments = array_values($segments);
$currentModule     = $segments[0] ?? 'pengadu';
$currentController = $segments[1] ?? 'dashboard';
$currentAction     = $segments[2] ?? 'index';
$currentRole       = $_SESSION['role'] ?? '';

function isActive(string $module, string $controller = '', string $action = ''): string {
    global $currentModule, $currentController, $currentAction;
    if ($module !== $currentModule) return '';
    if ($controller && $controller !== $currentController) return '';
    if ($action && $action !== $currentAction) return '';
    return 'active';
}

// Sidebar items per role (menggunakan helper url())
$sidebarItems = [];
switch ($currentRole) {
    case 'admin':
        $sidebarItems = [
            ['section' => 'Utama'],
            ['url' => url('admin/dashboard'), 'label' => 'Dashboard', 'icon' => 'bi-house', 'mod' => 'admin', 'ctrl' => 'dashboard'],
            ['url' => url(''), 'label' => 'Portal Landing', 'icon' => 'bi-globe', 'mod' => 'home', 'ctrl' => 'home'],
            ['section' => 'Master Data'],
            ['url' => url('admin/layanan'), 'label' => 'Layanan', 'icon' => 'bi-grid-3x3-gap', 'mod' => 'admin', 'ctrl' => 'layanan'],
            ['url' => url('admin/user'), 'label' => 'Pengguna', 'icon' => 'bi-people', 'mod' => 'admin', 'ctrl' => 'user'],
            ['section' => 'Sistem'],
            ['url' => url('admin/setting'), 'label' => 'Pengaturan Aplikasi', 'icon' => 'bi-gear', 'mod' => 'admin', 'ctrl' => 'setting'],
        ];
        break;
    case 'helpdesk':
        $sidebarItems = [
            ['section' => 'Utama'],
            ['url' => url('helpdesk/dashboard'), 'label' => 'Dashboard', 'icon' => 'bi-house', 'mod' => 'helpdesk', 'ctrl' => 'dashboard'],
            ['url' => url('helpdesk/aduan'), 'label' => 'Aduan Masuk', 'icon' => 'bi-inbox', 'mod' => 'helpdesk', 'ctrl' => 'aduan'],
            ['url' => url(''), 'label' => 'Portal Landing', 'icon' => 'bi-globe', 'mod' => 'home', 'ctrl' => 'home'],
        ];
        break;
    case 'atasan':
        $sidebarItems = [
            ['section' => 'Utama'],
            ['url' => url('atasan/dashboard'), 'label' => 'Dashboard', 'icon' => 'bi-house', 'mod' => 'atasan', 'ctrl' => 'dashboard'],
            ['url' => url('atasan/persetujuan'), 'label' => 'Persetujuan', 'icon' => 'bi-check2-circle', 'mod' => 'atasan', 'ctrl' => 'persetujuan'],
            ['url' => url(''), 'label' => 'Portal Landing', 'icon' => 'bi-globe', 'mod' => 'home', 'ctrl' => 'home'],
        ];
        break;
    default:
        $sidebarItems = [
            ['section' => 'Utama'],
            ['url' => url('pengadu/dashboard'), 'label' => 'Dashboard', 'icon' => 'bi-house', 'mod' => 'pengadu', 'ctrl' => 'dashboard'],
            ['url' => url('pengadu/aduan'), 'label' => 'Aduan Saya', 'icon' => 'bi-file-earmark-text', 'mod' => 'pengadu', 'ctrl' => 'aduan'],
            ['url' => url('pengadu/aduan/buat'), 'label' => 'Buat Aduan Baru', 'icon' => 'bi-plus-circle', 'mod' => 'pengadu', 'ctrl' => 'aduan', 'act' => 'buat'],
            ['url' => url(''), 'label' => 'Portal Landing', 'icon' => 'bi-globe', 'mod' => 'home', 'ctrl' => 'home'],
        ];
        break;
}

$userInitial = strtoupper(substr($_SESSION['nama'] ?? 'U', 0, 1));
$roleBadge   = ['admin' => 'Administrator', 'helpdesk' => 'Helpdesk', 'atasan' => 'Atasan', 'pengadu' => 'Pengadu'][$currentRole] ?? $currentRole;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($setting['deskripsi'] ?? '') ?>">
    <title><?= htmlspecialchars($pageTitle ?? 'Beranda') ?> — <?= htmlspecialchars($setting['nama_aplikasi'] ?? 'Super Aplikasi Pengaduan') ?></title>
    <?php if (!empty($setting['favicon'])): ?>
    <link rel="icon" type="image/x-icon" href="<?= htmlspecialchars(url($setting['favicon'])) ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= htmlspecialchars(url($setting['favicon'])) ?>">
    <?php endif; ?>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="<?= url('assets/css/app.css') ?>">
</head>
<body>
<div class="app-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <?php if (!empty($setting['logo'])): ?>
                <img src="<?= htmlspecialchars(url($setting['logo'])) ?>" alt="Logo" style="width:40px;height:40px;border-radius:10px;object-fit:cover;">
            <?php else: ?>
                <div class="sidebar-brand-logo"><?= strtoupper(substr($setting['singkatan'] ?? 'S', 0, 1)) ?></div>
            <?php endif; ?>
            <div class="sidebar-brand-text">
                <strong><?= htmlspecialchars($setting['nama_aplikasi'] ?? 'Super Aplikasi') ?></strong>
                <span><?= htmlspecialchars($setting['singkatan'] ?? '') ?></span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <?php foreach ($sidebarItems as $item): ?>
                <?php if (isset($item['section'])): ?>
                    <div class="sidebar-section"><?= $item['section'] ?></div>
                <?php else: ?>
                    <a href="<?= $item['url'] ?>"
                       class="<?= isActive($item['mod'], $item['ctrl'], $item['act'] ?? '') ?>">
                        <i class="bi <?= $item['icon'] ?>"></i>
                        <?= $item['label'] ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar"><?= $userInitial ?></div>
                <div class="sidebar-user-info">
                    <strong><?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?></strong>
                    <span><?= $roleBadge ?></span>
                </div>
                <a href="<?= url('logout') ?>" class="sidebar-user-logout" title="Keluar / Logout" data-confirm="Yakin ingin keluar dari akun?">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button id="sidebarToggle" class="btn btn-sm btn-secondary d-none" style="display:none">
                    <i class="bi bi-list"></i>
                </button>
                <span class="topbar-title"><?= htmlspecialchars($pageTitle ?? 'Beranda') ?></span>
            </div>
            <div class="topbar-right">
                <a href="<?= url('') ?>" class="btn-portal-home" title="Ke Halaman Depan">
                    <i class="bi bi-globe"></i> Portal Depan
                </a>
                <a href="<?= url('logout') ?>" class="btn btn-sm btn-danger" title="Keluar dari Akun" data-confirm="Yakin ingin keluar dari akun?">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </header>

        <!-- Flash Message -->
        <?php
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        if ($flash):
            $alertClass = ['success' => 'alert-success', 'error' => 'alert-danger', 'warning' => 'alert-warning', 'info' => 'alert-info'][$flash['type']] ?? 'alert-info';
            $alertIcon  = ['success' => 'bi-check-circle', 'error' => 'bi-x-circle', 'warning' => 'bi-exclamation-triangle', 'info' => 'bi-info-circle'][$flash['type']] ?? 'bi-info-circle';
        ?>
        <div style="padding: 1rem 1.75rem 0;">
            <div class="alert <?= $alertClass ?>" data-auto-dismiss>
                <i class="bi <?= $alertIcon ?>"></i>
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Page Content -->
        <main class="page-content">
            <?= $content ?>
        </main>

        <!-- Footer -->
        <footer style="padding:.75rem 1.75rem;border-top:1px solid var(--border);background:var(--bg-card);font-size:.78rem;color:var(--text-muted);text-align:center;">
            <?= htmlspecialchars($setting['footer_text'] ?? '') ?>
        </footer>
    </div>
</div>

<script src="<?= url('assets/js/app.js') ?>"></script>
</body>
</html>

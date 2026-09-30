<?php
$pageTitle = 'Masuk / Login';
?>
<div class="bright-auth-wrapper">
    <!-- Floating background decorative blobs -->
    <div class="auth-blob blob-1"></div>
    <div class="auth-blob blob-2"></div>

    <div class="bright-auth-card">
        <!-- Brand Header -->
        <div class="auth-brand-header">
            <?php if (!empty($setting['logo'])): ?>
                <img src="<?= htmlspecialchars(url($setting['logo'])) ?>" alt="Logo" class="auth-brand-logo">
            <?php else: ?>
                <div class="auth-brand-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
            <?php endif; ?>
            <h1><?= htmlspecialchars($setting['nama_aplikasi'] ?? 'Super Pengaduan') ?></h1>
            <p><?= htmlspecialchars($setting['deskripsi'] ?? 'Masuk ke sistem pengaduan layanan') ?></p>
        </div>

        <!-- Alert Error / Flash -->
        <?php if ($error): ?>
        <div class="bright-alert alert-error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div><?= htmlspecialchars($error) ?></div>
        </div>
        <?php endif; ?>

        <?php
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        if ($flash):
            $alertCls = $flash['type'] === 'success' ? 'alert-success' : 'alert-info';
            $alertIcon = $flash['type'] === 'success' ? 'bi-check-circle-fill' : 'bi-info-circle-fill';
        ?>
        <div class="bright-alert <?= $alertCls ?>">
            <i class="bi <?= $alertIcon ?>"></i>
            <div><?= htmlspecialchars($flash['message']) ?></div>
        </div>
        <?php endif; ?>

        <!-- Form Login -->
        <form method="POST" action="<?= url('login') ?>" id="loginForm">
            <div class="bright-form-group">
                <label for="username"><i class="bi bi-person-fill"></i> Username</label>
                <div class="input-icon-wrapper">
                    <input type="text" id="username" name="username" class="bright-input"
                           placeholder="Masukkan username Anda" required autocomplete="username"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
            </div>

            <div class="bright-form-group">
                <label for="password"><i class="bi bi-lock-fill"></i> Password</label>
                <div class="input-icon-wrapper">
                    <input type="password" id="password" name="password" class="bright-input"
                           placeholder="Masukkan password Anda" required autocomplete="current-password">
                    <button type="button" class="btn-toggle-pw" onclick="togglePassword('password', 'eyeIcon')" title="Tampilkan/sembunyikan password">
                        <i class="bi bi-eye-fill" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-bright-primary" id="loginBtn">
                <i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang
            </button>
        </form>

        <!-- Registration Callout Banner for Pengadu -->
        <div class="register-prompt-box">
            <div class="prompt-text">
                <strong>Belum punya akun pengadu?</strong>
                <span>Daftar dalam 10 detik untuk menyampaikan pengaduan Anda.</span>
            </div>
            <a href="<?= url('register') ?>" class="btn-register-link">
                <i class="bi bi-person-plus-fill"></i> Daftar Akun Pengadu
            </a>
        </div>

        <!-- Home & Demo Credentials helper -->
        <div class="auth-card-footer">
            <a href="<?= url('') ?>" class="link-home">
                <i class="bi bi-arrow-left"></i> Kembali ke Halaman Depan
            </a>

            <!-- Credentials Accordion / Helper -->
            <details class="credentials-helper">
                <summary><i class="bi bi-key-fill me-1"></i> Akun Demo Login (Klik untuk lihat)</summary>
                <div class="credentials-list">
                    <div class="cred-item"><strong>Admin:</strong> <code>admin</code> / <code>admin123</code></div>
                    <div class="cred-item"><strong>Helpdesk:</strong> <code>helpdesk</code> / <code>helpdesk123</code></div>
                    <div class="cred-item"><strong>Atasan:</strong> <code>atasan</code> / <code>atasan123</code></div>
                    <div class="cred-item"><strong>Pengadu:</strong> <code>pengadu</code> / <code>pengadu123</code></div>
                </div>
            </details>
        </div>
    </div>
</div>

<style>
.bright-auth-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #e0e7ff 0%, #f3e8ff 50%, #fae8ff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    position: relative;
    overflow: hidden;
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
}

/* Background floating glow blobs */
.auth-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.6;
    pointer-events: none;
}
.blob-1 {
    width: 350px; height: 350px;
    background: #818cf8;
    top: -100px; left: -100px;
}
.blob-2 {
    width: 400px; height: 400px;
    background: #c084fc;
    bottom: -120px; right: -120px;
}

/* Card */
.bright-auth-card {
    background: #ffffff;
    border-radius: 1.75rem;
    box-shadow: 0 25px 50px -12px rgba(99, 102, 241, 0.25), 0 0 0 1px rgba(99, 102, 241, 0.08);
    width: 100%;
    max-width: 460px;
    padding: 2.75rem 2.25rem 2rem;
    position: relative;
    z-index: 10;
}

/* Brand Header */
.auth-brand-header {
    text-align: center;
    margin-bottom: 2rem;
}
.auth-brand-icon {
    width: 64px; height: 64px;
    background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    border-radius: 1.25rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.85rem;
    color: #ffffff;
    margin-bottom: 1rem;
    box-shadow: 0 10px 20px rgba(99, 102, 241, 0.35);
}
.auth-brand-logo {
    width: 64px; height: 64px;
    border-radius: 1.25rem;
    object-fit: cover;
    margin-bottom: 1rem;
    box-shadow: 0 10px 20px rgba(99, 102, 241, 0.25);
}
.auth-brand-header h1 {
    color: #0f172a;
    font-size: 1.35rem;
    font-weight: 800;
    margin-bottom: 0.35rem;
}
.auth-brand-header p {
    color: #64748b;
    font-size: 0.875rem;
}

/* Alerts */
.bright-alert {
    padding: 0.85rem 1.1rem;
    border-radius: 0.875rem;
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: flex-start;
    gap: 0.65rem;
    margin-bottom: 1.5rem;
}
.alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
}
.alert-success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}
.alert-info {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e40af;
}

/* Inputs */
.bright-form-group {
    margin-bottom: 1.25rem;
}
.bright-form-group label {
    display: block;
    font-size: 0.85rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 0.45rem;
}
.bright-form-group label i {
    color: #6366f1;
    margin-right: 0.25rem;
}
.input-icon-wrapper {
    position: relative;
}
.bright-input {
    width: 100%;
    padding: 0.75rem 1rem;
    font-family: inherit;
    font-size: 0.925rem;
    color: #0f172a;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 0.875rem;
    outline: none;
    transition: all 0.2s ease;
}
.bright-input:focus {
    background: #ffffff;
    border-color: #6366f1;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
}
.btn-toggle-pw {
    position: absolute;
    right: 0.85rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    font-size: 1.1rem;
}
.btn-toggle-pw:hover { color: #6366f1; }

/* Buttons */
.btn-bright-primary {
    width: 100%;
    padding: 0.85rem;
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    color: #ffffff;
    border: none;
    border-radius: 0.875rem;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.35);
    transition: all 0.25s ease;
    margin-top: 0.5rem;
}
.btn-bright-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(99, 102, 241, 0.5);
}

/* Registration Prompt Box */
.register-prompt-box {
    margin-top: 1.75rem;
    padding: 1.25rem;
    background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
    border: 1px dashed #c4b5fd;
    border-radius: 1rem;
    text-align: center;
}
.prompt-text strong {
    display: block;
    color: #4c1d95;
    font-size: 0.9rem;
    font-weight: 700;
}
.prompt-text span {
    display: block;
    color: #6b21a8;
    font-size: 0.78rem;
    margin-top: 0.15rem;
    margin-bottom: 0.85rem;
}
.btn-register-link {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: #ffffff;
    color: #6d28d9;
    font-size: 0.85rem;
    font-weight: 700;
    padding: 0.55rem 1.1rem;
    border-radius: 999px;
    border: 1px solid #ddd6fe;
    box-shadow: 0 2px 6px rgba(109, 40, 217, 0.1);
    transition: all 0.2s ease;
}
.btn-register-link:hover {
    background: #6d28d9;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(109, 40, 217, 0.3);
}

/* Footer helper links */
.auth-card-footer {
    margin-top: 1.5rem;
    text-align: center;
    border-top: 1px solid #f1f5f9;
    padding-top: 1.25rem;
}
.link-home {
    font-size: 0.825rem;
    color: #64748b;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.link-home:hover { color: #6366f1; }

.credentials-helper {
    margin-top: 1rem;
    font-size: 0.78rem;
    color: #64748b;
    text-align: left;
}
.credentials-helper summary {
    cursor: pointer;
    font-weight: 600;
    color: #4f46e5;
    text-align: center;
}
.credentials-list {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.65rem;
    padding: 0.75rem;
    margin-top: 0.5rem;
}
.cred-item {
    margin-bottom: 0.25rem;
}
.cred-item code {
    background: #e2e8f0;
    padding: 0.1rem 0.35rem;
    border-radius: 4px;
    color: #1e293b;
}
</style>

<script>
function togglePassword(inputId, iconId) {
    const pw = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (pw.type === 'password') {
        pw.type = 'text';
        icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
    } else {
        pw.type = 'password';
        icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
    }
}

document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<i class="bi bi-arrow-repeat me-2"></i> Memproses...';
    btn.disabled = true;
});
</script>

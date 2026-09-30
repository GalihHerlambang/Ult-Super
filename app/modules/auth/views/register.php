<?php
$pageTitle = 'Registrasi Akun Pengadu';
?>
<div class="bright-auth-wrapper">
    <div class="auth-blob blob-1"></div>
    <div class="auth-blob blob-2"></div>

    <div class="bright-auth-card" style="max-width: 500px;">
        <!-- Header -->
        <div class="auth-brand-header">
            <?php if (!empty($setting['logo'])): ?>
                <img src="<?= htmlspecialchars(url($setting['logo'])) ?>" alt="Logo" class="auth-brand-logo">
            <?php else: ?>
                <div class="auth-brand-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
            <?php endif; ?>
            <h1>Daftar Akun Pengadu</h1>
            <p>Buat akun dalam hitungan detik untuk mulai menyampaikan laporan Anda.</p>
            <div style="display:inline-block;background:#ecfdf5;border:1px solid #a7f3d0;color:#047857;font-size:0.75rem;font-weight:700;padding:0.25rem 0.75rem;border-radius:999px;margin-top:0.5rem;">
                <i class="bi bi-shield-check me-1"></i> Hak Akses: Pengadu (Masyarakat / Pelapor)
            </div>
        </div>

        <!-- Alert Error -->
        <?php if ($error): ?>
        <div class="bright-alert alert-error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div><?= htmlspecialchars($error) ?></div>
        </div>
        <?php endif; ?>

        <!-- Form Registrasi -->
        <form method="POST" action="<?= url('register') ?>" id="registerForm">
            <div class="bright-form-group">
                <label for="nama"><i class="bi bi-person-badge-fill"></i> Nama Lengkap <span style="color:#ef4444">*</span></label>
                <input type="text" id="nama" name="nama" class="bright-input"
                       placeholder="mis. Budi Santoso" required
                       value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
            </div>

            <div class="grid-2-auth" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="bright-form-group">
                    <label for="username"><i class="bi bi-at"></i> Username <span style="color:#ef4444">*</span></label>
                    <input type="text" id="username" name="username" class="bright-input"
                           placeholder="mis. budi123" required pattern="[a-z0-9_]{3,20}"
                           title="Huruf kecil, angka, dan underscore (3-20 karakter)"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>

                <div class="bright-form-group">
                    <label for="email"><i class="bi bi-envelope-fill"></i> Email <span style="color:#ef4444">*</span></label>
                    <input type="email" id="email" name="email" class="bright-input"
                           placeholder="budi@example.com" required
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
            </div>

            <div class="grid-2-auth" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="bright-form-group">
                    <label for="password"><i class="bi bi-lock-fill"></i> Password <span style="color:#ef4444">*</span></label>
                    <div class="input-icon-wrapper">
                        <input type="password" id="password" name="password" class="bright-input"
                               placeholder="Min. 4 karakter" required>
                    </div>
                </div>

                <div class="bright-form-group">
                    <label for="password_confirm"><i class="bi bi-shield-lock-fill"></i> Konfirmasi <span style="color:#ef4444">*</span></label>
                    <div class="input-icon-wrapper">
                        <input type="password" id="password_confirm" name="password_confirm" class="bright-input"
                               placeholder="Ulangi password" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-bright-primary" id="registerBtn" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);box-shadow:0 8px 20px rgba(16,185,129,0.35);">
                <i class="bi bi-check-circle-fill"></i> Daftar Akun & Mulai Pengaduan
            </button>
        </form>

        <!-- Login Direct Prompt -->
        <div style="text-align:center;margin-top:1.75rem;padding-top:1.25rem;border-top:1px solid #f1f5f9;">
            <span style="font-size:0.875rem;color:#64748b;">Sudah memiliki akun?</span>
            <a href="<?= url('login') ?>" style="color:#10b981;font-weight:700;font-size:0.875rem;margin-left:0.35rem;">
                Masuk / Login di sini <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="auth-card-footer" style="margin-top:1rem;padding-top:0.75rem;border-top:none;">
            <a href="<?= url('') ?>" class="link-home">
                <i class="bi bi-arrow-left"></i> Kembali ke Halaman Depan
            </a>
        </div>
    </div>
</div>

<style>
.bright-auth-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #d1fae5 0%, #e0e7ff 50%, #f3e8ff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    position: relative;
    overflow: hidden;
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
}
.auth-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.6;
    pointer-events: none;
}
.blob-1 {
    width: 350px; height: 350px;
    background: #34d399;
    top: -100px; left: -100px;
}
.blob-2 {
    width: 400px; height: 400px;
    background: #818cf8;
    bottom: -120px; right: -120px;
}
.bright-auth-card {
    background: #ffffff;
    border-radius: 1.75rem;
    box-shadow: 0 25px 50px -12px rgba(16, 185, 129, 0.2), 0 0 0 1px rgba(16, 185, 129, 0.08);
    width: 100%;
    padding: 2.75rem 2.25rem 2rem;
    position: relative;
    z-index: 10;
}
.auth-brand-header {
    text-align: center;
    margin-bottom: 1.75rem;
}
.auth-brand-icon {
    width: 60px; height: 60px;
    border-radius: 1.25rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: #ffffff;
    margin-bottom: 0.75rem;
    box-shadow: 0 8px 18px rgba(16,185,129,0.3);
}
.auth-brand-logo {
    width: 60px; height: 60px;
    border-radius: 1.25rem;
    object-fit: cover;
    margin-bottom: 0.75rem;
}
.auth-brand-header h1 {
    color: #0f172a;
    font-size: 1.35rem;
    font-weight: 800;
    margin-bottom: 0.25rem;
}
.auth-brand-header p {
    color: #64748b;
    font-size: 0.85rem;
}
.bright-alert {
    padding: 0.85rem 1.1rem;
    border-radius: 0.875rem;
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: flex-start;
    gap: 0.65rem;
    margin-bottom: 1.25rem;
}
.alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
}
.bright-form-group {
    margin-bottom: 1.1rem;
}
.bright-form-group label {
    display: block;
    font-size: 0.825rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 0.35rem;
}
.bright-form-group label i {
    color: #10b981;
    margin-right: 0.25rem;
}
.bright-input {
    width: 100%;
    padding: 0.7rem 0.9rem;
    font-family: inherit;
    font-size: 0.875rem;
    color: #0f172a;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 0.875rem;
    outline: none;
    transition: all 0.2s ease;
}
.bright-input:focus {
    background: #ffffff;
    border-color: #10b981;
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
}
.btn-bright-primary {
    width: 100%;
    padding: 0.85rem;
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
    transition: all 0.25s ease;
    margin-top: 0.75rem;
}
.btn-bright-primary:hover {
    transform: translateY(-2px);
    opacity: 0.95;
}
.link-home {
    font-size: 0.825rem;
    color: #64748b;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.link-home:hover { color: #10b981; }

@media (max-width: 540px) {
    .grid-2-auth { grid-template-columns: 1fr !important; }
}
</style>

<script>
document.getElementById('registerForm').addEventListener('submit', function() {
    const btn = document.getElementById('registerBtn');
    btn.innerHTML = '<i class="bi bi-arrow-repeat me-2"></i> Membuat Akun...';
    btn.disabled = true;
});
</script>

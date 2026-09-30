<div class="page-header">
    <div>
        <h1>Pengaturan Aplikasi</h1>
        <p>Kelola identitas, logo (Main, Footer, Icon Taskbar .ICO), alamat, dan tampilan aplikasi.</p>
    </div>
</div>

<form method="POST" action="<?= url('admin/setting/simpan') ?>" enctype="multipart/form-data">
<div class="grid-2" style="gap:1.5rem;align-items:start;">

    <!-- Identitas Aplikasi & Upload Logo -->
    <div>
        <div class="card mb-4">
            <div class="card-header"><h5><i class="bi bi-building me-2"></i>Identitas Aplikasi</h5></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Nama Aplikasi <span class="req">*</span></label>
                    <input type="text" name="nama_aplikasi" class="form-control" required
                           value="<?= htmlspecialchars($settingData['nama_aplikasi'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Singkatan / Kode</label>
                    <input type="text" name="singkatan" class="form-control"
                           value="<?= htmlspecialchars($settingData['singkatan'] ?? '') ?>">
                    <div class="form-hint">Tampil di sidebar jika tidak ada logo.</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-control" rows="2"><?= htmlspecialchars($settingData['deskripsi'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Teks Footer</label>
                    <input type="text" name="footer_text" class="form-control"
                           value="<?= htmlspecialchars($settingData['footer_text'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Section Upload Logo Aplikasi (Main, Footer, Taskbar ICO) -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5><i class="bi bi-images me-2 text-primary"></i>Logo & Icon Aplikasi</h5>
            </div>
            <div class="card-body">

                <!-- 1. Main Logo -->
                <div class="p-3 mb-3 rounded" style="background:var(--bg-body); border:1px solid var(--border);">
                    <label class="form-label fw-600 d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-window-sidebar text-primary me-1"></i> 1. Logo Utama (Header / Sidebar)</span>
                        <span class="badge badge-primary" style="font-size:0.68rem;">Header & Sidebar</span>
                    </label>
                    <?php if (!empty($settingData['logo'])): ?>
                        <div class="d-flex align-items-center gap-3 mb-2 p-2 bg-white rounded border">
                            <img src="<?= htmlspecialchars(url($settingData['logo'])) ?>" alt="Logo Utama"
                                 style="height:46px; border-radius:6px; object-fit:contain;">
                            <div class="text-xs text-muted">Path: <?= htmlspecialchars($settingData['logo']) ?></div>
                            <div class="form-check ms-auto mb-0" style="font-size:0.8rem;">
                                <input type="checkbox" name="hapus_logo" id="hapus_logo" value="1">
                                <label for="hapus_logo" class="text-danger fw-600"><i class="bi bi-trash"></i> Hapus</label>
                            </div>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    <div class="form-hint text-xs mt-1">Format: PNG, JPG, JPEG, SVG, WEBP. Disarankan 200x200px atau proporsional transparan.</div>
                </div>

                <!-- 2. Logo Footer -->
                <div class="p-3 mb-3 rounded" style="background:var(--bg-body); border:1px solid var(--border);">
                    <label class="form-label fw-600 d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-layout-text-window-reverse text-success me-1"></i> 2. Logo Footer</span>
                        <span class="badge badge-success" style="font-size:0.68rem;">Footer Landing Page</span>
                    </label>
                    <?php if (!empty($settingData['logo_footer'])): ?>
                        <div class="d-flex align-items-center gap-3 mb-2 p-2 bg-white rounded border">
                            <img src="<?= htmlspecialchars(url($settingData['logo_footer'])) ?>" alt="Logo Footer"
                                 style="height:46px; border-radius:6px; object-fit:contain;">
                            <div class="text-xs text-muted">Path: <?= htmlspecialchars($settingData['logo_footer']) ?></div>
                            <div class="form-check ms-auto mb-0" style="font-size:0.8rem;">
                                <input type="checkbox" name="hapus_logo_footer" id="hapus_logo_footer" value="1">
                                <label for="hapus_logo_footer" class="text-danger fw-600"><i class="bi bi-trash"></i> Hapus</label>
                            </div>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="logo_footer" class="form-control" accept="image/*">
                    <div class="form-hint text-xs mt-1">Format: PNG, JPG, SVG, WEBP. Ditampilkan khusus di bagian bawah (Footer) portal.</div>
                </div>

                <!-- 3. Icon Taskbar (.ICO) & Favicon Browser -->
                <div class="p-3 rounded" style="background:var(--bg-body); border:1px solid var(--border);">
                    <label class="form-label fw-600 d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-app-indicator text-warning me-1"></i> 3. Icon Taskbar & Favicon Browser (.ICO)</span>
                        <span class="badge badge-warning" style="font-size:0.68rem;">Taskbar & Favicon</span>
                    </label>
                    <?php if (!empty($settingData['favicon'])): ?>
                        <div class="d-flex align-items-center gap-3 mb-2 p-2 bg-white rounded border">
                            <img src="<?= htmlspecialchars(url($settingData['favicon'])) ?>" alt="Favicon Taskbar"
                                 style="height:36px; width:36px; object-fit:contain;">
                            <div class="text-xs text-muted">Path: <?= htmlspecialchars($settingData['favicon']) ?></div>
                            <div class="form-check ms-auto mb-0" style="font-size:0.8rem;">
                                <input type="checkbox" name="hapus_favicon" id="hapus_favicon" value="1">
                                <label for="hapus_favicon" class="text-danger fw-600"><i class="bi bi-trash"></i> Hapus</label>
                            </div>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="favicon" class="form-control" accept=".ico,.png,.svg,.cur">
                    <div class="form-hint text-xs mt-1">
                        <strong>Format yang didukung:</strong> <span class="badge badge-warning">.ICO</span>, .PNG, .SVG.
                        <br>Disarankan berkas bertipe <code>.ico</code> (32x32px / 64x64px) untuk tampilan icon terbaik pada Windows Taskbar & Tab Browser.
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Kontak & Alamat -->
    <div>
        <div class="card">
            <div class="card-header"><h5><i class="bi bi-geo-alt me-2"></i>Alamat & Kontak</h5></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="3"><?= htmlspecialchars($settingData['alamat'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <div style="position:relative;">
                        <i class="bi bi-telephone" style="position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:var(--text-muted);"></i>
                        <input type="text" name="telepon" class="form-control" style="padding-left:2.25rem;"
                               value="<?= htmlspecialchars($settingData['telepon'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div style="position:relative;">
                        <i class="bi bi-envelope" style="position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:var(--text-muted);"></i>
                        <input type="email" name="email" class="form-control" style="padding-left:2.25rem;"
                               value="<?= htmlspecialchars($settingData['email'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Website</label>
                    <div style="position:relative;">
                        <i class="bi bi-globe" style="position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:var(--text-muted);"></i>
                        <input type="url" name="website" class="form-control" style="padding-left:2.25rem;"
                               value="<?= htmlspecialchars($settingData['website'] ?? '') ?>">
                    </div>
                </div>

                <!-- Info terakhir diperbarui -->
                <?php if (!empty($settingData['updated_at'])): ?>
                <div class="alert alert-info mt-4">
                    <i class="bi bi-clock"></i>
                    Terakhir diperbarui: <?= date('d M Y H:i', strtotime($settingData['updated_at'])) ?>
                </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:.5rem;">
                    <i class="bi bi-check-lg"></i> Simpan Pengaturan
                </button>
            </div>
        </div>
    </div>

</div>
</form>

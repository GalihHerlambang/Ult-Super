<?php $isEdit = isset($layanan); ?>
<div class="page-header">
    <div>
        <h1><?= $isEdit ? 'Edit Layanan' : 'Tambah Layanan' ?></h1>
    </div>
    <a href="<?= url('admin/layanan') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-header"><h5><i class="bi bi-grid me-2"></i>Data Layanan</h5></div>
    <div class="card-body">
        <form method="POST" action="<?= $isEdit ? url('admin/layanan/update/' . $layanan['id']) : url('admin/layanan/simpan') ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label" for="nama">Nama Layanan <span class="req">*</span></label>
                <input type="text" id="nama" name="nama" class="form-control" required
                       value="<?= htmlspecialchars($layanan['nama'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($layanan['deskripsi'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="file_template">Template Berkas / Syarat (Opsional)</label>
                <?php if (!empty($layanan['file_template'])): ?>
                    <div class="mb-2 p-2 border rounded d-flex align-items-center justify-content-between" style="background:var(--bg-subtle, #f8fafc);">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-text text-primary" style="font-size:1.2rem;"></i>
                            <a href="<?= url($layanan['file_template']) ?>" target="_blank" class="fw-500 text-sm">
                                <?= htmlspecialchars($layanan['nama_file_template'] ?? 'Download Template') ?>
                            </a>
                        </div>
                        <label class="form-check text-danger mb-0" style="font-size:.8rem;cursor:pointer;">
                            <input type="checkbox" name="hapus_file_template" value="1">
                            <span>Hapus file</span>
                        </label>
                    </div>
                <?php endif; ?>
                <input type="file" id="file_template" name="file_template" class="form-control"
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip">
                <div class="form-hint">Format file template: PDF, DOC, DOCX, XLS, XLSX, ZIP, Gambar.</div>
            </div>
            <div class="form-group">
                <label class="form-label">Sifat Template Berkas Bagi User</label>
                <div class="d-flex gap-3">
                    <label class="form-check">
                        <input type="radio" name="is_template_wajib" value="0" <?= (!isset($layanan) || empty($layanan['is_template_wajib'])) ? 'checked' : '' ?>>
                        <span>Tidak Wajib (Opsional)</span>
                    </label>
                    <label class="form-check">
                        <input type="radio" name="is_template_wajib" value="1" <?= (isset($layanan) && !empty($layanan['is_template_wajib'])) ? 'checked' : '' ?>>
                        <span>Wajib Upload saat Pengajuan</span>
                    </label>
                </div>
                <div class="form-hint">Jika diset Wajib, peserta/pengadu harus mengunggah berkas ini saat melakukan pengajuan.</div>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <div class="d-flex gap-2">
                    <label class="form-check">
                        <input type="radio" name="is_active" value="1" <?= (!isset($layanan) || $layanan['is_active']) ? 'checked' : '' ?>>
                        <span>Aktif</span>
                    </label>
                    <label class="form-check">
                        <input type="radio" name="is_active" value="0" <?= (isset($layanan) && !$layanan['is_active']) ? 'checked' : '' ?>>
                        <span>Nonaktif</span>
                    </label>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Layanan' ?>
                </button>
                <a href="<?= url('admin/layanan') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

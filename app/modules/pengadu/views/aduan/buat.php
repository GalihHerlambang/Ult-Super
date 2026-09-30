<div class="page-header">
    <div><h1>Buat Aduan Baru</h1><p>Pilih layanan dan isi formulir pengaduan.</p></div>
    <a href="<?= url('pengadu/aduan') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<form method="POST" action="<?= url('pengadu/aduan/simpan') ?>" enctype="multipart/form-data" id="aduanForm">
<div class="grid-2" style="gap:1.5rem;align-items:start;">

    <!-- Pilih Layanan -->
    <div class="card" style="position:sticky;top:80px;">
        <div class="card-header"><h5><i class="bi bi-grid me-2"></i>1. Pilih Layanan</h5></div>
        <div class="card-body">
            <?php if (empty($layanans)): ?>
            <div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Belum ada layanan tersedia. Hubungi admin.</div>
            <?php else: ?>
            <div id="layananList">
                <?php foreach ($layanans as $l): ?>
                <label class="layanan-option" style="display:block;cursor:pointer;margin-bottom:.65rem;">
                    <input type="radio" name="layanan_id" value="<?= $l['id'] ?>" style="display:none;" required>
                    <div class="layanan-card" style="border:2px solid var(--border);border-radius:var(--radius-sm);padding:.9rem;transition:var(--transition);">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;background:rgba(79,70,229,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--primary);">
                                <i class="bi bi-folder2"></i>
                            </div>
                            <div>
                                <div class="fw-600 text-sm"><?= htmlspecialchars($l['nama']) ?></div>
                                <div class="text-xs text-muted"><?= htmlspecialchars($l['deskripsi'] ?? '') ?></div>
                            </div>
                        </div>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Formulir Dinamis -->
    <div>
        <div class="card mb-4" id="formDinamis" style="display:none;">
            <div class="card-header"><h5><i class="bi bi-ui-checks-grid me-2"></i>2. Isi Formulir</h5></div>
            <div class="card-body" id="fieldContainer">
                <div class="empty-state" style="padding:2rem;">
                    <div class="empty-state-icon"><i class="bi bi-arrow-left-circle"></i></div>
                    <p>Pilih layanan terlebih dahulu.</p>
                </div>
            </div>
        </div>

        <!-- Syarat / Template Berkas Card -->
        <div class="card mb-4" id="templateCard" style="display:none;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-file-earmark-check me-2"></i>Syarat / Template Berkas</h5>
                <span id="templateWajibBadge"></span>
            </div>
            <div class="card-body">
                <div id="templateInfoBox" class="p-3 mb-3 border rounded" style="background:var(--bg-subtle, #f8fafc);">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <div class="fw-600 text-sm" id="templateFileName"></div>
                            <div class="text-xs text-muted">Unduh template ini, isi data yang diperlukan, lalu unggah kembali di bawah.</div>
                        </div>
                        <a href="#" id="templateDownloadBtn" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download me-1"></i> Unduh Template
                        </a>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label class="form-label" for="berkas_template" id="berkasTemplateLabel">
                        Upload Berkas Syarat / Template Yang Sudah Diisi
                    </label>
                    <input type="file" name="berkas_template" id="berkas_template" class="form-control"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip">
                    <div class="form-hint">Format: PDF, DOC, DOCX, XLS, XLSX, ZIP, Gambar.</div>
                </div>
            </div>
        </div>

        <!-- Upload Lampiran -->
        <div class="card" id="lampiranCard" style="display:none;">
            <div class="card-header"><h5><i class="bi bi-paperclip me-2"></i>3. Lampiran Pendukung (Opsional)</h5></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Unggah Bukti Pendukung Lainnya</label>
                    <input type="file" name="lampiran[]" class="form-control" multiple
                           accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.zip">
                    <div class="form-hint">Format: JPG, PNG, PDF, DOC, ZIP. Maks 5 file.</div>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:.5rem;">
                    <i class="bi bi-send"></i> Kirim Aduan
                </button>
            </div>
        </div>
    </div>

</div>
</form>

<style>
.layanan-option input:checked + .layanan-card {
    border-color: var(--primary);
    background: rgba(79,70,229,.06);
}
.layanan-card:hover { border-color: var(--primary-light); }
</style>

<script>
const fieldTypes = {
    text: (f) => `<input type="text" name="${f.nama_field}" id="${f.nama_field}" class="form-control" ${f.is_required ? 'required' : ''} placeholder="${f.label}">`,
    textarea: (f) => `<textarea name="${f.nama_field}" id="${f.nama_field}" class="form-control" rows="3" ${f.is_required ? 'required' : ''} placeholder="${f.label}"></textarea>`,
    select: (f) => {
        const opts = (f.opsi || []).map(o => `<option value="${o}">${o}</option>`).join('');
        return `<select name="${f.nama_field}" id="${f.nama_field}" class="form-control" ${f.is_required ? 'required' : ''}><option value="">— Pilih —</option>${opts}</select>`;
    },
    date: (f) => `<input type="date" name="${f.nama_field}" id="${f.nama_field}" class="form-control" ${f.is_required ? 'required' : ''}>`,
    number: (f) => `<input type="number" name="${f.nama_field}" id="${f.nama_field}" class="form-control" ${f.is_required ? 'required' : ''}>`,
    email: (f) => `<input type="email" name="${f.nama_field}" id="${f.nama_field}" class="form-control" ${f.is_required ? 'required' : ''}>`,
    file: (f) => `<input type="file" name="${f.nama_field}" id="${f.nama_field}" class="form-control">`,
};

document.querySelectorAll('input[name="layanan_id"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Highlight selected card
        document.querySelectorAll('.layanan-card').forEach(c => c.style.borderColor = 'var(--border)');
        this.nextElementSibling.style.borderColor = 'var(--primary)';

        fetch(`<?= url("pengadu/aduan/getFields") ?>?layanan_id=${this.value}`)
            .then(r => r.json())
            .then(res => {
                const fields = res.fields || (Array.isArray(res) ? res : []);
                const layanan = res.layanan || null;

                const container = document.getElementById('fieldContainer');
                if (!fields.length) {
                    container.innerHTML = '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Layanan ini belum memiliki field formulir.</div>';
                } else {
                    container.innerHTML = fields.map(f => `
                        <div class="form-group">
                            <label class="form-label" for="${f.nama_field}">
                                ${f.label}${f.is_required ? ' <span class="req">*</span>' : ''}
                            </label>
                            ${(fieldTypes[f.tipe_field] || fieldTypes.text)(f)}
                        </div>
                    `).join('');
                }
                document.getElementById('formDinamis').style.display = 'block';

                // Handle template card
                const templateCard = document.getElementById('templateCard');
                const berkasInput  = document.getElementById('berkas_template');
                if (layanan && layanan.file_template) {
                    templateCard.style.display = 'block';
                    document.getElementById('templateFileName').textContent = layanan.nama_file_template || 'Template Berkas Layanan';
                    const cleanPath = layanan.file_template.startsWith('/') ? layanan.file_template.substring(1) : layanan.file_template;
                    document.getElementById('templateDownloadBtn').href = `<?= url('') ?>/` + cleanPath;
                    
                    const labelEl = document.getElementById('berkasTemplateLabel');
                    const badgeEl = document.getElementById('templateWajibBadge');

                    if (layanan.is_template_wajib) {
                        labelEl.innerHTML = 'Upload Berkas Syarat / Template Yang Sudah Diisi <span class="req">*</span>';
                        badgeEl.innerHTML = '<span class="badge badge-warning"><i class="bi bi-exclamation-circle me-1"></i>Wajib Upload</span>';
                        berkasInput.setAttribute('required', 'required');
                    } else {
                        labelEl.innerHTML = 'Upload Berkas Syarat / Template Yang Sudah Diisi <span class="text-muted text-xs">(Opsional)</span>';
                        badgeEl.innerHTML = '<span class="badge badge-muted">Opsional</span>';
                        berkasInput.removeAttribute('required');
                    }
                } else {
                    templateCard.style.display = 'none';
                    berkasInput.removeAttribute('required');
                }

                document.getElementById('lampiranCard').style.display = 'block';
            });
    });
});
</script>

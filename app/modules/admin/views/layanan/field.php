<div class="page-header">
    <div>
        <h1>Field Formulir: <?= htmlspecialchars($layanan['nama']) ?></h1>
        <p>Atur field yang akan tampil di formulir pengaduan layanan ini.</p>
    </div>
    <a href="<?= url('admin/layanan') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="grid-2" style="gap:1.5rem;align-items:start;">
    <!-- Daftar Field -->
    <div class="card">
        <div class="card-header"><h5><i class="bi bi-ui-checks me-2"></i>Field Formulir</h5></div>
        <div class="table-responsive">
            <?php if (empty($fields)): ?>
            <div class="empty-state" style="padding:2rem;">
                <div class="empty-state-icon"><i class="bi bi-ui-checks"></i></div>
                <h5>Belum ada field</h5>
            </div>
            <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Urut</th>
                        <th>Label</th>
                        <th>Tipe</th>
                        <th>Wajib</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fields as $f): ?>
                    <tr>
                        <td class="text-muted"><?= $f['urutan'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($f['label']) ?></strong>
                            <br><code class="text-xs text-muted"><?= htmlspecialchars($f['nama_field']) ?></code>
                        </td>
                        <td><span class="badge badge-info"><?= $f['tipe_field'] ?></span></td>
                        <td>
                            <?php if ($f['is_required']): ?>
                                <span class="badge badge-danger">Wajib</span>
                            <?php else: ?>
                                <span class="badge badge-muted">Opsional</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= url('admin/layanan/hapusField/' . $f['id']) ?>" class="btn btn-sm btn-danger"
                               data-confirm="Hapus field ini?">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Form Tambah Field -->
    <div class="card">
        <div class="card-header"><h5><i class="bi bi-plus-circle me-2"></i>Tambah Field</h5></div>
        <div class="card-body">
            <form method="POST" action="<?= url('admin/layanan/simpanField/' . $layanan['id']) ?>">
                <div class="form-group">
                    <label class="form-label">Label <span class="req">*</span></label>
                    <input type="text" name="label" class="form-control" required placeholder="mis. Kronologi Kejadian">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Field (key) <span class="req">*</span></label>
                    <input type="text" name="nama_field" class="form-control" required placeholder="mis. kronologi"
                           pattern="[a-z0-9_]+" title="Hanya huruf kecil, angka, underscore">
                    <div class="form-hint">Hanya huruf kecil, angka, dan underscore.</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Tipe Field</label>
                    <select name="tipe_field" class="form-control" id="tipeFieldSelect">
                        <option value="text">Text</option>
                        <option value="textarea">Textarea</option>
                        <option value="select">Select (dropdown)</option>
                        <option value="date">Date</option>
                        <option value="number">Number</option>
                        <option value="email">Email</option>
                        <option value="file">File Upload</option>
                    </select>
                </div>
                <div class="form-group" id="opsiGroup" style="display:none;">
                    <label class="form-label">Opsi (satu per baris)</label>
                    <textarea name="opsi" class="form-control" rows="4" placeholder="Pilihan A&#10;Pilihan B&#10;Pilihan C"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="urutan" class="form-control" value="<?= count($fields) + 1 ?>" min="1">
                </div>
                <div class="form-check mb-4">
                    <input type="checkbox" name="is_required" id="is_required" value="1" checked>
                    <label for="is_required">Field Wajib Diisi</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-plus-lg"></i> Tambah Field
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('tipeFieldSelect').addEventListener('change', function() {
    document.getElementById('opsiGroup').style.display = (this.value === 'select') ? 'block' : 'none';
});
</script>

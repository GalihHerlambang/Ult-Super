<div class="page-header">
    <div>
        <h1>Tahapan: <?= htmlspecialchars($layanan['nama']) ?></h1>
        <p>Atur alur workflow untuk layanan ini. Urutan menentukan urutan proses.</p>
    </div>
    <a href="<?= url('admin/layanan') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="grid-2" style="gap:1.5rem;align-items:start;">
    <!-- Daftar Tahapan -->
    <div class="card">
        <div class="card-header"><h5><i class="bi bi-diagram-3 me-2"></i>Daftar Tahapan</h5></div>
        <div class="table-responsive">
            <?php if (empty($tahapan)): ?>
            <div class="empty-state" style="padding:2rem;">
                <div class="empty-state-icon"><i class="bi bi-diagram-3"></i></div>
                <h5>Belum ada tahapan</h5>
            </div>
            <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Tahapan</th>
                        <th>Role</th>
                        <th>Jenis</th>
                        <th>Visibilitas</th>
                        <th>SLA</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tahapan as $t): 
                        $isInternal = !empty($t['is_internal_only']);
                        $isTask = ($t['jenis_tahapan'] ?? 'approval') === 'task_execution';
                    ?>
                    <tr>
                        <td class="text-muted"><?= $t['urutan'] ?></td>
                        <td><strong><?= htmlspecialchars($t['nama_tahapan']) ?></strong>
                            <?php if ($t['is_final']): ?><br><span class="badge badge-success" style="font-size:.65rem;">Final</span><?php endif; ?>
                            <?php if (!empty($t['action_type'])): ?><br><span class="text-xs text-muted">Aksi: <?= htmlspecialchars($t['action_type']) ?></span><?php endif; ?>
                        </td>
                        <td><span class="badge badge-primary"><?= htmlspecialchars($t['role_pelaksana']) ?></span></td>
                        <td>
                            <?php if ($isTask): ?>
                                <span class="badge badge-warning"><i class="bi bi-tools me-1"></i>Task Internal</span>
                            <?php else: ?>
                                <span class="badge badge-info"><i class="bi bi-check-circle me-1"></i>Approval</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($isInternal): ?>
                                <span class="badge badge-muted"><i class="bi bi-eye-slash me-1"></i>Internal Saja</span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><i class="bi bi-eye me-1"></i>Publik (User)</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-sm text-muted"><?= $t['sla_hari'] ?> hari</td>
                        <td>
                            <a href="<?= url('admin/layanan/hapusTahapan/' . $t['id']) ?>" class="btn btn-sm btn-danger"
                               data-confirm="Hapus tahapan ini?">
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

    <!-- Form Tambah Tahapan -->
    <div class="card">
        <div class="card-header"><h5><i class="bi bi-plus-circle me-2"></i>Tambah Tahapan</h5></div>
        <div class="card-body">
            <form method="POST" action="<?= url('admin/layanan/simpanTahapan/' . $layanan['id']) ?>">
                <div class="form-group">
                    <label class="form-label">Urutan <span class="req">*</span></label>
                    <input type="number" name="urutan" class="form-control" value="<?= count($tahapan) + 1 ?>" min="1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Tahapan <span class="req">*</span></label>
                    <input type="text" name="nama_tahapan" class="form-control" required placeholder="mis. Upload SK Dokumen Hasil">
                </div>
                <div class="form-group">
                    <label class="form-label">Role Pelaksana <span class="req">*</span></label>
                    <select name="role_pelaksana" class="form-control" required>
                        <option value="helpdesk">Helpdesk</option>
                        <option value="atasan">Atasan</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Tahapan Workflow</label>
                    <select name="jenis_tahapan" id="jenis_tahapan_select" class="form-control">
                        <option value="approval">Approval (Persetujuan / Penolakan)</option>
                        <option value="task_execution">Task Eksekusi Internal (Tindak Lanjut / Upload Berkas)</option>
                    </select>
                </div>
                <div class="form-group" id="action_type_group" style="display:none;">
                    <label class="form-label">Tipe Aksi Eksekusi</label>
                    <select name="action_type" class="form-control">
                        <option value="upload_file_hasil">Upload File Dokumen Hasil / SK Pengajuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tipe Approval (Jika Persetujuan Multiple)</label>
                    <select name="tipe_approval" class="form-control">
                        <option value="any">ANY — cukup satu setuju</option>
                        <option value="all">ALL — semua harus setuju</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">SLA (hari)</label>
                    <input type="number" name="sla_hari" class="form-control" value="3" min="1">
                </div>
                <div class="form-check mb-2">
                    <input type="checkbox" name="is_internal_only" id="is_internal_only" value="1">
                    <label for="is_internal_only">Internal Saja (Sembunyikan detail tahapan dari Pengadu)</label>
                </div>
                <div class="form-check mb-4">
                    <input type="checkbox" name="is_final" id="is_final" value="1">
                    <label for="is_final">Tahapan Final (aduan selesai setelah ini)</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-plus-lg"></i> Tambah Tahapan
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisSelect = document.getElementById('jenis_tahapan_select');
    const actionGroup = document.getElementById('action_type_group');
    const internalCheck = document.getElementById('is_internal_only');

    if (jenisSelect && actionGroup) {
        jenisSelect.addEventListener('change', function() {
            if (this.value === 'task_execution') {
                actionGroup.style.display = 'block';
                if (internalCheck) internalCheck.checked = true;
            } else {
                actionGroup.style.display = 'none';
            }
        });
    }
});
</script>

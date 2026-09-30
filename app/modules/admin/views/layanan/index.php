<div class="page-header">
    <div>
        <h1>Manajemen Layanan</h1>
        <p>Kelola layanan pengaduan yang tersedia untuk pengguna.</p>
    </div>
    <a href="<?= url('admin/layanan/tambah') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Layanan
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <?php if (empty($layanans)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-grid"></i></div>
            <h5>Belum ada layanan</h5>
            <p>Tambahkan layanan pengaduan pertama Anda.</p>
        </div>
        <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th width="40">#</th>
                    <th>Nama Layanan</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Template Berkas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($layanans as $i => $layanan): ?>
                <tr>
                    <td class="text-muted"><?= $i + 1 ?></td>
                    <td><strong><?= htmlspecialchars($layanan['nama']) ?></strong></td>
                    <td class="text-muted text-sm"><?= htmlspecialchars($layanan['deskripsi'] ?? '-') ?></td>
                    <td>
                        <?php if ($layanan['is_active']): ?>
                            <span class="badge badge-success"><i class="bi bi-check"></i> Aktif</span>
                        <?php else: ?>
                            <span class="badge badge-muted">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($layanan['file_template'])): ?>
                            <a href="<?= url($layanan['file_template']) ?>" target="_blank" class="btn btn-xs btn-outline-primary" style="padding:.2rem .5rem;font-size:.75rem;">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> <?= htmlspecialchars($layanan['nama_file_template'] ?? 'File Template') ?>
                            </a>
                            <div class="mt-1">
                                <?php if (!empty($layanan['is_template_wajib'])): ?>
                                    <span class="badge badge-warning" style="font-size:.7rem;"><i class="bi bi-exclamation-circle me-1"></i>Wajib Upload</span>
                                <?php else: ?>
                                    <span class="badge badge-muted" style="font-size:.7rem;">Opsional Upload</span>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-muted text-xs">Tidak Ada</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="<?= url('admin/layanan/tahapan/' . $layanan['id']) ?>" class="btn btn-sm btn-secondary" title="Atur Tahapan">
                                <i class="bi bi-diagram-3"></i> Tahapan
                            </a>
                            <a href="<?= url('admin/layanan/field/' . $layanan['id']) ?>" class="btn btn-sm btn-secondary" title="Atur Field Formulir">
                                <i class="bi bi-ui-checks"></i> Field
                            </a>
                            <a href="<?= url('admin/layanan/edit/' . $layanan['id']) ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= url('admin/layanan/hapus/' . $layanan['id']) ?>" class="btn btn-sm btn-danger"
                               data-confirm="Yakin ingin menghapus layanan '<?= htmlspecialchars($layanan['nama']) ?>'?">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

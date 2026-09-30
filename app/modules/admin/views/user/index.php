<div class="page-header">
    <div>
        <h1>Manajemen Pengguna</h1>
        <p>Kelola akun pengguna dan hak akses role.</p>
    </div>
    <a href="<?= url('admin/user/tambah') ?>" class="btn btn-primary">
        <i class="bi bi-person-plus"></i> Tambah Pengguna
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <?php if (empty($users)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-people"></i></div>
            <h5>Belum ada pengguna</h5>
        </div>
        <?php else: ?>
        <?php
        $roleBadges = ['admin' => 'danger', 'helpdesk' => 'info', 'atasan' => 'warning', 'pengadu' => 'primary'];
        $roleLabels = ['admin' => 'Administrator', 'helpdesk' => 'Helpdesk', 'atasan' => 'Atasan', 'pengadu' => 'Pengadu'];
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $i => $user): ?>
                <tr>
                    <td class="text-muted"><?= $i + 1 ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0;">
                                <?= strtoupper(substr($user['nama'],0,1)) ?>
                            </div>
                            <strong><?= htmlspecialchars($user['nama']) ?></strong>
                        </div>
                    </td>
                    <td><code class="text-sm"><?= htmlspecialchars($user['username']) ?></code></td>
                    <td class="text-muted text-sm"><?= htmlspecialchars($user['email'] ?? '-') ?></td>
                    <td><span class="badge badge-<?= $roleBadges[$user['role']] ?? 'muted' ?>">
                        <?= $roleLabels[$user['role']] ?? $user['role'] ?>
                    </span></td>
                    <td>
                        <?php if ($user['is_active']): ?>
                            <span class="badge badge-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge badge-muted">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="<?= url('admin/user/edit/' . $user['id']) ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                            <a href="<?= url('admin/user/hapus/' . $user['id']) ?>" class="btn btn-sm btn-danger"
                               data-confirm="Hapus pengguna '<?= htmlspecialchars($user['nama']) ?>'?">
                                <i class="bi bi-trash"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

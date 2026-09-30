<?php
$statusLabels = ['berjalan' => ['Berjalan', 'warning'], 'selesai' => ['Selesai', 'success'], 'ditolak' => ['Ditolak', 'danger'], 'dibatalkan' => ['Dibatalkan', 'muted']];
?>
<div class="page-header">
    <div>
        <h1>Dashboard Admin</h1>
        <p>Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?>! Berikut ringkasan sistem.</p>
    </div>
</div>

<!-- Statistik -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon primary"><i class="bi bi-file-earmark-text"></i></div>
        <div>
            <div class="stat-number"><?= $stats['total_aduan'] ?></div>
            <div class="stat-label">Total Aduan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning"><i class="bi bi-hourglass-split"></i></div>
        <div>
            <div class="stat-number"><?= $stats['berjalan'] ?></div>
            <div class="stat-label">Sedang Berjalan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-number"><?= $stats['selesai'] ?></div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger"><i class="bi bi-x-circle"></i></div>
        <div>
            <div class="stat-number"><?= $stats['ditolak'] ?></div>
            <div class="stat-label">Ditolak</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info"><i class="bi bi-people"></i></div>
        <div>
            <div class="stat-number"><?= $stats['total_user'] ?></div>
            <div class="stat-label">Pengguna</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon primary"><i class="bi bi-grid"></i></div>
        <div>
            <div class="stat-number"><?= $stats['total_layanan'] ?></div>
            <div class="stat-label">Layanan Aktif</div>
        </div>
    </div>
</div>

<!-- Aduan Terbaru -->
<div class="card">
    <div class="card-header">
        <h5><i class="bi bi-clock-history me-2"></i>Aduan Terbaru</h5>
        <a href="<?= url('admin/layanan') ?>" class="btn btn-sm btn-outline-primary">Kelola Layanan</a>
    </div>
    <div class="table-responsive">
        <?php if (empty($aduanTerbaru)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
            <h5>Belum ada aduan</h5>
            <p>Aduan yang masuk akan muncul di sini.</p>
        </div>
        <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pengadu</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($aduanTerbaru as $aduan):
                    [$statusLabel, $statusClass] = $statusLabels[$aduan['status']] ?? [$aduan['status'], 'muted'];
                    $namaUser    = $userMap[$aduan['user_id']]['nama'] ?? '-';
                    $namaLayanan = $layananMap[$aduan['layanan_id']]['nama'] ?? '-';
                ?>
                <tr>
                    <td><span class="fw-600 text-sm"><?= htmlspecialchars($aduan['kode_aduan']) ?></span></td>
                    <td><?= htmlspecialchars($namaUser) ?></td>
                    <td><?= htmlspecialchars($namaLayanan) ?></td>
                    <td><span class="badge badge-<?= $statusClass ?>"><?= $statusLabel ?></span></td>
                    <td class="text-muted text-sm"><?= date('d M Y', strtotime($aduan['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

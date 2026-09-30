<?php
$statusLabels = ['berjalan' => ['Berjalan', 'warning'], 'selesai' => ['Selesai', 'success'], 'ditolak' => ['Ditolak', 'danger']];
?>
<div class="page-header">
    <div><h1>Dashboard Atasan</h1><p>Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?>!</p></div>
    <a href="<?= url('atasan/persetujuan') ?>" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Daftar Persetujuan</a>
</div>

<div class="stat-grid" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr));">
    <div class="stat-card">
        <div class="stat-icon warning"><i class="bi bi-hourglass-split"></i></div>
        <div><div class="stat-number"><?= $stats['menunggu'] ?></div><div class="stat-label">Menunggu Persetujuan</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success"><i class="bi bi-check-circle"></i></div>
        <div><div class="stat-number"><?= $stats['selesai'] ?></div><div class="stat-label">Total Selesai</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger"><i class="bi bi-x-circle"></i></div>
        <div><div class="stat-number"><?= $stats['ditolak'] ?></div><div class="stat-label">Ditolak</div></div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h5><i class="bi bi-clock-history me-2"></i>Aduan Menunggu Persetujuan Anda</h5></div>
    <div class="table-responsive">
        <?php if (empty($aduanSaya)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-check2-all"></i></div>
            <h5>Tidak ada aduan yang menunggu persetujuan</h5>
        </div>
        <?php else: ?>
        <table class="table">
            <thead><tr><th>Kode</th><th>Layanan</th><th>Pengadu</th><th>Tanggal</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php foreach ($aduanSaya as $aduan): ?>
                <tr>
                    <td class="fw-600"><?= htmlspecialchars($aduan['kode_aduan']) ?></td>
                    <td><?= htmlspecialchars($layananMap[$aduan['layanan_id']]['nama'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($userMap[$aduan['user_id']]['nama'] ?? '-') ?></td>
                    <td class="text-muted text-sm"><?= date('d M Y H:i', strtotime($aduan['created_at'])) ?></td>
                    <td><a href="<?= url('atasan/persetujuan/detail/' . $aduan['id']) ?>" class="btn btn-sm btn-primary"><i class="bi bi-arrow-right-circle"></i> Review</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php
$statusLabels = ['berjalan' => ['Berjalan', 'warning'], 'selesai' => ['Selesai', 'success'], 'ditolak' => ['Ditolak', 'danger'], 'dibatalkan' => ['Dibatalkan', 'muted']];
?>
<div class="page-header">
    <div>
        <h1>Dashboard Saya</h1>
        <p>Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?>! Berikut ringkasan aduan Anda.</p>
    </div>
    <a href="<?= url('pengadu/aduan/buat') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Buat Aduan Baru
    </a>
</div>

<div class="stat-grid" style="grid-template-columns:repeat(auto-fit,minmax(180px,1fr));">
    <div class="stat-card">
        <div class="stat-icon primary"><i class="bi bi-file-earmark-text"></i></div>
        <div><div class="stat-number"><?= $stats['total'] ?></div><div class="stat-label">Total Aduan</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning"><i class="bi bi-hourglass-split"></i></div>
        <div><div class="stat-number"><?= $stats['berjalan'] ?></div><div class="stat-label">Sedang Diproses</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success"><i class="bi bi-check-circle"></i></div>
        <div><div class="stat-number"><?= $stats['selesai'] ?></div><div class="stat-label">Selesai</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger"><i class="bi bi-x-circle"></i></div>
        <div><div class="stat-number"><?= $stats['ditolak'] ?></div><div class="stat-label">Ditolak</div></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5><i class="bi bi-clock-history me-2"></i>Aduan Terbaru</h5>
        <a href="<?= url('pengadu/aduan') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <?php if (empty($aduanTerbaru)): ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
        <h5>Belum ada aduan</h5>
        <p>Klik tombol "Buat Aduan Baru" untuk memulai.</p>
        <a href="<?= url('pengadu/aduan/buat') ?>" class="btn btn-primary mt-3"><i class="bi bi-plus-lg"></i> Buat Aduan</a>
    </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>Kode</th><th>Layanan</th><th>Status</th><th>Tanggal</th><th></th></tr>
            </thead>
            <tbody>
                <?php foreach ($aduanTerbaru as $aduan):
                    [$label, $cls] = $statusLabels[$aduan['status']] ?? [$aduan['status'], 'muted'];
                ?>
                <tr>
                    <td class="fw-600"><?= htmlspecialchars($aduan['kode_aduan']) ?></td>
                    <td><?= htmlspecialchars($layananMap[$aduan['layanan_id']]['nama'] ?? '-') ?></td>
                    <td><span class="badge badge-<?= $cls ?>"><?= $label ?></span></td>
                    <td class="text-muted text-sm"><?= date('d M Y', strtotime($aduan['created_at'])) ?></td>
                    <td><a href="<?= url('pengadu/aduan/detail/' . $aduan['id']) ?>" class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

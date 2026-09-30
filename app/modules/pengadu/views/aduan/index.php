<?php
$statusLabels = ['berjalan' => ['Berjalan', 'warning'], 'selesai' => ['Selesai', 'success'], 'ditolak' => ['Ditolak', 'danger'], 'dibatalkan' => ['Dibatalkan', 'muted']];
?>
<div class="page-header">
    <div><h1>Aduan Saya</h1><p>Daftar seluruh aduan yang pernah Anda ajukan.</p></div>
    <a href="<?= url('pengadu/aduan/buat') ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Aduan Baru</a>
</div>

<div class="card">
    <div class="table-responsive">
        <?php if (empty($aduanSaya)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
            <h5>Belum ada aduan</h5>
            <p>Anda belum pernah mengajukan aduan.</p>
            <a href="<?= url('pengadu/aduan/buat') ?>" class="btn btn-primary mt-3"><i class="bi bi-plus-lg"></i> Buat Aduan Pertama</a>
        </div>
        <?php else: ?>
        <table class="table">
            <thead>
                <tr><th>Kode</th><th>Layanan</th><th>Tahapan Saat Ini</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($aduanSaya as $aduan):
                    [$label, $cls] = $statusLabels[$aduan['status']] ?? [$aduan['status'], 'muted'];
                    $namaTahapan   = $tahapanMap[$aduan['tahapan_saat_ini_id']]['nama_tahapan'] ?? '-';
                ?>
                <tr>
                    <td class="fw-600"><?= htmlspecialchars($aduan['kode_aduan']) ?></td>
                    <td><?= htmlspecialchars($layananMap[$aduan['layanan_id']]['nama'] ?? '-') ?></td>
                    <td class="text-sm"><?= $aduan['status'] === 'selesai' ? '<span class="badge badge-success">Selesai</span>' : htmlspecialchars($namaTahapan) ?></td>
                    <td><span class="badge badge-<?= $cls ?>"><?= $label ?></span></td>
                    <td class="text-muted text-sm"><?= date('d M Y', strtotime($aduan['created_at'])) ?></td>
                    <td><a href="<?= url('pengadu/aduan/detail/' . $aduan['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Detail</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

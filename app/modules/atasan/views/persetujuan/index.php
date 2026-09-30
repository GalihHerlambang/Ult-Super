<div class="page-header">
    <div><h1>Daftar Persetujuan</h1><p>Aduan yang menunggu keputusan Anda.</p></div>
</div>

<div class="card">
    <div class="table-responsive">
        <?php if (empty($aduanList)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-check2-all"></i></div>
            <h5>Tidak ada aduan yang menunggu persetujuan</h5>
        </div>
        <?php else: ?>
        <table class="table">
            <thead><tr><th>Kode</th><th>Layanan</th><th>Pengadu</th><th>Tahapan</th><th>Tanggal</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php foreach ($aduanList as $aduan): ?>
                <tr>
                    <td class="fw-600"><?= htmlspecialchars($aduan['kode_aduan']) ?></td>
                    <td><?= htmlspecialchars($layananMap[$aduan['layanan_id']]['nama'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($userMap[$aduan['user_id']]['nama'] ?? '-') ?></td>
                    <td><span class="badge badge-info"><?= htmlspecialchars($tahapanMap[$aduan['tahapan_saat_ini_id']]['nama_tahapan'] ?? '-') ?></span></td>
                    <td class="text-muted text-sm"><?= date('d M Y H:i', strtotime($aduan['created_at'])) ?></td>
                    <td><a href="<?= url('atasan/persetujuan/detail/' . $aduan['id']) ?>" class="btn btn-sm btn-primary"><i class="bi bi-arrow-right-circle"></i> Review</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

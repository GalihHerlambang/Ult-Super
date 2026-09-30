<?php
$statusLabels = ['berjalan' => ['Berjalan', 'warning'], 'selesai' => ['Selesai', 'success'], 'ditolak' => ['Ditolak', 'danger'], 'dibatalkan' => ['Dibatalkan', 'muted']];
$aksiLabels = ['diajukan' => 'Aduan Diajukan', 'diteruskan' => 'Diteruskan', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak', 'dikembalikan' => 'Dikembalikan'];
[$statusLabel, $statusClass] = $statusLabels[$aduan['status']] ?? [$aduan['status'], 'muted'];
$canProcess = $myApproval !== null;
$modulePrefix = 'atasan/persetujuan';
?>
<div class="page-header">
    <div>
        <h1><?= htmlspecialchars($aduan['kode_aduan']) ?></h1>
        <p><?= htmlspecialchars($layanan['nama'] ?? '-') ?></p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <span class="badge badge-<?= $statusClass ?>" style="font-size:.85rem;padding:.4rem .9rem;"><?= $statusLabel ?></span>
        <a href="<?= url('atasan/persetujuan') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<!-- Step Progress -->
<div class="card mb-4">
    <div class="card-header"><h5><i class="bi bi-diagram-3 me-2"></i>Progres Tahapan</h5></div>
    <div class="card-body">
        <div class="step-progress">
            <?php foreach ($tahapan as $t):
                $isDone = false;
                $isActive = $t['id'] == $aduan['tahapan_saat_ini_id'];
                foreach ($riwayat as $rw) {
                    if ($rw['tahapan_asal_id'] == $t['id'] && $rw['arah'] === 'maju') { $isDone = true; break; }
                }
                if ($aduan['status'] === 'selesai') $isDone = true;
            ?>
            <div class="step-item <?= $isDone ? 'done' : ($isActive ? 'active' : '') ?>">
                <div class="step-circle"><?= $isDone ? '<i class="bi bi-check"></i>' : $t['urutan'] ?></div>
                <div class="step-label"><?= htmlspecialchars($t['nama_tahapan']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="grid-2" style="gap:1.5rem;align-items:start;">
    <div>
        <?php if ($canProcess && $aduan['status'] === 'berjalan'): ?>
        <div class="card mb-4" style="border:2px solid var(--primary);">
            <div class="card-header" style="background:rgba(79,70,229,.06);">
                <h5><i class="bi bi-check2-square me-2"></i>Keputusan Anda</h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-success" data-modal="modalSetujui"><i class="bi bi-check-lg"></i> Setujui</button>
                    <button class="btn btn-danger" data-modal="modalTolak"><i class="bi bi-x-lg"></i> Tolak</button>
                    <?php if (!empty($tahapanSebelumnya)): ?>
                    <button class="btn btn-warning" data-modal="modalKembalikan"><i class="bi bi-arrow-return-left"></i> Kembalikan</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php elseif ($aduan['status'] === 'berjalan'): ?>
        <div class="alert alert-info mb-4"><i class="bi bi-info-circle"></i> Anda sudah memproses aduan ini.</div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header"><h5><i class="bi bi-file-earmark-text me-2"></i>Detail Aduan</h5></div>
            <div class="card-body">
                <table style="width:100%;font-size:.875rem;">
                    <tr><td style="color:var(--text-muted);padding:.4rem 0;width:40%;">Pengadu</td><td class="fw-600"><?= htmlspecialchars($userMap[$aduan['user_id']]['nama'] ?? '-') ?></td></tr>
                    <tr><td style="color:var(--text-muted);padding:.4rem 0;">Tahapan</td><td><?= htmlspecialchars($tahapanSkrg['nama_tahapan'] ?? '-') ?> (<span class="badge badge-info"><?= strtoupper($tahapanSkrg['tipe_approval'] ?? '') ?></span>)</td></tr>
                    <tr><td style="color:var(--text-muted);padding:.4rem 0;">Tanggal</td><td><?= date('d M Y H:i', strtotime($aduan['created_at'])) ?></td></tr>
                </table>
                <?php if (!empty($aduan['data_formulir'])): ?>
                <hr style="border:none;border-top:1px solid var(--border);margin:1rem 0;">
                <?php foreach ($aduan['data_formulir'] as $key => $val): ?>
                <div class="mb-3">
                    <div style="font-size:.78rem;color:var(--text-muted);"><?= htmlspecialchars($fieldMap[$key]['label'] ?? $key) ?></div>
                    <div class="fw-600"><?= nl2br(htmlspecialchars($val)) ?></div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($approvals)): ?>
        <div class="card mb-4">
            <div class="card-header"><h5><i class="bi bi-people me-2"></i>Status Approval Paralel</h5></div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Petugas</th><th>Status</th><th>Waktu</th><th>Catatan</th></tr></thead>
                    <tbody>
                        <?php
                        $apStatusLabel = ['menunggu' => ['Menunggu', 'warning'], 'setuju' => ['Setuju', 'success'], 'tolak' => ['Tolak', 'danger'], 'dilewati' => ['Dilewati', 'muted'], 'dibatalkan' => ['Dibatalkan', 'muted']];
                        foreach ($approvals as $ap):
                            if ($ap['status'] === 'dibatalkan') continue;
                            [$apLabel, $apCls] = $apStatusLabel[$ap['status']] ?? [$ap['status'], 'muted'];
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($userMap[$ap['approver_user_id']]['nama'] ?? '-') ?></td>
                            <td><span class="badge badge-<?= $apCls ?>"><?= $apLabel ?></span></td>
                            <td class="text-muted text-sm"><?= $ap['diproses_at'] ? date('d M Y H:i', strtotime($ap['diproses_at'])) : '-' ?></td>
                            <td class="text-sm"><?= htmlspecialchars($ap['catatan'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($lampiran)): ?>
        <div class="card">
            <div class="card-header"><h5><i class="bi bi-paperclip me-2"></i>Lampiran</h5></div>
            <div class="card-body">
                <?php foreach ($lampiran as $lp): ?>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-file-earmark" style="color:var(--primary);font-size:1.2rem;"></i>
                    <a href="<?= htmlspecialchars(url($lp['path_file'])) ?>" target="_blank" class="text-sm"><?= htmlspecialchars($lp['nama_file']) ?></a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Riwayat -->
    <div class="card">
        <div class="card-header"><h5><i class="bi bi-clock-history me-2"></i>Riwayat Proses</h5></div>
        <div class="card-body">
            <?php if (empty($riwayat)): ?>
            <div class="empty-state" style="padding:1.5rem;"><p>Belum ada riwayat.</p></div>
            <?php else: ?>
            <div class="timeline">
                <?php foreach (array_reverse($riwayat) as $rw):
                    $itemClass = $rw['arah'] === 'mundur' ? 'mundur' : ($rw['aksi'] === 'selesai' ? 'selesai' : ($rw['aksi'] === 'ditolak' ? 'ditolak' : ''));
                    $asal   = $tahapanMap[$rw['tahapan_asal_id']]['nama_tahapan'] ?? 'Pengadu';
                    $tujuan = $tahapanMap[$rw['tahapan_tujuan_id']]['nama_tahapan'] ?? 'Selesai';
                    $oleh   = $userMap[$rw['diproses_oleh']]['nama'] ?? 'Sistem';
                ?>
                <div class="timeline-item <?= $itemClass ?>">
                    <div class="timeline-date"><?= date('d M Y H:i', strtotime($rw['created_at'])) ?> — <?= htmlspecialchars($oleh) ?></div>
                    <div class="timeline-body">
                        <strong><?= $aksiLabels[$rw['aksi']] ?? ucfirst($rw['aksi']) ?></strong>
                        <?php if ($rw['tahapan_asal_id']): ?>
                        <div class="text-xs text-muted mt-1"><?= htmlspecialchars($asal) ?> → <?= htmlspecialchars($tujuan) ?></div>
                        <?php endif; ?>
                        <?php if ($rw['catatan']): ?><div class="text-sm mt-1" style="border-left:2px solid var(--border);padding-left:.75rem;margin-top:.5rem;"><?= nl2br(htmlspecialchars($rw['catatan'])) ?></div><?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Setujui -->
<div class="modal-overlay" id="modalSetujui">
    <div class="modal">
        <div class="modal-header"><h5><i class="bi bi-check-circle text-success me-2"></i>Setujui Aduan</h5><button class="modal-close">&times;</button></div>
        <form method="POST" action="<?= url('atasan/persetujuan/setujui/' . $aduan['id']) ?>">
            <div class="modal-body">
                <div class="form-group"><label class="form-label">Catatan (opsional)</label><textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan..."></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal-close-btn">Batal</button>
                <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Setujui</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal-overlay" id="modalTolak">
    <div class="modal">
        <div class="modal-header"><h5><i class="bi bi-x-circle text-danger me-2"></i>Tolak Aduan</h5><button class="modal-close">&times;</button></div>
        <form method="POST" action="<?= url('atasan/persetujuan/tolak/' . $aduan['id']) ?>">
            <div class="modal-body">
                <div class="form-group"><label class="form-label">Alasan <span class="req">*</span></label><textarea name="catatan" class="form-control" rows="3" required placeholder="Tuliskan alasan..."></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal-close-btn">Batal</button>
                <button type="submit" class="btn btn-danger"><i class="bi bi-x-lg"></i> Tolak</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Kembalikan -->
<?php if (!empty($tahapanSebelumnya)): ?>
<div class="modal-overlay" id="modalKembalikan">
    <div class="modal">
        <div class="modal-header"><h5><i class="bi bi-arrow-return-left text-warning me-2"></i>Kembalikan</h5><button class="modal-close">&times;</button></div>
        <form method="POST" action="<?= url('atasan/persetujuan/kembalikan/' . $aduan['id']) ?>">
            <div class="modal-body">
                <div class="form-group"><label class="form-label">Ke Tahapan <span class="req">*</span></label>
                    <select name="tahapan_tujuan_id" class="form-control" required>
                        <?php foreach ($tahapanSebelumnya as $t): ?>
                        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nama_tahapan']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Alasan <span class="req">*</span></label><textarea name="catatan" class="form-control" rows="3" required></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal-close-btn">Batal</button>
                <button type="submit" class="btn btn-warning"><i class="bi bi-arrow-return-left"></i> Kembalikan</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
document.querySelectorAll('.modal-close-btn').forEach(btn => {
    btn.addEventListener('click', () => btn.closest('.modal-overlay').classList.remove('active'));
});
</script>

<?php
$statusLabels = ['berjalan' => ['Berjalan', 'warning'], 'selesai' => ['Selesai', 'success'], 'ditolak' => ['Ditolak', 'danger'], 'dibatalkan' => ['Dibatalkan', 'muted']];
$aksiLabels = ['diajukan' => 'Aduan Diajukan', 'diteruskan' => 'Diteruskan', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak', 'dikembalikan' => 'Dikembalikan'];
[$statusLabel, $statusClass] = $statusLabels[$aduan['status']] ?? [$aduan['status'], 'muted'];
$canProcess = $myApproval !== null;
$tahapanSebelumnya = [];
foreach ($tahapan as $t) {
    if ($tahapanSkrg && $t['urutan'] < $tahapanSkrg['urutan']) {
        $tahapanSebelumnya[] = $t;
    }
}
?>
<div class="page-header">
    <div>
        <h1><?= htmlspecialchars($aduan['kode_aduan']) ?></h1>
        <p><?= htmlspecialchars($layanan['nama'] ?? '-') ?></p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <span class="badge badge-<?= $statusClass ?>" style="font-size:.85rem;padding:.4rem .9rem;"><?= $statusLabel ?></span>
        <a href="<?= url('helpdesk/aduan') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="grid-2" style="gap:1.5rem;align-items:start;">

    <!-- Kiri: Detail + Aksi -->
    <div>
        <!-- Aksi -->
        <?php if ($canProcess && $aduan['status'] === 'berjalan'): 
            $isTaskExecution = ($tahapanSkrg['jenis_tahapan'] ?? '') === 'task_execution';
        ?>
        <?php if ($isTaskExecution): ?>
        <div class="card mb-4" style="border:2px solid var(--warning);">
            <div class="card-header" style="background:rgba(245,158,11,.08);">
                <h5><i class="bi bi-cloud-upload me-2 text-warning"></i>Aksi Eksekusi Internal: Upload Berkas Hasil / SK</h5>
            </div>
            <div class="card-body">
                <p class="text-sm text-muted mb-3">Tahapan ini adalah alur eksekusi internal Helpdesk. Silakan unggah berkas hasil/SK keputusan untuk pengajuan ini.</p>
                <form method="POST" action="<?= url('helpdesk/aduan/uploadHasil/' . $aduan['id']) ?>" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label class="form-label">Unggah Dokumen SK / File Hasil <span class="req">*</span></label>
                        <input type="file" name="file_hasil" class="form-control" accept=".pdf,.doc,.docx,.zip,.png,.jpg,.jpeg" required>
                        <small class="text-muted">Format: PDF, DOC, DOCX, ZIP, PNG, JPG</small>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Catatan Eksekusi (Opsional)</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan mengenai berkas yang diunggah..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-check-circle me-1"></i> Upload Berkas Hasil & Selesaikan Tahap Ini
                    </button>
                </form>
            </div>
        </div>
        <?php else: ?>
        <div class="card mb-4" style="border:2px solid var(--primary);">
            <div class="card-header" style="background:rgba(79,70,229,.06);">
                <h5><i class="bi bi-check2-square me-2"></i>Tindakan Anda</h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2 flex-wrap">
                    <!-- Modal Setujui -->
                    <button class="btn btn-success" data-modal="modalSetujui">
                        <i class="bi bi-check-lg"></i> Setujui
                    </button>
                    <!-- Modal Tolak -->
                    <button class="btn btn-danger" data-modal="modalTolak">
                        <i class="bi bi-x-lg"></i> Tolak
                    </button>
                    <!-- Modal Kembalikan -->
                    <?php if (!empty($tahapanSebelumnya)): ?>
                    <button class="btn btn-warning" data-modal="modalKembalikan">
                        <i class="bi bi-arrow-return-left"></i> Kembalikan
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php elseif ($aduan['status'] === 'berjalan'): ?>
        <div class="alert alert-info mb-4"><i class="bi bi-info-circle"></i> Anda sudah memproses aduan ini atau tidak memiliki hak proses di tahap ini.</div>
        <?php endif; ?>

        <!-- Data Aduan -->
        <div class="card mb-4">
            <div class="card-header"><h5><i class="bi bi-file-earmark-text me-2"></i>Detail Aduan</h5></div>
            <div class="card-body">
                <table style="width:100%;font-size:.875rem;">
                    <tr><td style="color:var(--text-muted);padding:.4rem 0;width:40%;">Pengadu</td><td class="fw-600"><?= htmlspecialchars($userMap[$aduan['user_id']]['nama'] ?? '-') ?></td></tr>
                    <tr><td style="color:var(--text-muted);padding:.4rem 0;">Tahapan Saat Ini</td><td><?= htmlspecialchars($tahapanSkrg['nama_tahapan'] ?? '-') ?> (<span class="badge badge-info"><?= strtoupper($tahapanSkrg['tipe_approval'] ?? '') ?></span>)</td></tr>
                    <tr><td style="color:var(--text-muted);padding:.4rem 0;">Tanggal Aduan</td><td><?= date('d M Y H:i', strtotime($aduan['created_at'])) ?></td></tr>
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

        <!-- Status Approval Paralel -->
        <?php if (!empty($approvals)): ?>
        <div class="card mb-4">
            <div class="card-header"><h5><i class="bi bi-people me-2"></i>Status Approval Tahap Ini</h5></div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Petugas</th><th>Status</th><th>Waktu Proses</th><th>Catatan</th></tr></thead>
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

        <!-- Lampiran -->
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

    <!-- Kanan: Riwayat -->
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
        <div class="modal-header">
            <h5><i class="bi bi-check-circle text-success me-2"></i>Setujui Aduan</h5>
            <button class="modal-close">&times;</button>
        </div>
        <form method="POST" action="<?= url('helpdesk/aduan/setujui/' . $aduan['id']) ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan..."></textarea>
                </div>
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
        <div class="modal-header">
            <h5><i class="bi bi-x-circle text-danger me-2"></i>Tolak Aduan</h5>
            <button class="modal-close">&times;</button>
        </div>
        <form method="POST" action="<?= url('helpdesk/aduan/tolak/' . $aduan['id']) ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Alasan Penolakan <span class="req">*</span></label>
                    <textarea name="catatan" class="form-control" rows="3" required placeholder="Tuliskan alasan penolakan..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal-close-btn">Batal</button>
                <button type="submit" class="btn btn-danger"><i class="bi bi-x-lg"></i> Tolak Aduan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Kembalikan -->
<?php if (!empty($tahapanSebelumnya)): ?>
<div class="modal-overlay" id="modalKembalikan">
    <div class="modal">
        <div class="modal-header">
            <h5><i class="bi bi-arrow-return-left text-warning me-2"></i>Kembalikan ke Tahap Sebelumnya</h5>
            <button class="modal-close">&times;</button>
        </div>
        <form method="POST" action="<?= url('helpdesk/aduan/kembalikan/' . $aduan['id']) ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Kembalikan ke Tahapan <span class="req">*</span></label>
                    <select name="tahapan_tujuan_id" class="form-control" required>
                        <?php foreach ($tahapanSebelumnya as $t): ?>
                        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nama_tahapan']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Alasan Revisi <span class="req">*</span></label>
                    <textarea name="catatan" class="form-control" rows="3" required placeholder="Jelaskan kenapa aduan dikembalikan..."></textarea>
                </div>
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

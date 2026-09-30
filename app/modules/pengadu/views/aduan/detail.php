<?php
$statusLabels = ['berjalan' => ['Berjalan', 'warning'], 'selesai' => ['Selesai', 'success'], 'ditolak' => ['Ditolak', 'danger'], 'dibatalkan' => ['Dibatalkan', 'muted']];
$aksiLabels = ['diajukan' => 'Aduan Diajukan', 'diteruskan' => 'Diteruskan ke Tahap Berikutnya', 'selesai' => 'Aduan Selesai', 'ditolak' => 'Aduan Ditolak', 'dikembalikan' => 'Dikembalikan ke Tahap Sebelumnya'];
[$statusLabel, $statusClass] = $statusLabels[$aduan['status']] ?? [$aduan['status'], 'muted'];

// Separate lampiran user vs file hasil SK
$lampiranUser = [];
$fileHasil = [];
foreach ($lampiran as $lp) {
    if (($lp['jenis_lampiran'] ?? '') === 'file_hasil' || str_contains($lp['nama_file'], '[SK / Hasil]')) {
        $fileHasil[] = $lp;
    } else {
        $lampiranUser[] = $lp;
    }
}

// Filter tahapan publik saja untuk timeline progress bar
$tahapanPublic = array_values(array_filter($tahapan, fn($t) => empty($t['is_internal_only'])));
$isInternalStepNow = !empty($tahapanSkrg['is_internal_only']) && $aduan['status'] === 'berjalan';
?>
<div class="page-header">
    <div>
        <h1><?= htmlspecialchars($aduan['kode_aduan']) ?></h1>
        <p>Detail pengaduan Anda — <?= htmlspecialchars($layanan['nama'] ?? '-') ?></p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <span class="badge badge-<?= $statusClass ?>" style="font-size:.85rem;padding:.4rem .9rem;">
            <?= $statusLabel ?>
        </span>
        <a href="<?= url('pengadu/aduan') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<!-- Banner Info jika sedang di Tahap Internal (Post-Approval Execution) -->
<?php if ($isInternalStepNow): ?>
<div class="alert alert-info mb-4" style="border-left: 4px solid var(--primary); background: rgba(79,70,229,.08);">
    <div class="d-flex align-items-start gap-3">
        <i class="bi bi-file-earmark-check text-primary" style="font-size: 1.8rem; line-height: 1;"></i>
        <div>
            <h5 style="margin-bottom:.3rem; font-size: 1rem; color: var(--primary);">Permohonan Telah Disetujui — Dalam Proses Penyiapan Dokumen Hasil</h5>
            <p class="mb-0 text-sm">Pengajuan Anda telah disetujui oleh Pimpinan. Petugas Helpdesk saat ini sedang memproses penerbitan dokumen SK/Hasil akhir untuk Anda.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Step Progress Workflow Publik -->
<div class="card mb-4">
    <div class="card-header"><h5><i class="bi bi-diagram-3 me-2"></i>Progres Tahapan</h5></div>
    <div class="card-body">
        <div class="step-progress">
            <?php foreach ($tahapanPublic as $t):
                $isDone   = false;
                $isActive = ($t['id'] == $aduan['tahapan_saat_ini_id']) || ($isInternalStepNow && $t['is_final']);
                foreach ($riwayat as $rw) {
                    if ($rw['tahapan_asal_id'] == $t['id'] && $rw['arah'] === 'maju') {
                        $isDone = true; break;
                    }
                }
                if ($aduan['status'] === 'selesai') $isDone = true;
            ?>
            <div class="step-item <?= $isDone ? 'done' : ($isActive ? 'active' : '') ?>">
                <div class="step-circle">
                    <?= $isDone ? '<i class="bi bi-check"></i>' : $t['urutan'] ?>
                </div>
                <div class="step-label"><?= htmlspecialchars($t['nama_tahapan']) ?></div>
            </div>
            <?php endforeach; ?>
            <?php if ($aduan['status'] === 'selesai'): ?>
            <div class="step-item done">
                <div class="step-circle"><i class="bi bi-check-all"></i></div>
                <div class="step-label">Selesai</div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="grid-2" style="gap:1.5rem;align-items:start;">

    <!-- Data Aduan & File Hasil -->
    <div>
        <!-- DOKUMEN HASIL / SK PENGAJUAN (Jika sudah diterbitkan Helpdesk) -->
        <?php if (!empty($fileHasil)): ?>
        <div class="card mb-4" style="border: 2px solid var(--success); background: rgba(16,185,129,.03);">
            <div class="card-header" style="background: rgba(16,185,129,.1);">
                <h5 class="text-success"><i class="bi bi-file-earmark-arrow-down me-2"></i>Dokumen Hasil / SK Pengajuan</h5>
            </div>
            <div class="card-body">
                <p class="text-sm text-muted mb-3">Dokumen resmi hasil keputusan pengajuan Anda telah siap dan diterbitkan:</p>
                <?php foreach ($fileHasil as $fh): ?>
                <div class="d-flex align-items-center justify-content-between p-3" style="background: var(--bg-body); border-radius: .5rem; border: 1px solid var(--border);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-pdf text-success" style="font-size: 1.5rem;"></i>
                        <div>
                            <div class="fw-600 text-sm"><?= htmlspecialchars($fh['nama_file']) ?></div>
                            <div class="text-xs text-muted">Diterbitkan pada <?= date('d M Y H:i', strtotime($fh['created_at'])) ?></div>
                        </div>
                    </div>
                    <a href="<?= htmlspecialchars(url($fh['path_file'])) ?>" target="_blank" class="btn btn-sm btn-success">
                        <i class="bi bi-download me-1"></i> Unduh File
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Data Aduan -->
        <div class="card mb-4">
            <div class="card-header"><h5><i class="bi bi-file-earmark-text me-2"></i>Data Aduan</h5></div>
            <div class="card-body">
                <table style="width:100%;font-size:.875rem;">
                    <tr><td style="color:var(--text-muted);width:40%;padding:.4rem 0;">Kode Aduan</td><td class="fw-600"><?= htmlspecialchars($aduan['kode_aduan']) ?></td></tr>
                    <tr><td style="color:var(--text-muted);padding:.4rem 0;">Layanan</td><td><?= htmlspecialchars($layanan['nama'] ?? '-') ?></td></tr>
                    <tr><td style="color:var(--text-muted);padding:.4rem 0;">Status</td><td><span class="badge badge-<?= $statusClass ?>"><?= $statusLabel ?></span></td></tr>
                    <tr>
                        <td style="color:var(--text-muted);padding:.4rem 0;">Tahapan Saat Ini</td>
                        <td>
                            <?php if ($aduan['status'] === 'selesai'): ?>
                                <span class="badge badge-success">Selesai</span>
                            <?php elseif ($isInternalStepNow): ?>
                                <span class="badge badge-warning">Penyiapan Dokumen Hasil</span>
                            <?php else: ?>
                                <?= htmlspecialchars($tahapanSkrg['nama_tahapan'] ?? '-') ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr><td style="color:var(--text-muted);padding:.4rem 0;">Tanggal Aduan</td><td><?= date('d M Y H:i', strtotime($aduan['created_at'])) ?></td></tr>
                </table>

                <?php if (!empty($aduan['data_formulir'])): ?>
                <hr style="border:none;border-top:1px solid var(--border);margin:1rem 0;">
                <h6 style="font-weight:600;margin-bottom:.75rem;color:var(--text-muted);font-size:.8rem;text-transform:uppercase;letter-spacing:.05em;">Data Formulir</h6>
                <?php foreach ($aduan['data_formulir'] as $key => $val): ?>
                    <div class="mb-3">
                        <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:.2rem;">
                            <?= htmlspecialchars($fieldMap[$key]['label'] ?? $key) ?>
                        </div>
                        <div class="fw-600"><?= nl2br(htmlspecialchars($val)) ?></div>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Lampiran Berkas Syarat Pengadu -->
        <?php if (!empty($lampiranUser)): ?>
        <div class="card">
            <div class="card-header"><h5><i class="bi bi-paperclip me-2"></i>Lampiran Berkas Syarat</h5></div>
            <div class="card-body">
                <?php foreach ($lampiranUser as $lp): ?>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-file-earmark" style="color:var(--primary);font-size:1.2rem;"></i>
                    <a href="<?= htmlspecialchars(url($lp['path_file'])) ?>" target="_blank" class="text-sm">
                        <?= htmlspecialchars($lp['nama_file']) ?>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Timeline Riwayat -->
    <div class="card">
        <div class="card-header"><h5><i class="bi bi-clock-history me-2"></i>Riwayat Proses</h5></div>
        <div class="card-body">
            <?php if (empty($riwayat)): ?>
            <div class="empty-state" style="padding:1.5rem;">
                <div class="empty-state-icon"><i class="bi bi-clock"></i></div>
                <p>Belum ada riwayat proses.</p>
            </div>
            <?php else: ?>
            <div class="timeline">
                <?php foreach (array_reverse($riwayat) as $rw):
                    $itemClass = $rw['arah'] === 'mundur' ? 'mundur' : ($rw['aksi'] === 'selesai' ? 'selesai' : ($rw['aksi'] === 'ditolak' ? 'ditolak' : ''));
                    $tAsal = $tahapanMap[$rw['tahapan_asal_id']] ?? null;
                    $tTujuan = $tahapanMap[$rw['tahapan_tujuan_id']] ?? null;
                    
                    $asal   = !empty($tAsal['is_internal_only']) ? 'Proses Internal' : ($tAsal['nama_tahapan'] ?? 'Pengadu');
                    $tujuan = !empty($tTujuan['is_internal_only']) ? 'Penyiapan Dokumen' : ($tTujuan['nama_tahapan'] ?? 'Selesai');
                    $oleh   = $userMap[$rw['diproses_oleh']]['nama'] ?? 'Sistem';
                ?>
                <div class="timeline-item <?= $itemClass ?>">
                    <div class="timeline-date"><?= date('d M Y H:i', strtotime($rw['created_at'])) ?> — oleh <?= htmlspecialchars($oleh) ?></div>
                    <div class="timeline-body">
                        <strong><?= $aksiLabels[$rw['aksi']] ?? ucfirst($rw['aksi']) ?></strong>
                        <?php if ($rw['tahapan_asal_id']): ?>
                        <div class="text-xs text-muted mt-1">
                            <?= htmlspecialchars($asal) ?> → <?= htmlspecialchars($tujuan) ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($rw['catatan']): ?>
                        <div class="text-sm mt-1" style="border-left:2px solid var(--border);padding-left:.75rem;margin-top:.5rem;">
                            <?= nl2br(htmlspecialchars($rw['catatan'])) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

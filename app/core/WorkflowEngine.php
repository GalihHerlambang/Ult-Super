<?php
/**
 * WorkflowEngine.php — Mesin workflow pengaduan
 * Menangani: generate approval, cek tahapan selesai, maju/mundur tahapan.
 */
class WorkflowEngine
{
    private AduanApproval $approvalModel;
    private AduanRiwayat $riwayatModel;
    private LayananTahapan $tahapanModel;
    private User $userModel;
    private Aduan $aduanModel;

    public function __construct()
    {
        $this->approvalModel = new AduanApproval();
        $this->riwayatModel  = new AduanRiwayat();
        $this->tahapanModel  = new LayananTahapan();
        $this->userModel     = new User();
        $this->aduanModel    = new Aduan();
    }

    /**
     * Generate approval records saat aduan masuk ke tahapan baru.
     * Snapshot semua user dengan role_pelaksana tahapan tsb.
     */
    public function generateApproval(int $aduanId, array $tahapan): void
    {
        // Batalkan approval lama di tahapan ini
        $existing = $this->approvalModel->where(['aduan_id' => $aduanId, 'tahapan_id' => $tahapan['id']]);
        foreach ($existing as $ap) {
            $this->approvalModel->update($ap['id'], ['status' => 'dibatalkan']);
        }

        // Snapshot user dengan role yang sesuai
        $roleUsers = $this->userModel->where(['role' => $tahapan['role_pelaksana'], 'is_active' => true]);

        if (empty($roleUsers)) {
            // Jika tidak ada user dengan role tsb, buat satu dummy agar tidak stuck
            // (Fallback: langsung generate approval untuk admin)
            $roleUsers = $this->userModel->where(['role' => 'admin']);
        }

        foreach ($roleUsers as $user) {
            $this->approvalModel->insert([
                'aduan_id'         => $aduanId,
                'tahapan_id'       => $tahapan['id'],
                'approver_user_id' => $user['id'],
                'status'           => 'menunggu',
                'catatan'          => null,
                'diproses_at'      => null,
            ]);
        }
    }

    /**
     * Cek apakah tahapan sudah selesai berdasarkan tipe_approval.
     */
    public function tahapanSelesai(int $aduanId, array $tahapan): bool
    {
        $rows = $this->approvalModel->where(['aduan_id' => $aduanId, 'tahapan_id' => $tahapan['id']]);
        // Filter hanya yang tidak dibatalkan
        $rows = array_filter($rows, fn($r) => $r['status'] !== 'dibatalkan');

        if (empty($rows)) return false;

        if ($tahapan['tipe_approval'] === 'any') {
            return count(array_filter($rows, fn($r) => $r['status'] === 'setuju')) > 0;
        }

        // 'all' — semua harus setuju
        $notSetuju = array_filter($rows, fn($r) => !in_array($r['status'], ['setuju', 'dilewati']));
        return count($notSetuju) === 0;
    }

    /**
     * Skip approval lain jika tipe 'any' dan sudah ada yang setuju.
     */
    public function skipSisaApproval(int $aduanId, int $tahapanId, int $approverUserId): void
    {
        $rows = $this->approvalModel->where(['aduan_id' => $aduanId, 'tahapan_id' => $tahapanId]);
        foreach ($rows as $ap) {
            if ($ap['approver_user_id'] != $approverUserId && $ap['status'] === 'menunggu') {
                $this->approvalModel->update($ap['id'], ['status' => 'dilewati', 'diproses_at' => date('Y-m-d H:i:s')]);
            }
        }
    }

    /**
     * Cari tahapan berikutnya (urutan lebih besar).
     */
    public function cariTahapanBerikutnya(array $tahapanSaatIni): ?array
    {
        $semua = $this->tahapanModel->where(['layanan_id' => $tahapanSaatIni['layanan_id']]);
        usort($semua, fn($a, $b) => $a['urutan'] <=> $b['urutan']);
        foreach ($semua as $t) {
            if ($t['urutan'] > $tahapanSaatIni['urutan']) return $t;
        }
        return null;
    }

    /**
     * Cari tahapan sebelumnya (urutan lebih kecil).
     */
    public function cariTahapanSebelumnya(array $tahapanSaatIni): ?array
    {
        $semua = $this->tahapanModel->where(['layanan_id' => $tahapanSaatIni['layanan_id']]);
        usort($semua, fn($a, $b) => $b['urutan'] <=> $a['urutan']);
        foreach ($semua as $t) {
            if ($t['urutan'] < $tahapanSaatIni['urutan']) return $t;
        }
        return null;
    }

    /**
     * Proses persetujuan: update approval, maju tahapan jika selesai.
     */
    public function prosesSetuju(int $aduanId, int $approverUserId, string $catatan = ''): array
    {
        $aduan   = $this->aduanModel->find($aduanId);
        $tahapan = $this->tahapanModel->find($aduan['tahapan_saat_ini_id']);

        // Update approval baris ini
        $approvalId = $this->findApprovalId($aduanId, $tahapan['id'], $approverUserId);
        if (!$approvalId) {
            return ['success' => false, 'message' => 'Data approval tidak ditemukan.'];
        }

        $this->approvalModel->update($approvalId, [
            'status'      => 'setuju',
            'catatan'     => $catatan,
            'diproses_at' => date('Y-m-d H:i:s'),
        ]);

        // Jika 'any', skip yang lain
        if ($tahapan['tipe_approval'] === 'any') {
            $this->skipSisaApproval($aduanId, $tahapan['id'], $approverUserId);
        }

        // Cek apakah tahapan sudah selesai
        if ($this->tahapanSelesai($aduanId, $tahapan)) {
            $next = $this->cariTahapanBerikutnya($tahapan);

            // Catat riwayat
            $this->riwayatModel->insert([
                'aduan_id'          => $aduanId,
                'arah'              => 'maju',
                'tahapan_asal_id'   => $tahapan['id'],
                'tahapan_tujuan_id' => $next ? $next['id'] : null,
                'aksi'              => $next ? 'diteruskan' : 'selesai',
                'catatan'           => $catatan,
                'diproses_oleh'     => $approverUserId,
            ]);

            if ($next) {
                $this->aduanModel->update($aduanId, ['tahapan_saat_ini_id' => $next['id']]);
                $this->generateApproval($aduanId, $next);
                $isTask = ($next['jenis_tahapan'] ?? '') === 'task_execution';
                $msg = $isTask 
                    ? 'Aduan disetujui & diteruskan ke tugas eksekusi internal (' . htmlspecialchars($next['nama_tahapan']) . ').' 
                    : 'Aduan diteruskan ke tahap berikutnya.';
                return ['success' => true, 'message' => $msg, 'action' => 'maju'];
            } else {
                $this->aduanModel->update($aduanId, ['status' => 'selesai']);
                return ['success' => true, 'message' => 'Aduan selesai diproses.', 'action' => 'selesai'];
            }
        }

        return ['success' => true, 'message' => 'Persetujuan berhasil dicatat.', 'action' => 'menunggu'];
    }

    /**
     * Proses penolakan aduan.
     */
    public function prosesTolak(int $aduanId, int $approverUserId, string $catatan = ''): array
    {
        $aduan   = $this->aduanModel->find($aduanId);
        $tahapan = $this->tahapanModel->find($aduan['tahapan_saat_ini_id']);

        $approvalId = $this->findApprovalId($aduanId, $tahapan['id'], $approverUserId);
        if ($approvalId) {
            $this->approvalModel->update($approvalId, [
                'status'      => 'tolak',
                'catatan'     => $catatan,
                'diproses_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $this->aduanModel->update($aduanId, ['status' => 'ditolak']);

        $this->riwayatModel->insert([
            'aduan_id'          => $aduanId,
            'arah'              => 'maju',
            'tahapan_asal_id'   => $tahapan['id'],
            'tahapan_tujuan_id' => null,
            'aksi'              => 'ditolak',
            'catatan'           => $catatan,
            'diproses_oleh'     => $approverUserId,
        ]);

        return ['success' => true, 'message' => 'Aduan berhasil ditolak.', 'action' => 'ditolak'];
    }

    /**
     * Kembalikan aduan ke tahapan sebelumnya (revisi).
     */
    public function prosesKembalikan(int $aduanId, int $tahapanTujuanId, int $approverUserId, string $catatan = ''): array
    {
        $aduan          = $this->aduanModel->find($aduanId);
        $tahapanAsal    = $this->tahapanModel->find($aduan['tahapan_saat_ini_id']);
        $tahapanTujuan  = $this->tahapanModel->find($tahapanTujuanId);

        if (!$tahapanTujuan) {
            return ['success' => false, 'message' => 'Tahapan tujuan tidak ditemukan.'];
        }

        $this->aduanModel->update($aduanId, ['tahapan_saat_ini_id' => $tahapanTujuanId]);

        $this->riwayatModel->insert([
            'aduan_id'          => $aduanId,
            'arah'              => 'mundur',
            'tahapan_asal_id'   => $tahapanAsal['id'],
            'tahapan_tujuan_id' => $tahapanTujuanId,
            'aksi'              => 'dikembalikan',
            'catatan'           => $catatan,
            'diproses_oleh'     => $approverUserId,
        ]);

        // Reset approval di tahapan tujuan
        $this->generateApproval($aduanId, $tahapanTujuan);

        return ['success' => true, 'message' => 'Aduan dikembalikan ke tahap sebelumnya.', 'action' => 'mundur'];
    }

    /**
     * Cari ID approval berdasarkan aduan+tahapan+user.
     */
    public function findApprovalId(int $aduanId, int $tahapanId, int $userId): ?int
    {
        $rows = $this->approvalModel->where([
            'aduan_id'         => $aduanId,
            'tahapan_id'       => $tahapanId,
            'approver_user_id' => $userId,
        ]);
        // Ambil yang status menunggu
        foreach ($rows as $row) {
            if ($row['status'] === 'menunggu') return $row['id'];
        }
        // Fallback: ambil yang pertama
        return !empty($rows) ? $rows[0]['id'] : null;
    }
}

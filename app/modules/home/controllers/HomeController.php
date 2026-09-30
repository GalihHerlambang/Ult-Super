<?php
require_once APP_PATH . '/core/Controller.php';

class HomeController extends Controller
{
    private Layanan $layananModel;
    private Setting $settingModel;
    private Aduan $aduanModel;
    private LayananTahapan $tahapanModel;

    public function __construct()
    {
        $this->layananModel = new Layanan();
        $this->settingModel = new Setting();
        $this->aduanModel   = new Aduan();
        $this->tahapanModel = new LayananTahapan();
    }

    public function index(): void
    {
        $setting     = $this->settingModel->getSetting();
        $layanans    = $this->layananModel->getActive();
        $allAduan    = $this->aduanModel->all();

        // Count stats for landing page hero
        $stats = [
            'total_aduan'   => count($allAduan),
            'selesai'       => count(array_filter($allAduan, fn($a) => $a['status'] === 'selesai')),
            'total_layanan' => count($layanans),
        ];

        // Enrich layanan with tahapan count & field info
        foreach ($layanans as &$l) {
            $tahapan = $this->tahapanModel->getByLayanan($l['id']);
            $l['total_tahapan'] = count($tahapan);
            $l['tahapan_list']  = array_column($tahapan, 'nama_tahapan');
            $l['tahapan_detail'] = array_map(function($t) {
                return [
                    'nama' => $t['nama_tahapan'],
                    'role' => $t['role_pelaksana'],
                    'jenis' => $t['jenis_tahapan'] ?? 'approval',
                    'is_internal' => !empty($t['is_internal_only']),
                    'sla' => $t['sla_hari'] ?? 3
                ];
            }, $tahapan);
        }

        $pageTitle = 'Layanan Pengaduan Publik';
        
        // Render landing page (without main backend layout, standalone custom landing layout)
        extract(compact('setting', 'layanans', 'stats', 'pageTitle'));
        require APP_PATH . '/modules/home/views/index.php';
    }

    public function cekAduan(): void
    {
        $kode = trim($_GET['kode'] ?? '');
        if (empty($kode)) {
            $this->json(['success' => false, 'message' => 'Kode aduan tidak boleh kosong.']);
        }
        $all = $this->aduanModel->all();
        $found = null;
        foreach ($all as $a) {
            if (strtolower(trim($a['kode_aduan'])) === strtolower($kode)) {
                $found = $a;
                break;
            }
        }
        if (!$found) {
            $this->json(['success' => false, 'message' => "Aduan dengan kode '{$kode}' tidak ditemukan."]);
        }

        $layanan = $this->layananModel->find($found['layanan_id']);
        $publicTahapan = $this->tahapanModel->getTahapanPublic($found['layanan_id']);
        
        $allTahapan = $this->tahapanModel->getByLayanan($found['layanan_id']);
        $tahapanMap = array_column($allTahapan, null, 'id');
        $tahapanSkrg = $tahapanMap[$found['tahapan_saat_ini_id']] ?? null;

        // User info if available
        $userModel = new User();
        $user = $userModel->find($found['user_id'] ?? 0);
        $pemohonNama = $user['nama'] ?? ($found['data_formulir']['nama_peserta'] ?? 'Pengadu');

        // Build step progress array for public timeline in tracker
        $steps = [];
        $currentUrutan = $tahapanSkrg['urutan'] ?? 1;
        $isFinished = ($found['status'] === 'selesai');
        $isRejected = ($found['status'] === 'ditolak');

        foreach ($publicTahapan as $st) {
            $statusStep = 'pending';
            if ($isFinished) {
                $statusStep = 'completed';
            } else if ($isRejected) {
                if ($st['urutan'] < $currentUrutan) {
                    $statusStep = 'completed';
                } else if ($st['urutan'] == $currentUrutan) {
                    $statusStep = 'rejected';
                } else {
                    $statusStep = 'pending';
                }
            } else {
                if ($st['urutan'] < $currentUrutan) {
                    $statusStep = 'completed';
                } else if ($st['urutan'] == $currentUrutan) {
                    $statusStep = 'active';
                } else {
                    $statusStep = 'pending';
                }
            }

            $steps[] = [
                'id'     => $st['id'],
                'nama'   => $st['nama_tahapan'],
                'role'   => strtoupper($st['role_pelaksana'] ?? 'Helpdesk'),
                'urutan' => $st['urutan'],
                'status' => $statusStep,
                'sla'    => $st['sla_hari'] ?? 1
            ];
        }

        $this->json([
            'success' => true,
            'data' => [
                'id'               => $found['id'],
                'kode_aduan'       => $found['kode_aduan'],
                'status'           => $found['status'],
                'layanan_nama'     => $layanan['nama'] ?? '-',
                'tahapan_saat_ini' => $isFinished ? 'Selesai' : ($tahapanSkrg['nama_tahapan'] ?? '-'),
                'pemohon_nama'     => $pemohonNama,
                'created_at'       => date('d M Y, H:i', strtotime($found['created_at'])),
                'updated_at'       => !empty($found['updated_at']) ? date('d M Y, H:i', strtotime($found['updated_at'])) : '-',
                'file_hasil'       => !empty($found['file_hasil']) ? url($found['file_hasil']) : null,
                'steps'            => $steps
            ]
        ]);
    }
}

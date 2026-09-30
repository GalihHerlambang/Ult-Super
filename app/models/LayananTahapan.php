<?php
class LayananTahapan extends Model
{
    public function __construct() { parent::__construct('layanan_tahapan'); }

    public function getByLayanan(int $layananId): array
    {
        $tahapan = $this->where(['layanan_id' => $layananId]);
        usort($tahapan, fn($a, $b) => $a['urutan'] <=> $b['urutan']);
        return $tahapan;
    }

    public function getTahapanPublic(int $layananId): array
    {
        $tahapan = $this->getByLayanan($layananId);
        return array_values(array_filter($tahapan, fn($t) => empty($t['is_internal_only'])));
    }

    public function getTahapanPertama(int $layananId): ?array
    {
        $tahapan = $this->getByLayanan($layananId);
        return $tahapan[0] ?? null;
    }
}

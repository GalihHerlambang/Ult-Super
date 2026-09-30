<?php
class AduanLampiran extends Model
{
    public function __construct() { parent::__construct('aduan_lampiran'); }

    public function getFileHasil(int $aduanId): array
    {
        $lampiran = $this->where(['aduan_id' => $aduanId]);
        return array_values(array_filter($lampiran, fn($l) => ($l['jenis_lampiran'] ?? '') === 'file_hasil'));
    }

    public function getLampiranUser(int $aduanId): array
    {
        $lampiran = $this->where(['aduan_id' => $aduanId]);
        return array_values(array_filter($lampiran, fn($l) => ($l['jenis_lampiran'] ?? 'persyaratan') !== 'file_hasil'));
    }
}

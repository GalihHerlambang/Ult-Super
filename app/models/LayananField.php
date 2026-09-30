<?php
class LayananField extends Model
{
    public function __construct() { parent::__construct('layanan_field'); }

    public function getByLayanan(int $layananId): array
    {
        $fields = $this->where(['layanan_id' => $layananId]);
        usort($fields, fn($a, $b) => $a['urutan'] <=> $b['urutan']);
        return $fields;
    }
}

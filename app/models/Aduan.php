<?php
class Aduan extends Model
{
    public function __construct() { parent::__construct('aduan'); }

    public function generateKode(): string
    {
        $year  = date('Y');
        $count = count($this->where(['kode_aduan' => null])) + count($this->all());
        return 'ADU-' . $year . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }
}

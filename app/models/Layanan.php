<?php
class Layanan extends Model
{
    public function __construct() { parent::__construct('layanan'); }

    public function getActive(): array
    {
        return $this->where(['is_active' => true]);
    }
}

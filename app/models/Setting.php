<?php
/**
 * Setting.php — Singleton JSON setting identitas aplikasi
 */
class Setting extends Model
{
    public function __construct() { parent::__construct('setting'); }

    /**
     * Ambil setting sebagai array key-value (bukan array of records).
     */
    public function getSetting(): array
    {
        $data = $this->readAll();
        if (empty($data)) {
            return $this->getDefault();
        }
        return $data[0] ?? $this->getDefault();
    }

    /**
     * Simpan setting (replace semua dengan satu record).
     */
    public function saveSetting(array $data): bool
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->writeAll([$data]);
    }

    private function getDefault(): array
    {
        return [
            'nama_aplikasi' => 'Super Aplikasi Pengaduan',
            'singkatan'     => 'SUPERADU',
            'alamat'        => 'Jl. Contoh No. 1, Kota, Provinsi',
            'telepon'       => '(021) 12345678',
            'email'         => 'info@contoh.go.id',
            'website'       => 'https://contoh.go.id',
            'logo'          => '',
            'logo_footer'   => '',
            'favicon'       => '',
            'deskripsi'     => 'Aplikasi pengaduan layanan publik',
            'footer_text'   => '© ' . date('Y') . ' Super Aplikasi Pengaduan',
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
    }
}

# Dokumen Kebutuhan Produk (PRD)
**Nama Aplikasi**: Sistem Manajemen Pengaduan & Layanan Super
**Versi**: 1.0

## 1. Pendahuluan
Dokumen ini menguraikan kebutuhan produk untuk Sistem Manajemen Pengaduan dan Layanan "Super". Aplikasi ini adalah platform berbasis web yang dirancang untuk menangani permintaan layanan, pengaduan, dan alur kerja (workflow) masing-masing. Sistem ini memungkinkan konfigurasi layanan secara kustom, field formulir yang dinamis, serta proses persetujuan (approval) bertingkat.

## 2. Tujuan
- Menyediakan platform terpusat untuk mengirimkan dan melacak pengaduan atau permintaan layanan.
- Mengaktifkan alur kerja layanan dan field formulir yang dinamis dan dapat disesuaikan.
- Memfasilitasi lingkungan multi-peran yang terstruktur untuk memastikan akuntabilitas dan transparansi.
- Melacak seluruh riwayat dan proses persetujuan dari setiap tiket pengaduan.

## 3. Peran Pengguna (User Roles)
Sistem ini mendukung peran pengguna berikut:
1. **Admin**: Memiliki akses penuh ke sistem. Mengelola pengguna, pengaturan sistem, jenis layanan, field dinamis, dan alur kerja.
2. **Pengadu**: Pengguna akhir yang mengirimkan pengaduan atau permintaan. Mereka dapat melihat status dan riwayat pengajuan mereka.
3. **Helpdesk**: Staf dukungan garis depan yang menerima, meninjau, dan memproses pengaduan melalui tahapan alur kerja yang telah ditentukan.
4. **Atasan**: Manajemen tingkat atas yang bertanggung jawab untuk meninjau dan menyetujui pengaduan pada tahapan alur kerja tertentu.

## 4. Fitur Utama & Modul

### 4.1 Modul Autentikasi (`auth`)
- Login, logout, dan manajemen sesi pengguna.
- Kontrol akses berbasis peran untuk mengarahkan pengguna ke dasbor masing-masing.

### 4.2 Modul Administrator (`admin`)
- **Dasbor**: Ringkasan statistik sistem.
- **Manajemen Pengguna**: Membuat, membaca, memperbarui, menghapus pengguna, dan menetapkan peran.
- **Manajemen Layanan (`Layanan`)**:
  - Menentukan berbagai jenis layanan atau kategori pengaduan.
  - **Field Dinamis (`LayananField`)**: Mengonfigurasi field input kustom yang diperlukan untuk layanan tertentu.
  - **Alur Kerja / Tahapan (`LayananTahapan`)**: Menentukan tahapan yang harus dilalui oleh setiap jenis pengaduan.
- **Pengaturan Sistem (`Setting`)**: Konfigurasi aplikasi secara global.

### 4.3 Modul Pengadu (`pengadu`)
- **Kirim Pengaduan (`Aduan`)**: Mengisi formulir dinamis berdasarkan jenis layanan yang dipilih.
- **Lampiran (`AduanLampiran`)**: Mengunggah dokumen atau gambar pendukung.
- **Pelacakan (`AduanRiwayat`)**: Melihat garis waktu (timeline), pembaruan status, dan riwayat pengaduan yang diajukan.

### 4.4 Modul Helpdesk (`helpdesk`)
- **Antrean Tiket**: Melihat pengaduan yang masuk.
- **Pemrosesan**: Memperbarui status tiket dan memindahkannya melalui tahapan alur kerja yang telah ditentukan (`LayananTahapan`).
- **Komunikasi**: Menambahkan catatan dan rekaman riwayat ke timeline tiket.

### 4.5 Modul Atasan (`atasan`)
- **Antrean Persetujuan**: Melihat tiket yang memerlukan persetujuan manajerial.
- **Mekanisme Persetujuan (`AduanApproval`)**: Menyetujui atau menolak tiket berdasarkan persyaratan tahapan alur kerja saat ini.

## 5. Arsitektur Model Data
Aplikasi ini menggunakan struktur database JSON *flat-file* (`/database/json/`).
- `users.json`: Menyimpan kredensial pengguna dan informasi peran.
- `setting.json`: Pengaturan seluruh sistem.
- `layanan.json`: Jenis dan kategori layanan.
- `layanan_field.json`: Definisi field kustom per layanan.
- `layanan_tahapan.json`: Tahapan alur kerja per layanan.
- `aduan.json`: Rekaman data inti pengaduan/tiket.
- `aduan_lampiran.json`: Referensi ke file yang diunggah untuk tiket.
- `aduan_riwayat.json`: Jejak audit dan riwayat status untuk setiap tiket.
- `aduan_approval.json`: Log persetujuan dan keputusan oleh atasan.

## 6. Stack Teknologi
- **Backend/Logika**: PHP (Arsitektur MVC Kustom)
- **Database**: Penyimpanan *flat-file* berbasis JSON
- **Frontend**: HTML, CSS, JavaScript (Render dinamis berdasarkan `LayananField`)
- **Arsitektur**: Struktur modular (`app/modules/`) yang memisahkan logika berdasarkan peran pengguna dan fungsionalitas.

## 7. Pengembangan di Masa Depan
- Migrasi dari file JSON *flat-file* ke database relasional (misalnya, MySQL/PostgreSQL) untuk skalabilitas yang lebih baik.
- Integrasi email atau notifikasi *Push* untuk pembaruan status.
- Dasbor analitik dan pelaporan tingkat lanjut.

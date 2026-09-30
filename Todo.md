# Task List & Pengembangan (Todo List)
**Proyek**: Sistem Manajemen Pengaduan & Layanan Super

Berikut adalah estimasi rincian pekerjaan berdasarkan struktur dan file aplikasi saat ini:

## ✅ Pekerjaan yang Sudah Dikerjakan (Done)
- **Struktur Dasar Aplikasi (Core Framework)**:
  - Arsitektur MVC (Model-View-Controller) kustom sudah dibuat (terlihat di `app/core`).
  - Sistem *Routing* untuk berbagai modul peran (admin, auth, helpdesk, atasan, pengadu).
- **Basis Data *Flat-File***:
  - Skema dan file JSON telah dibuat (`users.json`, `layanan.json`, `aduan.json`, dll).
- **Manajemen Model**:
  - Class Model untuk setiap entitas sudah disiapkan (`Aduan.php`, `User.php`, `Layanan.php`, `Setting.php`, dll).
- **Modul Autentikasi (`auth`)**:
  - Logika verifikasi pengguna (berdasarkan `User::verifyPassword`).
  - Manajemen peran (*role-based login*).
- **Struktur Dasbor Multi-Peran**:
  - Pembuatan *Controller* dan *View* untuk masing-masing peran (Admin, Pengadu, Helpdesk, Atasan).

## ⏳ Pekerjaan yang Belum Dikerjakan (To-Do / Dalam Proses)
- **Validasi Input & Keamanan**:
  - Validasi ketat pada *field* dinamis (`LayananField`) saat pengguna (*pengadu*) mensubmit data.
  - Implementasi keamanan tingkat lanjut (CSRF token, *XSS Filtering*) khususnya karena ini menggunakan *flat-file* database.
- **Logika File Upload**:
  - Penyempurnaan pemrosesan unggahan dokumen atau gambar lampiran (`AduanLampiran`) untuk memastikan ekstensi dan ukuran file aman.
- **Pengujian Alur Kerja (Workflow Testing)**:
  - Menguji *End-to-End* (E2E) jalannya tiket dari *Pengadu* -> *Helpdesk* -> *Atasan* (Approval) berdasarkan tahapan dinamis (`LayananTahapan`).
- **UI/UX Polish**:
  - Meningkatkan tampilan antarmuka (mungkin menggunakan *framework* CSS atau desain modern kustom) agar responsif dan mudah digunakan (khususnya untuk formulir dinamis).
- **Paginasi & Optimasi Performa JSON**:
  - Logika memecah data (paginasi) di dasbor jika ukuran array JSON untuk `aduan` atau `riwayat` membesar.

## 🚀 Pengembangan yang Bisa Dilakukan (Future Enhancements)
- **Migrasi ke Database Relasional (MySQL/PostgreSQL)**:
  - Karena operasi baca/tulis JSON secara terus-menerus (terutama ketika aplikasi membesar) rawan *corrupt* atau lambat, migrasi dari JSON ke MySQL adalah prioritas utama.
- **Sistem Notifikasi**:
  - Menambahkan notifikasi Email atau WhatsApp Gateway setiap ada perubahan status tiket (kepada Pengadu) atau saat ada tiket yang perlu di-*approve* (kepada Atasan).
- **Fitur Ekspor & Laporan (Reporting)**:
  - Kemampuan bagi Admin untuk mengekspor data pengaduan per rentang waktu ke format PDF atau Microsoft Excel.
- **API (Application Programming Interface)**:
  - Membuka *endpoint* REST API jika ke depannya sistem ini ingin dibuatkan versi Aplikasi *Mobile* (Android/iOS).
- **Dasbor Analitik & Grafik**:
  - Menampilkan grafik (menggunakan *Chart.js* atau *ApexCharts*) untuk tren pengaduan (misal: jumlah pengaduan per kategori, *Service Level Agreement* / SLA resolution time).
- **SLA (Service Level Agreement)**:
  - Batas waktu penyelesaian untuk setiap tahap (jika melewati batas waktu, tiket ditandai warna merah/eskalasi otomatis).

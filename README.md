🗂️ SISLEMDA – Sistem Lembar Kendali

SISLEMDA (Sistem Lembar Kendali) adalah aplikasi berbasis web untuk mengelola proses pengajuan kegiatan dalam lingkungan institusi pendidikan atau organisasi. Sistem ini dibuat untuk memudahkan manajemen pengguna, prodi, fakultas, klasifikasi pengajuan, hingga pelacakan status pengajuan yang dilakukan oleh user.

🚀 Fitur Utama

- **🔐 Login Multi-Role**  
  Mendukung login untuk berbagai peran seperti Admin, Dosen, Kaprodi, Dekan, Wakil Rektor, Rektor, Keuangan, hingga Yayasan.

- **👥 Manajemen Pengguna**
  - Tambah/edit/hapus pengguna
  - Role pengguna per prodi/fakultas

- **🏢 Manajemen Unit & Klasifikasi Pengajuan**
  - Kelola unit pengaju
  - Kelola klasifikasi pengajuan

- **📚 Manajemen Prodi & Fakultas**
  - Input dan update data Prodi dan Fakultas

- **📑 Pengajuan Kegiatan**
  - Menampilkan 10 pengajuan terbaru
  - Statistik pengajuan per bulan (Chart)

- **📊 Dashboard Interaktif**
  - Card indikator jumlah data (pengguna, prodi, fakultas, dll.)
  - Chart statistik pengajuan bulanan
  - Tabel data pengguna & pengajuan

🛠️ Teknologi yang Digunakan

- **Backend**: PHP + CodeIgniter 3  
- **Database**: MySQL  
- **Frontend**: HTML5, CSS3, Bootstrap 5, DataTables  
- **Library Tambahan**:
  - FontAwesome / Bootstrap Icons
  - Chart.js

📂 Struktur Direktori Penting



application/
├── controllers/
│   └── Admins.php
├── models/
│   └── Admin\_m.php
├── views/
│   ├── admin/
│   │   └── dashboard/
│   │       └── index.php
│   └── template/
│       ├── header.php
│       ├── footer.php
│       └── sidebar\_admin.php



📦 Cara Menjalankan

1. Clone repository ini:
   bash
   git clone https://github.com/Nbill27/SISLEMDA_BIU.git

2. Import file `sislemda.sql` ke database MySQL
3. Sesuaikan konfigurasi database di `application/config/database.php`
4. Jalankan project menggunakan local server (XAMPP/Laragon)
5. Login dengan akun yang tersedia di tabel `user`

👨‍💻 Kontributor

* Nabil (Nbill27) – Fullstack Developer & Admin System Builder
* TEAM – Builder



📃 Lisensi

Proyek ini bebas digunakan untuk pengembangan internal dan pembelajaran. Silakan fork dan modifikasi sesuai kebutuhanmu.

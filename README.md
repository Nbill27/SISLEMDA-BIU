<div align="center">

# SISLEMDA
### Sistem Lembar Kendali

Aplikasi web untuk mengelola proses pengajuan kegiatan di lingkungan institusi pendidikan, mulai dari manajemen pengguna, prodi, fakultas, klasifikasi pengajuan, hingga pelacakan status pengajuan secara real-time.

[![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)](https://www.php.net/)
[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-3-EF4223?style=flat&logo=codeigniter&logoColor=white)](https://codeigniter.com/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/License-Free%20for%20Learning-blue)](#lisensi)

</div>

---

## Daftar Isi

- [Tentang Proyek](#tentang-proyek)
- [Fitur Utama](#fitur-utama)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Struktur Direktori](#struktur-direktori)
- [Instalasi](#instalasi)
- [Role Pengguna](#role-pengguna)
- [Kontributor](#kontributor)
- [Lisensi](#lisensi)

---

## Tentang Proyek

**SISLEMDA (Sistem Lembar Kendali)** adalah sistem digital yang dirancang untuk menggantikan proses pengajuan kegiatan manual di institusi pendidikan. Sistem ini menyederhanakan alur persetujuan lintas jabatan, mulai dari Dosen hingga Yayasan, dengan dashboard terpusat yang menampilkan statistik dan status pengajuan secara transparan.

## Fitur Utama

| Modul | Deskripsi |
|---|---|
| **Login Multi-Role** | Mendukung Admin, Dosen, Kaprodi, Dekan, Wakil Rektor, Rektor, Keuangan, dan Yayasan |
| **Manajemen Pengguna** | Tambah, edit, hapus pengguna beserta pengaturan role per prodi/fakultas |
| **Manajemen Unit & Klasifikasi** | Kelola unit pengaju dan klasifikasi jenis pengajuan |
| **Manajemen Prodi & Fakultas** | Input dan update data program studi serta fakultas |
| **Pengajuan Kegiatan** | Menampilkan 10 pengajuan terbaru dan riwayat lengkap |
| **Dashboard Interaktif** | Card indikator jumlah data, chart statistik bulanan, dan tabel data dinamis |

## Teknologi yang Digunakan

- **Backend:** PHP, CodeIgniter 3
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, Bootstrap 5, DataTables
- **Library Tambahan:** FontAwesome / Bootstrap Icons, Chart.js

## Struktur Direktori

```
application/
├── controllers/
│   └── Admins.php
├── models/
│   └── Admin_m.php
├── views/
│   ├── admin/
│   │   └── dashboard/
│   │       └── index.php
│   └── template/
│       ├── header.php
│       ├── footer.php
│       └── sidebar_admin.php
```

## Instalasi

1. Clone repository ini
   ```bash
   git clone https://github.com/Nbill27/SISLEMDA_BIU.git
   ```
2. Import file `sislemda.sql` ke database MySQL
3. Sesuaikan konfigurasi koneksi database di `application/config/database.php`
4. Jalankan project menggunakan local server (XAMPP/Laragon)
5. Login menggunakan akun yang tersedia pada tabel `user`

## Role Pengguna

| Role | Akses |
|---|---|
| Admin | Kelola data master (pengguna, prodi, fakultas, unit, klasifikasi) |
| Dosen | Mengajukan kegiatan |
| Kaprodi | Meninjau dan menyetujui pengajuan tingkat prodi |
| Dekan | Meninjau dan menyetujui pengajuan tingkat fakultas |
| Wakil Rektor / Rektor | Persetujuan tingkat institusi |
| Keuangan | Verifikasi anggaran pengajuan |
| Yayasan | Persetujuan akhir |

## Kontributor

- **Nabil ([@Nbill27](https://github.com/Nbill27))** - Fullstack Developer & Admin System Builder
- **Team** - Builder

## Lisensi

Proyek ini bebas digunakan untuk pengembangan internal dan pembelajaran. Silakan fork dan modifikasi sesuai kebutuhanmu.

---

<div align="center">
Dibuat dengan dedikasi untuk digitalisasi proses administrasi kampus.
</div>

<div align="center">

# ⚽ FutsalHub

**Sistem Reservasi & Manajemen Lapangan Futsal Berbasis Web**

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.5%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-10B981?style=for-the-badge)

Platform web modern untuk otomatisasi pemesanan lapangan futsal secara *real-time*, pencegahan jadwal ganda (*anti-double booking*), pembayaran multi-kanal (Tunai & QRIS), serta manajemen operasional arena terpadu.

</div>

---

## 📋 Deskripsi Umum

**FutsalHub** dibangun untuk menggantikan pencatatan reservasi manual menjadi sistem terintegrasi yang cepat, akurat, dan transparan bagi penyewa (*Customer*) maupun pengelola arena (*Administrator*). Sistem ini menangani siklus pemesanan dari pemilihan jenis lantai lapangan, pengecekan slot jam operasional secara *real-time*, penguncian transaksi untuk mencegah bentrok jadwal, verifikasi pembayaran, hingga pelaporan keuangan siap cetak.

---

## ✨ Fitur Utama Sistem

### 👤 Modul Customer (Penyewa Lapangan)
1. **Cek Jadwal & Ketersediaan Lapangan secara Real-Time**  
   Menampilkan status ketersediaan slot jam operasional lapangan secara langsung berdasarkan tanggal dan jenis lapangan yang dipilih. Sistem otomatis menonaktifkan tanggal masa lalu serta slot jam yang sudah melewati waktu berjalan (zona waktu `Asia/Jakarta`).
2. **Validasi Anti-Bentrok Jadwal (*Slot Overlap Validation*)**  
   Mendukung pemesanan *multi-slot* (beberapa jam sekaligus) dalam satu transaksi yang dilindungi oleh *Database Transaction* dan *Pessimistic Locking* (`lockForUpdate()`), sehingga mencegah bentrok jadwal atau *double-booking* saat dua pengguna memesan slot yang sama secara bersamaan.
3. **Multi-Metode Pembayaran (Tunai & QRIS)**  
   - **QRIS Dinamis**: Menampilkan kode QR pembayaran arena dilengkapi fitur unggah bukti transfer (`JPG`, `JPEG`, `PNG`, `WEBP` maks. `2MB`).
   - **Tunai di Kasir (*Cash*)**: Pelanggan dapat melakukan reservasi terlebih dahulu dan melakukan pelunasan langsung di kasir arena saat hari pertandingan.
4. **Riwayat Pesanan, Invoice & Pembatalan Mandiri**  
   Menampilkan kode booking unik (`FTS-XXXXXX`), rincian jam bermain, status pesanan (*Pending*, *Terkonfirmasi*, *Dibatalkan*), invoice digital, serta opsi pembatalan sebelum diverifikasi admin.
5. **Autentikasi & Manajemen Akun (+62 WhatsApp)**  
   Registrasi akun dengan standardisasi otomatis nomor WhatsApp format `+62`, pembaruan profil dan foto avatar, serta dukungan notifikasi *Dynamic Island*.

### 🛡️ Modul Administrator (Pengelola Arena)
1. **Panel Manajemen Jadwal & Data Lapangan**  
   Pengelolaan penuh (*CRUD*) data lapangan mencakup tipe lantai (*Vinyl*, *Rumput Sintetis*, *Interlock*), tarif sewa per jam, deskripsi fasilitas, gambar lapangan, serta status operasional (*Tersedia* / *Maintenance*).
2. **Verifikasi Reservasi & Pembayaran Dua Arah**  
   - **QRIS**: Pratinjau bukti transfer resolusi penuh via modal untuk aksi **Setujui** (*Confirmed*) atau **Tolak** (*Cancelled*).
   - **Cash**: Konfirmasi pelunasan tunai di kasir atau pembatalan pesanan jika penyewa berhalangan hadir.
3. **Manajemen Data Customer & Direct Chat WhatsApp**  
   Daftar member lengkap dengan tombol **Chat WhatsApp** satu klik (`wa.me/62...`) beserta template pesan otomatis, serta kebijakan pemulihan akun (*SoftDeletes*) yang membedakan akun dihapus oleh admin dan akun dihapus mandiri oleh pengguna.
4. **Rekap Laporan Sewa & Cetak Dokumen Resmi**  
   Filter laporan berdasarkan rentang tanggal, lapangan, dan status reservasi dengan status awal *menunggu difilter*, paginasi navigasi **Prev / Next**, ringkasan pendapatan terverifikasi, serta tampilan cetak dokumen laporan (*print-ready layout*).

---

## 🛠️ Tech Stack

| Kategori | Teknologi | Keterangan |
| :--- | :--- | :--- |
| **Backend Framework** | **Laravel 13** (`v13.40.0`) | Arsitektur MVC, Routing, Middleware RBAC, Eloquent ORM |
| **Bahasa Pemrograman** | **PHP 8.5+** (Min. `^8.3`) | Logika bisnis server-side & keamanan transaksi |
| **Database** | **MySQL / MariaDB** | Penyimpanan data relasional dengan integritas ACID & *SoftDeletes* |
| **Frontend & Templating** | **Blade Templates** & **Vanilla JS** | *Server-Side Rendering* (SSR) & pengecekan slot via Fetch API |
| **Styling & Bundler** | **Tailwind CSS v4** & **Vite 8** | Antarmuka responsif modern & kompilasi aset cepat |

---

## 🚀 Panduan Instalasi Lokal

### Prasyarat Sistem
Pastikan perangkat Anda sudah terpasang:
- **PHP >= 8.3** (direkomendasikan PHP 8.5, atau gunakan [Laragon](https://laragon.org/))
- **Composer**
- **Node.js & NPM**
- **MySQL Server**

### Langkah-Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/seevaroo/FutsalHub.git
   cd FutsalHub
   ```

2. **Install Dependensi PHP (Composer)**
   ```bash
   composer install
   ```

3. **Salin dan Konfigurasi File `.env`**  
   Salin `.env.example` menjadi `.env`:
   ```bash
   # Linux / macOS / Git Bash
   cp .env.example .env

   # Windows PowerShell
   Copy-Item .env.example .env
   ```
   Buat database baru bernama `futsalhub` di MySQL / phpMyAdmin, lalu sesuaikan konfigurasi koneksi database di dalam file `.env`:
   ```env
   APP_NAME=FutsalHub
   APP_URL=http://localhost:8000
   APP_TIMEZONE=Asia/Jakarta

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=futsalhub
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key & Symbolic Link Storage**
   ```bash
   php artisan key:generate
   php artisan storage:link
   ```
   > Perintah `storage:link` diperlukan agar gambar lapangan, foto profil/avatar, dan unggahan bukti transfer QRIS dapat diakses oleh browser.

5. **Jalankan Migrasi Database & Seeder**
   ```bash
   php artisan migrate --seed
   ```
   > Seeder akan mengisi akun demo awal (Admin & Customer), 3 master data lapangan (*Vinyl*, *Sintetis*, *Interlock*), serta slot jam operasional (`08:00` – `22:00`).

6. **Install Dependensi Frontend & Build Aset**
   ```bash
   npm install
   npm run build
   ```
   *(Gunakan `npm run dev` jika Anda ingin menjalankan Vite dalam mode pengembangan/hot-reload).*

7. **Jalankan Server Aplikasi Lokal**
   ```bash
   php artisan serve
   ```
   Buka browser dan kunjungi **`http://localhost:8000`**.

---

## 🔐 Akun Demo (Hasil Seeder)

Setelah menjalankan `php artisan migrate --seed`, Anda dapat langsung menguji aplikasi menggunakan akun berikut:

| Peran (*Role*) | Email | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin@futsal.com` | `password123` |
| **Customer** | `customer@gmail.com` | `password123` |

---

## 👨‍💻 Informasi Pengembang (Author)

- **Nama:** Alvaro Raka Pratama
- **Kompetensi Keahlian:** Rekayasa Perangkat Lunak (RPL)
- **Repository:** [github.com/seevaroo/FutsalHub](https://github.com/seevaroo/FutsalHub)

---

<div align="center">
  <sub>Dikembangkan dengan menggunakan Laravel 13 &amp; Tailwind CSS v4 — &copy; 2026 FutsalHub</sub>
</div>

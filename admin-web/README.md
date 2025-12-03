# 🕌 Digital Mosque Display (Mading Masjid Digital)

Aplikasi Digital Signage berbasis web untuk menampilkan informasi Masjid, Jadwal Sholat, Laporan Keuangan, dan Slider Informasi secara *real-time* di layar TV/Monitor.

Dibangun menggunakan arsitektur **Microservices**:

  * **Frontend & Admin Panel:** Laravel 10
  * **Backend API:** Lumen 10
  * **Database:** MySQL
  * **Containerization:** Docker

*(Anda bisa mengganti link gambar di atas dengan screenshot asli proyek Anda nanti)*

-----

## ✨ Fitur Utama

1.  **Smart Display TV:**

      * Tampilan *Glassmorphism* Modern.
      * **Background Slider Hidup:** Gambar berganti otomatis memenuhi layar.
      * **Jadwal Sholat Otomatis:** Menyesuaikan koordinat lokasi masjid (API Aladhan).
      * **Highlight Waktu Sholat:** Penanda visual saat mendekati waktu sholat tertentu.
      * **Running Text:** Informasi teks berjalan yang bisa diupdate realtime.
      * **Laporan Kas Overlay:** Pop-up transparansi keuangan (Pemasukan/Pengeluaran) yang muncul di sela-sela slider.

2.  **Sistem Cerdas (Smart Mode):**

      * **Jeda Iqomah:** Hitung mundur (Countdown) otomatis saat masuk waktu Adzan.
      * **Standby Mode:** Layar otomatis gelap/mati saat waktu sholat berlangsung agar tidak mengganggu kekhusyukan.

3.  **Admin Panel Lengkap:**

      * Dashboard Statistik Modern.
      * Pengaturan Identitas Masjid & Koordinat Lokasi.
      * Manajemen Slider (Upload Gambar).
      * Pencatatan Kas Masjid (Pemasukan & Pengeluaran).

-----

## 🚀 Cara Instalasi (Pilihan 1: Menggunakan Docker) **[Direkomendasikan]**

Cara termudah dan tercepat karena tidak perlu install PHP/MySQL manual di komputer Anda. Pastikan **Docker** dan **Docker Compose** sudah terinstall.

### 1\. Clone Repository

```bash
git clone https://github.com/muhendrico/digital_mosque_display.git
cd digital_mosque_display
```

### 2\. Setup Environment

Copy file konfigurasi contoh menjadi file aktif untuk kedua layanan (Admin & API).

```bash
cp admin-web/.env.example admin-web/.env
cp api-service/.env.example api-service/.env
```

*Catatan: Setting database di dalam `.env` sudah disesuaikan untuk Docker (`DB_HOST=mysql`), jadi biarkan default jika Anda tidak mengubah `docker-compose.yml`.*

### 3\. Jalankan Container

```bash
docker-compose up -d --build
```

Tunggu hingga semua container (nginx, mysql, admin\_app, api\_app) berjalan.

### 4\. Instalasi Dependensi & Database

Jalankan perintah berikut satu per satu untuk menyiapkan aplikasi:

```bash
# 1. Install Library PHP
docker-compose exec admin_app composer install
docker-compose exec api_app composer install

# 2. Generate Key Keamanan
docker-compose exec admin_app php artisan key:generate

# 3. Migrasi Database & Isi Data Awal (Seeder)
docker-compose exec admin_app php artisan migrate --seed

# 4. Link Folder Gambar (Agar slider muncul)
docker-compose exec admin_app php artisan storage:link
```

### 5\. Selesai\!

Akses aplikasi melalui browser:

  * **Layar TV:** [http://localhost:8000](https://www.google.com/search?q=http://localhost:8000)
  * **Admin Panel:** [http://localhost:8000/admin](https://www.google.com/search?q=http://localhost:8000/admin)
  * **API Endpoint:** [http://localhost:8001](https://www.google.com/search?q=http://localhost:8001)

-----

## 💻 Cara Instalasi (Pilihan 2: Manual / Tanpa Docker)

Gunakan cara ini jika Anda menginstall di Hosting cPanel atau XAMPP/Laragon lokal.

**Syarat:** PHP \>= 8.1, Composer, MySQL.

### 1\. Persiapan Database

Buat database baru di MySQL (misal via phpMyAdmin) dengan nama `db_masjid`.

### 2\. Setup API Service (Lumen)

Buka terminal di folder `api-service`:

```bash
cd api-service
composer install
cp .env.example .env
```

Edit file `.env`, sesuaikan koneksi database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_masjid
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan API di port **8001**:

```bash
php -S localhost:8001 -t public
```

### 3\. Setup Admin Web (Laravel)

Buka terminal baru di folder `admin-web`:

```bash
cd admin-web
composer install
cp .env.example .env
```

Edit file `.env`, sesuaikan koneksi database (sama dengan API):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=db_masjid
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan perintah setup:

```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

Jalankan Admin di port **8000**:

```bash
php artisan serve --port=8000
```

-----

## ⚙️ Konfigurasi Penting

### Mengubah IP Address API

Secara default, Tampilan TV (`index.blade.php`) diatur untuk menghubungi API di `localhost:8001`.

Jika Anda memasang ini di jaringan lokal (agar bisa diakses HP lain), Anda perlu mengubah alamat IP di file `admin-web/resources/views/tv/index.blade.php`:

```javascript
// Ganti localhost dengan IP Komputer Server (misal: 192.168.1.10)
const API_URL = 'http://192.168.1.10:8001';
```

-----

## 🤝 Kontribusi

Silakan *fork* repository ini dan kirimkan *Pull Request* jika Anda memiliki ide fitur baru yang bermanfaat untuk kemakmuran masjid.

## 📄 Lisensi

Open Source (MIT License). Bebas digunakan dan dimodifikasi untuk kepentingan Masjid mana pun.

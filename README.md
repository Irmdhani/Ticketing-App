# Ticketing App

Aplikasi pemesanan tiket event sederhana yang dibangun menggunakan **Laravel** dan **Tailwind CSS** (dengan DaisyUI).

## Prasyarat Server (Requirements)

Sebelum memulai, pastikan komputer Anda telah terinstal software berikut:

* [PHP](https://www.php.net/) >= 8.2
* [Composer](https://getcomposer.org/)
* [Node.js](https://nodejs.org/) & NPM
* [MySQL](https://www.mysql.com/) atau MariaDB
* Git

## Cara Instalasi & Menjalankan Project

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan aplikasi di komputer lokal Anda.

### 1. Clone Repository

Unduh source code project ke komputer Anda.

```bash
git clone [https://github.com/username/ticketing-app.git](https://github.com/username/ticketing-app.git)
cd ticketing-app
2. Install Dependensi
```

Install library PHP (Laravel) dan library JavaScript (Tailwind/Vite).

Bash
# Install dependensi PHP
```
composer install
```

# Install dependensi JavaScript/Node
npm install
3. Konfigurasi Environment (.env)

Salin file konfigurasi contoh .env.example menjadi .env.

```Bash
cp .env.example .env
```
Buka file .env tersebut dengan text editor Anda, lalu sesuaikan konfigurasi database. Pastikan Anda sudah membuat database kosong di MySQL (misalnya bernama ticketing_db).
```
Ini, TOML
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ticketing_db
DB_USERNAME=root
DB_PASSWORD=
4. Generate Application Key
```

Buat kunci enkripsi aplikasi Laravel.

```Bash
php artisan key:generate
```
5. Setup Database (Migrasi & Seeder)

Jalankan migrasi untuk membuat tabel-tabel di database (seperti tabel orders, payment_types, events) dan isi dengan data dummy awal.

```Bash
# Perintah ini akan menghapus tabel lama (jika ada) dan membuatnya ulang beserta data dummy
php artisan migrate:fresh --seed
```
Catatan: Project ini menggunakan Seeder (DatabaseSeeder) untuk mengisi data awal seperti User Admin, Event contoh, Tiket, dan Tipe Pembayaran.

6. Link Storage (Penting untuk Gambar)

Aplikasi ini menyimpan gambar event di folder storage publik. Agar gambar bisa muncul di halaman depan, jalankan perintah ini:

```Bash
php artisan storage:link
```
7. Menjalankan Aplikasi

Anda perlu menjalankan dua terminal terpisah agar aplikasi berjalan dengan tampilan yang benar (Laravel + Vite).

Terminal 1 (Menjalankan Server Laravel):

```Bash
php artisan serve
```
Terminal 2 (Menjalankan Build Aset Frontend):

```Bash
npm run dev
```
Aplikasi sekarang dapat diakses melalui browser di: http://localhost:8000

Akun Login (Default Seeder)
Jika Anda menggunakan php artisan migrate:fresh --seed, akun default berikut biasanya tersedia (cek database/seeders/UserSeeder.php untuk memastikan):

Email: admin@gmail.com

Password: password

Struktur Fitur
Autentikasi: Login dan Register User (Breeze).

Event: Melihat daftar dan detail event.

Order: Pemesanan tiket dengan validasi stok.

Payment: Pemilihan metode pembayaran saat checkout.

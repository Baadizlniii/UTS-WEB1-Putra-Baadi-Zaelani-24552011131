# LaporWarga RW 01

**Sistem Informasi Pengaduan Masyarakat RW 01 Kelurahan Cibaduyut Wetan**

LaporWarga merupakan sistem informasi berbasis web yang dibuat untuk membantu masyarakat dalam menyampaikan pengaduan terkait lingkungan dan pelayanan masyarakat. Sistem ini mencakup wilayah RT 01, RT 02, dan RT 03 di RW 01, Kelurahan Cibaduyut Wetan, Kecamatan Bojongloa Kidul, Kota Bandung.

Repository ini terdiri dari dua bagian utama, yaitu website menggunakan PHP Native dan REST API CRUD yang terhubung dengan database MySQL yang sama.

## Teknologi yang Digunakan

- PHP Native dan MySQLi
- MySQL / MariaDB
- HTML5
- CSS3
- JavaScript
- Apache Web Server
- XAMPP atau Laragon
- Postman atau Bruno untuk pengujian REST API

## Fitur Aplikasi

### Project 1 — Website LaporWarga

Website digunakan oleh masyarakat untuk menyampaikan dan memantau pengaduan.

Fitur yang tersedia:

- **Beranda:** halaman utama aplikasi LaporWarga.
- **Registrasi:** pendaftaran akun masyarakat.
- **Login:** autentikasi pengguna.
- **Dashboard:** halaman utama setelah pengguna berhasil login.
- **Pengaduan:** formulir untuk menyampaikan laporan masyarakat.
- **Detail Pengaduan:** menampilkan informasi laporan berdasarkan ID.
- **Program Kerja:** informasi program kerja lingkungan.
- **Tentang Kami:** informasi mengenai aplikasi LaporWarga.
- **Logout:** mengakhiri sesi pengguna.

### Project 2 — REST API

REST API digunakan untuk mengelola data pengaduan melalui format JSON.

Operasi API yang tersedia:

- GET: menampilkan seluruh laporan atau detail laporan.
- POST: menambahkan laporan baru.
- PUT: memperbarui status laporan.
- DELETE: menghapus laporan.

## Struktur Repository

```text
UTS-WEB1-Putra-Baadi-Zaelani-24552011131/
|
|-- UTS WEB 1 Putra Baadi Zaelani 24552011131/
|   |
|   |-- project-1-website/
|   |   |-- assets/
|   |   |   |-- css/
|   |   |   `-- js/
|   |   |
|   |   |-- config/
|   |   |   `-- database.php
|   |   |
|   |   |-- includes/
|   |   |   |-- header.php
|   |   |   |-- navbar.php
|   |   |   `-- footer.php
|   |   |
|   |   |-- index.php
|   |   |-- detail.php
|   |   |-- login.php
|   |   |-- register.php
|   |   |-- dashboard.php
|   |   |-- pengaduan.php
|   |   |-- program.php
|   |   |-- tentang.php
|   |   `-- logout.php
|   |
|   |-- project-2-api/
|   |   |-- config/
|   |   |   `-- database.php
|   |   |
|   |   `-- api/
|   |       `-- laporan.php
|   |
|   |-- database.sql
|   `-- README.md
|
`-- README.md
```

## Database

Nama database yang digunakan:

`db_laporwarga`

Database terdiri dari dua tabel utama:

| Nama Tabel | Fungsi |
|---|---|
| `users` | Menyimpan data akun pengguna dan admin |
| `laporan` | Menyimpan data pengaduan masyarakat |

Relasi database:

`users.id` → `laporan.user_id`

Setiap laporan terhubung dengan akun pengguna yang membuat laporan tersebut.

File database tersedia pada:

`database.sql`

Database juga dilengkapi dengan beberapa data contoh untuk membantu proses pengujian aplikasi.

## Instalasi dan Menjalankan Aplikasi

### 1. Persiapan

Pastikan perangkat sudah memiliki:

- XAMPP atau Laragon.
- PHP.
- MySQL atau MariaDB.
- Browser.

### 2. Menempatkan Folder Proyek

Salin folder proyek ke direktori web server.

**XAMPP:**

```text
C:\xampp\htdocs\
```

**Laragon:**

```text
C:\laragon\www\
```

### 3. Menjalankan Server

Aktifkan layanan:

- Apache
- MySQL

### 4. Membuat Database

1. Buka phpMyAdmin melalui `http://localhost/phpmyadmin`.
2. Pilih menu **Import**.
3. Pilih file `database.sql` dari folder utama proyek.
4. Klik **Import** atau **Go**.
5. Pastikan database `db_laporwarga` berhasil dibuat.

### 5. Konfigurasi Database

Konfigurasi database terdapat pada:

- `project-1-website/config/database.php`
- `project-2-api/config/database.php`

Konfigurasi default:

| Konfigurasi | Nilai |
|---|---|
| Host | localhost |
| Username | root |
| Password | Kosong |
| Database | db_laporwarga |

Jika konfigurasi MySQL berbeda, sesuaikan kedua file konfigurasi tersebut.

### 6. Membuka Website

Jalankan aplikasi melalui browser:

```text
http://localhost/UTS%20WEB%201%20Putra%20Baadi%20Zaelani%2024552011131/project-1-website/
```

Sesuaikan alamat URL apabila nama folder proyek berbeda.

## Akun Pengujian

Database menyediakan akun contoh untuk menguji aplikasi.

| Role | Email | Password |
|---|---|---|
| Warga | putra@warga.id | password |
| Admin | admin@rw01.id | password |

Akun tersebut digunakan untuk keperluan pengujian lokal.

## REST API Documentation

REST API tersedia pada endpoint berikut:

```text
http://localhost/UTS%20WEB%201%20Putra%20Baadi%20Zaelani%2024552011131/project-2-api/api/laporan.php
```

### Daftar Endpoint

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/laporan.php` | Mengambil seluruh data laporan |
| GET | `/api/laporan.php?id=1` | Mengambil detail laporan |
| POST | `/api/laporan.php` | Menambahkan laporan baru |
| PUT | `/api/laporan.php` | Memperbarui status laporan |
| DELETE | `/api/laporan.php` | Menghapus laporan |

### Contoh Request POST

Digunakan untuk menambahkan laporan baru.

Header:

```text
Content-Type: application/json
```

Body:

```json
{
  "judul": "Jalan Berlubang di Depan Pos RW",
  "kategori": "Infrastruktur",
  "rt": "02",
  "deskripsi": "Aspal jalan berlubang cukup dalam, mohon segera diperbaiki."
}
```

### Contoh Request PUT

Digunakan untuk memperbarui status laporan.

```json
{
  "id": 1,
  "status": "Diproses"
}
```

Status laporan yang tersedia:

- Menunggu
- Diproses
- Selesai

### Contoh Request DELETE

Digunakan untuk menghapus laporan berdasarkan ID.

```json
{
  "id": 1
}
```

### Ketentuan Penggunaan API

- GET dapat digunakan untuk melihat data laporan.
- POST membutuhkan sesi login pengguna.
- PUT hanya dapat dilakukan oleh admin.
- DELETE hanya dapat dilakukan oleh admin.
- Request POST, PUT, dan DELETE menggunakan format JSON.
- Sesi login website digunakan untuk autentikasi operasi API yang memerlukannya.

Pengujian API dapat dilakukan menggunakan Postman atau Bruno.

## Dokumentasi Screenshot

Screenshot digunakan untuk memperlihatkan hasil implementasi aplikasi dan pengujian REST API.

### Website LaporWarga

Dokumentasikan halaman berikut:

1. Halaman Beranda
2. Halaman Login
3. Halaman Registrasi
4. Halaman Dashboard
5. Halaman Pengaduan
6. Halaman Detail Pengaduan
7. Halaman Program Kerja
8. Halaman Tentang Kami

### Pengujian REST API

Dokumentasikan hasil pengujian:

1. GET seluruh laporan.
2. GET detail laporan.
3. POST tambah laporan.
4. PUT ubah status laporan.
5. DELETE hapus laporan.

*Screenshot hasil pengujian ditambahkan setelah aplikasi dijalankan dan setiap endpoint diuji.*

## Identitas Pengembang

**Nama:** Putra Baadi Zaelani

**NIM:** 24552011131

**Kelas:** TIF RP 24 E CNS

**Program Studi:** Teknik Informatika

**Mata Kuliah:** Pemrograman Web 1

**Universitas:** Universitas Teknologi Bandung

---

© 2026 LaporWarga RW 01 — Project Ujian Tengah Semester Pemrograman Web 1.

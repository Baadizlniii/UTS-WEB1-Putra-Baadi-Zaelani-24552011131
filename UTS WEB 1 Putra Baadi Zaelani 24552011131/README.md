# LaporWarga RW 01 — Sistem Pengaduan Masyarakat
**Kelurahan Cibaduyut Wetan (RT 01, RT 02, RT 03), Kec. Bojongloa Kidul, Kota Bandung**

- **Nama:** Putra Baadi Zaelani
- **NPM:** 24552011131
- **Kelas:** TIF RP 24 E CNS
- **Mata Kuliah:** Pemrograman Web 1

## Cara Menjalankan
1. Pindahkan folder `UTS WEB 1 Putra Baadi Zaelani 24552011131` ke dalam folder `htdocs` (XAMPP) atau `www` (Laragon).
2. Buka `http://localhost/phpmyadmin`, buat database bernama **`db_laporwarga`**, lalu import file **`database.sql`**.
3. Buka di browser:
   - **Website:** `http://localhost/UTS%20WEB%201%20Putra%20Baadi%20Zaelani%2024552011131/project-1-website/`
   - **REST API:** `http://localhost/UTS%20WEB%201%20Putra%20Baadi%20Zaelani%2024552011131/project-2-api/api/laporan.php`

## Akun Uji Coba
- **Akun Warga:** `putra@warga.id` | **Password:** `password`
- **Akun Admin:** `admin@rw01.id` | **Password:** `password`

> Kedua akun uji coba menggunakan password `password` sesuai hash yang tersimpan di `database.sql`. Ganti password akun contoh sebelum website digunakan secara publik.


## Catatan REST API
- `GET /project-2-api/api/laporan.php` menampilkan daftar laporan.
- `GET /project-2-api/api/laporan.php?id=1` menampilkan detail laporan.
- `POST` hanya dapat digunakan setelah login; `user_id` diambil dari session login.
- `PUT` untuk mengubah status dan `DELETE` untuk menghapus laporan hanya dapat dilakukan oleh akun admin.
- Untuk menguji `POST`, `PUT`, dan `DELETE`, gunakan klien API yang menyimpan cookie session dari proses login website.


## Screenshot Hasil Project 1 (Website)
> *Ganti bagian ini dengan screenshot tampilan website setelah dijalankan di browser:*
- **Halaman Utama (`index.php`):** `![Halaman Utama](screenshots/halaman-utama.png)`
- **Halaman Detail (`detail.php`):** `![Halaman Detail](screenshots/halaman-detail.png)`
- **Halaman Login & Registrasi (`login.php` / `register.php`):** `![Halaman Login](screenshots/halaman-login.png)`
- **Halaman Menu Utama / Dashboard (`dashboard.php`):** `![Dashboard](screenshots/dashboard.png)`

## Screenshot Hasil Uji CRUD API Project 2 (Postman / Bruno)
> *Ganti bagian ini dengan screenshot hasil pengujian endpoint `/project-2-api/api/laporan.php`:*
- **GET (Read Data Laporan):** `![GET API](screenshots/api-get.png)`
- **POST (Create Laporan Baru):** `![POST API](screenshots/api-post.png)`
- **PUT (Update Status Laporan):** `![PUT API](screenshots/api-put.png)`
- **DELETE (Hapus Laporan):** `![DELETE API](screenshots/api-delete.png)`

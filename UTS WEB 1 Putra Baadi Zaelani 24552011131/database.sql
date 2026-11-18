-- Database LaporWarga RW 01 Cibaduyut Wetan
CREATE DATABASE IF NOT EXISTS db_laporwarga
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_laporwarga;

-- Tabel pengguna
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rt ENUM('01', '02', '03') NOT NULL DEFAULT '01',
    role ENUM('warga', 'admin') NOT NULL DEFAULT 'warga',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel pengaduan
CREATE TABLE IF NOT EXISTS laporan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    judul VARCHAR(150) NOT NULL,
    kategori ENUM('Infrastruktur', 'Kebersihan', 'Keamanan', 'Sosial', 'Lainnya') NOT NULL,
    rt ENUM('01', '02', '03') NOT NULL,
    deskripsi TEXT NOT NULL,
    status ENUM('Menunggu', 'Diproses', 'Selesai') NOT NULL DEFAULT 'Menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_laporan_user
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Akun contoh. Password kedua akun: password
-- Hash dibuat menggunakan password_hash('password', PASSWORD_DEFAULT).
INSERT IGNORE INTO users (nama, email, password, rt, role) VALUES
('H. Asep Saepudin (Ketua RW 01)', 'admin@rw01.id',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01', 'admin'),
('Putra Baadi Zaelani', 'putra@warga.id',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '02', 'warga');

-- Data contoh pengaduan (tidak menggandakan data saat SQL diimpor ulang).
INSERT INTO laporan (user_id, judul, kategori, rt, deskripsi, status)
SELECT u.id, 'Saluran Air Tersumbat di Gang H. Kurdi', 'Infrastruktur', '02',
       'Saluran air di sekitar RT 02 mengalami penyumbatan akibat tumpukan sampah plastik sehingga menyebabkan genangan saat hujan deras.',
       'Diproses'
FROM users u
WHERE u.email = 'putra@warga.id'
  AND NOT EXISTS (
      SELECT 1 FROM laporan l
      WHERE l.judul = 'Saluran Air Tersumbat di Gang H. Kurdi'
  );

INSERT INTO laporan (user_id, judul, kategori, rt, deskripsi, status)
SELECT u.id, 'Lampu Penerangan Jalan Padam', 'Infrastruktur', '01',
       'Lampu PJU di pertigaan jalan utama RT 01 mati total sejak 3 hari lalu sehingga kondisi jalan gelap pada malam hari.',
       'Selesai'
FROM users u
WHERE u.email = 'putra@warga.id'
  AND NOT EXISTS (
      SELECT 1 FROM laporan l
      WHERE l.judul = 'Lampu Penerangan Jalan Padam'
  );

INSERT INTO laporan (user_id, judul, kategori, rt, deskripsi, status)
SELECT u.id, 'Penumpukan Sampah Dekat Pos Ronda', 'Kebersihan', '03',
       'Mohon penertiban dan pengangkutan sampah di sekitar pos kamling RT 03 karena sudah menumpuk selama 2 hari.',
       'Menunggu'
FROM users u
WHERE u.email = 'putra@warga.id'
  AND NOT EXISTS (
      SELECT 1 FROM laporan l
      WHERE l.judul = 'Penumpukan Sampah Dekat Pos Ronda'
  );

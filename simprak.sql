-- Membuat database jika belum ada dan mengaturnya
CREATE DATABASE IF NOT EXISTS simpraktikum CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE simpraktikum;

-- Menghapus tabel lama jika ada untuk menghindari error (opsional, baik untuk testing)
DROP TABLE IF EXISTS `laporan`, `pendaftaran_praktikum`, `modul`, `mata_praktikum`, `users`;

--
-- Struktur dan Data untuk tabel `users`
--
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','asisten') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan data pengguna
-- Password asisten: asisten123
-- Password mahasiswa: mahasiswa123
INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `password`, `role`) VALUES
(1, 'Admin Asisten', 'asisten', '$2y$10$mRt2EYN5sQ25HUAtHFj6OO6JDDTDTy7gNd/4FZduYvglSP/obQAXq', 'asisten'),
(2, 'Budi Sanjaya', 'budi', '$2y$10$Xg8WD9mRN4YMFFvQha8o6eNzh5J9n73itPIuPnPXYoq5ILCPtaTOK', 'mahasiswa'),
(3, 'Cinta Lestari', 'cinta', '$2y$10$Xg8WD9mRN4YMFFvQha8o6eNzh5J9n73itPIuPnPXYoq5ILCPtaTOK', 'mahasiswa');

--
-- Struktur dan Data untuk tabel `mata_praktikum`
--
CREATE TABLE `mata_praktikum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_praktikum` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan data mata praktikum
INSERT INTO `mata_praktikum` (`id`, `nama_praktikum`, `deskripsi`) VALUES
(1, 'Pemrograman Web', 'Membahas konsep dasar pengembangan web dengan HTML, CSS, PHP, dan MySQL.'),
(2, 'Jaringan Komputer', 'Praktikum untuk mempelajari konfigurasi dan manajemen jaringan dasar.'),
(3, 'Basis Data', 'Praktikum mendalam mengenai desain, implementasi, dan kueri database relasional.');

--
-- Struktur dan Data untuk tabel `modul`
--
CREATE TABLE `modul` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `praktikum_id` int(11) NOT NULL,
  `judul_modul` varchar(255) NOT NULL,
  `deskripsi_modul` text DEFAULT NULL,
  `file_materi` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `praktikum_id` (`praktikum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan data modul
INSERT INTO `modul` (`id`, `praktikum_id`, `judul_modul`, `deskripsi_modul`, `file_materi`) VALUES
(1, 1, 'Modul 1: Pengenalan HTML & CSS', 'Dasar-dasar struktur halaman web dan styling.', 'modul-1-web.pdf'),
(2, 1, 'Modul 2: PHP Dasar', 'Variabel, tipe data, dan struktur kontrol pada PHP.', 'modul-2-web.pdf'),
(3, 1, 'Modul 3: Koneksi Database', 'Menghubungkan aplikasi PHP dengan database MySQL.', NULL),
(4, 2, 'Modul 1: Pengalamatan IP', 'Mempelajari subnetting dan supernetting.', 'modul-1-jarkom.pdf'),
(5, 2, 'Modul 2: Konfigurasi Router', 'Konfigurasi dasar pada perangkat router.', NULL);

--
-- Struktur dan Data untuk tabel `pendaftaran_praktikum`
--
CREATE TABLE `pendaftaran_praktikum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` int(11) NOT NULL,
  `praktikum_id` int(11) NOT NULL,
  `tanggal_pendaftaran` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `mahasiswa_praktikum` (`mahasiswa_id`,`praktikum_id`),
  KEY `praktikum_id` (`praktikum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan data pendaftaran
INSERT INTO `pendaftaran_praktikum` (`mahasiswa_id`, `praktikum_id`) VALUES
(2, 1), -- Budi mendaftar Pemrograman Web
(2, 2), -- Budi mendaftar Jaringan Komputer
(3, 1); -- Cinta mendaftar Pemrograman Web

--
-- Struktur dan Data untuk tabel `laporan`
--
CREATE TABLE `laporan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modul_id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `file_laporan` varchar(255) NOT NULL,
  `tanggal_pengumpulan` timestamp NOT NULL DEFAULT current_timestamp(),
  `nilai` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `status` enum('dikumpulkan','dinilai') NOT NULL DEFAULT 'dikumpulkan',
  PRIMARY KEY (`id`),
  KEY `modul_id` (`modul_id`),
  KEY `mahasiswa_id` (`mahasiswa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan data laporan contoh
INSERT INTO `laporan` (`modul_id`, `mahasiswa_id`, `file_laporan`, `nilai`, `feedback`, `status`) VALUES
(1, 2, 'laporan_budi_modul1.pdf', 90, 'Kerja bagus! Penjelasan mengenai tag semantik sudah sangat lengkap.', 'dinilai'),
(2, 2, 'laporan_budi_modul2.pdf', NULL, NULL, 'dikumpulkan'),
(1, 3, 'laporan_cinta_modul1.pdf', NULL, NULL, 'dikumpulkan');

-- Menambahkan Foreign Key Constraints
ALTER TABLE `modul`
  ADD CONSTRAINT `modul_ibfk_1` FOREIGN KEY (`praktikum_id`) REFERENCES `mata_praktikum` (`id`) ON DELETE CASCADE;

ALTER TABLE `pendaftaran_praktikum`
  ADD CONSTRAINT `pendaftaran_praktikum_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendaftaran_praktikum_ibfk_2` FOREIGN KEY (`praktikum_id`) REFERENCES `mata_praktikum` (`id`) ON DELETE CASCADE;

ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`modul_id`) REFERENCES `modul` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `laporan_ibfk_2` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
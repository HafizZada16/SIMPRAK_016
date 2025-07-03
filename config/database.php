<?php
// Pengaturan koneksi database
$db_host = 'localhost'; // atau sesuaikan dengan host Anda
$db_user = 'root';      // atau sesuaikan dengan user database Anda
$db_pass = '';          // atau sesuaikan dengan password database Anda
$db_name = 'simpraktikum';

// Membuat koneksi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Mengatur sesi untuk autentikasi
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
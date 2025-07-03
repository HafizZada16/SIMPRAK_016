<?php
// File ini hanya untuk membuat hash password baru.
// Setelah digunakan, file ini bisa dihapus.

$password_untuk_dihash = 'mahasiswa123';

$hash_baru = password_hash($password_untuk_dihash, PASSWORD_DEFAULT);

echo "<h3>Hash Password Baru</h3>";
echo "<p>Silakan salin (copy) seluruh teks di bawah ini dan tempelkan ke kolom 'password' di database untuk pengguna 'asisten'.</p>";
echo "<hr>";
echo "<textarea rows='3' cols='80' readonly>" . htmlspecialchars($hash_baru) . "</textarea>";

?>
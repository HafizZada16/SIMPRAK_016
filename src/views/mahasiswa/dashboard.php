<?php
require_once __DIR__ . '/../../../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: index.php?page=login");
    exit();
}

$page_title = 'Dasbor Mahasiswa';
include_once __DIR__ . '/../templates/header.php';

// Ambil data praktikum yang diikuti oleh mahasiswa
$mahasiswa_id = $_SESSION['user_id'];
$query = "
    SELECT mp.id, mp.nama_praktikum, mp.deskripsi 
    FROM mata_praktikum mp
    JOIN pendaftaran_praktikum pp ON mp.id = pp.praktikum_id
    WHERE pp.mahasiswa_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $mahasiswa_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="px-4 py-6 sm:px-0">
    <h1 class="text-2xl font-bold text-gray-900 mb-4">
        Selamat Datang, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>!
    </h1>
    
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-800">Praktikum Saya</h2>
        <div class="mt-4 space-y-4">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="bg-white p-6 rounded-lg shadow-md flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800"><?= htmlspecialchars($row['nama_praktikum']) ?></h3>
                        <p class="text-gray-600"><?= htmlspecialchars($row['deskripsi']) ?></p>
                    </div>
                    <a href="index.php?page=detail_praktikum&id=<?= $row['id'] ?>" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600">
                        Lihat Detail
                    </a>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="bg-white p-4 rounded-lg shadow">
                    <p class="text-gray-500">Anda belum mengikuti praktikum apapun. Silakan cari praktikum yang tersedia.</p>
                    <a href="index.php?page=cari_praktikum" class="mt-2 inline-block text-indigo-600 hover:underline">Cari Praktikum</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
$stmt->close();
include_once __DIR__ . '/../templates/footer.php'; 
?>
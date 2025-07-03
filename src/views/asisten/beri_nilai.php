<?php
require_once __DIR__ . '/../../../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten' || !isset($_GET['id'])) {
    header("Location: index.php?page=login");
    exit();
}
$page_title = 'Beri Nilai Laporan';
include_once __DIR__ . '/../templates/header.php';

// Ambil detail laporan
$laporan_id = $_GET['id'];
$query = "
    SELECT 
        l.*,
        u.nama_lengkap AS nama_mahasiswa,
        m.judul_modul,
        mp.nama_praktikum
    FROM laporan l
    JOIN users u ON l.mahasiswa_id = u.id
    JOIN modul m ON l.modul_id = m.id
    JOIN mata_praktikum mp ON m.praktikum_id = mp.id
    WHERE l.id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $laporan_id);
$stmt->execute();
$laporan = $stmt->get_result()->fetch_assoc();
if (!$laporan) { exit('Laporan tidak ditemukan.'); }
?>

<div class="px-4 py-6 sm:px-0">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Periksa Laporan</h1>
        <a href="index.php?page=periksa_laporan" class="text-indigo-600 hover:underline">&larr; Kembali ke Daftar Laporan</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-1 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Detail Pengumpulan</h2>
            <div class="space-y-3">
                <p><strong>Mahasiswa:</strong><br><?= htmlspecialchars($laporan['nama_mahasiswa']) ?></p>
                <p><strong>Praktikum:</strong><br><?= htmlspecialchars($laporan['nama_praktikum']) ?></p>
                <p><strong>Modul:</strong><br><?= htmlspecialchars($laporan['judul_modul']) ?></p>
                <p><strong>Dikumpulkan pada:</strong><br><?= date('d M Y, H:i', strtotime($laporan['tanggal_pengumpulan'])) ?></p>
                <hr>
                <a href="public/uploads/laporan/<?= htmlspecialchars($laporan['file_laporan']) ?>" download class="w-full text-center inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    Unduh File Laporan
                </a>
            </div>
        </div>
        
        <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Form Penilaian</h2>
            <form action="index.php?action=beri_nilai" method="POST">
                <input type="hidden" name="laporan_id" value="<?= $laporan_id ?>">
                <div class="mb-4">
                    <label for="nilai" class="block text-sm font-medium text-gray-700">Nilai (Angka)</label>
                    <input type="number" name="nilai" id="nilai" min="0" max="100" value="<?= htmlspecialchars($laporan['nilai'] ?? '') ?>" required
                           class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="feedback" class="block text-sm font-medium text-gray-700">Feedback (Teks)</label>
                    <textarea name="feedback" id="feedback" rows="5" required
                              class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm"><?= htmlspecialchars($laporan['feedback'] ?? '') ?></textarea>
                </div>
                <div>
                    <button type="submit" class="px-6 py-2 font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                        Simpan Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
$stmt->close();
include_once __DIR__ . '/../templates/footer.php'; 
?>
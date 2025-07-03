<?php
// Proteksi halaman dan ambil data
require_once __DIR__ . '/../../../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa' || !isset($_GET['id'])) {
    header("Location: index.php?page=login");
    exit();
}

$mahasiswa_id = $_SESSION['user_id'];
$praktikum_id = $_GET['id'];

// Ambil info praktikum
$praktikum_stmt = $conn->prepare("SELECT nama_praktikum FROM mata_praktikum WHERE id = ?");
$praktikum_stmt->bind_param("i", $praktikum_id);
$praktikum_stmt->execute();
$praktikum_result = $praktikum_stmt->get_result();
$praktikum = $praktikum_result->fetch_assoc();
if (!$praktikum) {
    echo "Praktikum tidak ditemukan.";
    exit();
}
$page_title = 'Detail Praktikum: ' . htmlspecialchars($praktikum['nama_praktikum']);

// Ambil semua modul untuk praktikum ini & status laporan mahasiswa
$query_modul = "
    SELECT 
        m.id, 
        m.judul_modul, 
        m.deskripsi_modul, 
        m.file_materi,
        l.file_laporan,
        l.tanggal_pengumpulan,
        l.nilai,
        l.feedback
    FROM modul m
    LEFT JOIN laporan l ON m.id = l.modul_id AND l.mahasiswa_id = ?
    WHERE m.praktikum_id = ?
    ORDER BY m.id ASC
";
$modul_stmt = $conn->prepare($query_modul);
$modul_stmt->bind_param("ii", $mahasiswa_id, $praktikum_id);
$modul_stmt->execute();
$modul_result = $modul_stmt->get_result();

include_once __DIR__ . '/../templates/header.php';
?>

<div class="px-4 py-6 sm:px-0">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($praktikum['nama_praktikum']) ?></h1>
            <p class="text-gray-600">Berikut adalah daftar modul, materi, dan pengumpulan laporan Anda.</p>
        </div>
        <a href="index.php?page=mahasiswa_dashboard" class="text-indigo-600 hover:underline">&larr; Kembali ke Dasbor</a>
    </div>

    <div class="space-y-6">
        <?php if ($modul_result->num_rows > 0): ?>
            <?php while($modul = $modul_result->fetch_assoc()): ?>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold text-gray-800"><?= htmlspecialchars($modul['judul_modul']) ?></h2>
                <p class="mt-1 text-gray-600"><?= htmlspecialchars($modul['deskripsi_modul']) ?></p>

                <div class="mt-4 border-t pt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <div>
                        <h3 class="font-semibold text-gray-700">Materi Praktikum</h3>
                        <?php if (!empty($modul['file_materi'])): ?>
                            <a href="public/uploads/materi/<?= htmlspecialchars($modul['file_materi']) ?>" download class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                Unduh Materi
                            </a>
                        <?php else: ?>
                            <p class="text-sm text-gray-500 mt-2">Materi belum tersedia.</p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-700">Pengumpulan Laporan</h3>
                        <?php if (empty($modul['file_laporan'])): ?>
                            <p class="text-sm text-red-500 mt-2">Anda belum mengumpulkan laporan.</p>
                             <form action="index.php?action=kumpul_laporan" method="POST" enctype="multipart/form-data" class="mt-2">
                                <input type="hidden" name="modul_id" value="<?= $modul['id'] ?>">
                                <input type="file" name="file_laporan" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
                                <button type="submit" class="mt-2 w-full bg-blue-500 text-white px-3 py-1.5 rounded-md hover:bg-blue-600 text-sm">Unggah Laporan</button>
                            </form>
                        <?php else: ?>
                            <p class="text-sm text-green-600 mt-2">
                                Terkumpul pada: <?= date('d M Y, H:i', strtotime($modul['tanggal_pengumpulan'])) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-700">Nilai & Feedback</h3>
                        <?php if (!empty($modul['nilai'])): ?>
                            <p class="text-4xl font-bold text-indigo-600 mt-2"><?= htmlspecialchars($modul['nilai']) ?></p>
                            <p class="text-sm text-gray-700 mt-1"><strong>Feedback:</strong> <?= htmlspecialchars($modul['feedback']) ?></p>
                        <?php else: ?>
                            <p class="text-sm text-gray-500 mt-2">Laporan belum dinilai oleh asisten.</p>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-gray-500">Modul untuk praktikum ini belum ditambahkan oleh Asisten.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
$praktikum_stmt->close();
$modul_stmt->close();
include_once __DIR__ . '/../templates/footer.php'; 
?>
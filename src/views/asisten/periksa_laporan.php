<?php
require_once __DIR__ . '/../../../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: index.php?page=login");
    exit();
}

$page_title = 'Periksa Laporan Masuk';
include_once __DIR__ . '/../templates/header.php';

// Logika Filter
$where_clauses = [];
$params = [];
$types = '';

if (!empty($_GET['praktikum_id'])) {
    $where_clauses[] = 'mp.id = ?';
    $params[] = $_GET['praktikum_id'];
    $types .= 'i';
}
if (!empty($_GET['status'])) {
    $where_clauses[] = 'l.status = ?';
    $params[] = $_GET['status'];
    $types .= 's';
}
$where_sql = count($where_clauses) > 0 ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

// Query untuk mengambil laporan
$query = "
    SELECT 
        l.id, l.tanggal_pengumpulan, l.status, l.nilai,
        u.nama_lengkap AS nama_mahasiswa,
        m.judul_modul,
        mp.nama_praktikum
    FROM laporan l
    JOIN users u ON l.mahasiswa_id = u.id
    JOIN modul m ON l.modul_id = m.id
    JOIN mata_praktikum mp ON m.praktikum_id = mp.id
    $where_sql
    ORDER BY l.tanggal_pengumpulan DESC
";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Data untuk filter dropdown
$praktikum_list = $conn->query("SELECT id, nama_praktikum FROM mata_praktikum ORDER BY nama_praktikum");
?>

<div class="px-4 py-6 sm:px-0">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Laporan Masuk</h1>
        <a href="index.php?page=asisten_dashboard" class="text-indigo-600 hover:underline">&larr; Kembali ke Dasbor</a>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-md mb-8">
        <form method="GET" action="index.php">
            <input type="hidden" name="page" value="periksa_laporan">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="praktikum_id" class="block text-sm font-medium text-gray-700">Filter by Praktikum</label>
                    <select name="praktikum_id" id="praktikum_id" class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 rounded-md">
                        <option value="">Semua Praktikum</option>
                        <?php while ($prak = $praktikum_list->fetch_assoc()): ?>
                            <option value="<?= $prak['id'] ?>" <?= (($_GET['praktikum_id'] ?? '') == $prak['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($prak['nama_praktikum']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Filter by Status</label>
                    <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 rounded-md">
                        <option value="">Semua Status</option>
                        <option value="dikumpulkan" <?= (($_GET['status'] ?? '') == 'dikumpulkan') ? 'selected' : '' ?>>Belum Dinilai</option>
                        <option value="dinilai" <?= (($_GET['status'] ?? '') == 'dinilai') ? 'selected' : '' ?>>Sudah Dinilai</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Filter</button>
                    <a href="index.php?page=periksa_laporan" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mahasiswa</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Praktikum / Modul</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Kumpul</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="px-4 py-4"><?= htmlspecialchars($row['nama_mahasiswa']) ?></td>
                        <td class="px-4 py-4">
                            <span class="font-bold"><?= htmlspecialchars($row['nama_praktikum']) ?></span><br>
                            <span class="text-sm text-gray-600"><?= htmlspecialchars($row['judul_modul']) ?></span>
                        </td>
                        <td class="px-4 py-4"><?= date('d M Y, H:i', strtotime($row['tanggal_pengumpulan'])) ?></td>
                        <td class="px-4 py-4">
                            <?php if ($row['status'] == 'dinilai'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Dinilai (<?= $row['nilai'] ?>)</span>
                            <?php else: ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Dikumpulkan</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4">
                            <a href="index.php?page=beri_nilai&id=<?= $row['id'] ?>" class="text-indigo-600 hover:text-indigo-900">Periksa & Nilai</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">Tidak ada laporan yang sesuai dengan filter.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$stmt->close();
include_once __DIR__ . '/../templates/footer.php'; 
?>
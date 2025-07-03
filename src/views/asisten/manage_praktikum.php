<?php
require_once __DIR__ . '/../../../config/database.php';
// Proteksi halaman, hanya untuk asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: index.php?page=login");
    exit();
}
$page_title = 'Kelola Mata Praktikum';
include_once __DIR__ . '/../templates/header.php';

// Ambil semua data praktikum untuk ditampilkan
$result = $conn->query("SELECT * FROM mata_praktikum ORDER BY id DESC");
?>

<div class="px-4 py-6 sm:px-0">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Kelola Mata Praktikum</h1>
        <a href="index.php?page=asisten_dashboard" class="text-indigo-600 hover:underline">&larr; Kembali ke Dasbor</a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 id="form-title" class="text-2xl font-semibold text-gray-800 mb-4">Tambah Praktikum Baru</h2>
        <form id="praktikum-form" action="index.php" method="POST">
            <input type="hidden" name="action" id="form-action" value="add_praktikum">
            <input type="hidden" name="id" id="praktikum-id">
            
            <div class="mb-4">
                <label for="nama_praktikum" class="block text-sm font-medium text-gray-700">Nama Praktikum</label>
                <input type="text" name="nama_praktikum" id="nama_praktikum" required class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" required class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <div class="flex items-center space-x-4">
                <button type="submit" id="submit-button" class="px-4 py-2 font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Simpan</button>
                <button type="button" id="cancel-button" onclick="resetForm()" class="px-4 py-2 font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300" style="display: none;">Batal</button>
            </div>
        </form>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Daftar Mata Praktikum</h2>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Praktikum</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['nama_praktikum']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($row['deskripsi']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="editForm(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['nama_praktikum'])) ?>', '<?= htmlspecialchars(addslashes($row['deskripsi'])) ?>')" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            <a href="index.php?action=delete_praktikum&id=<?= $row['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="text-red-600 hover:text-red-900 ml-4">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada data mata praktikum.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function editForm(id, nama, deskripsi) {
    document.getElementById('form-title').innerText = 'Ubah Data Praktikum';
    document.getElementById('form-action').value = 'update_praktikum';
    document.getElementById('praktikum-id').value = id;
    document.getElementById('nama_praktikum').value = nama;
    document.getElementById('deskripsi').value = deskripsi;
    document.getElementById('submit-button').innerText = 'Update';
    document.getElementById('cancel-button').style.display = 'inline-block';
    window.scrollTo(0, 0); // Gulir ke atas halaman
}

function resetForm() {
    document.getElementById('form-title').innerText = 'Tambah Praktikum Baru';
    document.getElementById('praktikum-form').reset();
    document.getElementById('form-action').value = 'add_praktikum';
    document.getElementById('praktikum-id').value = '';
    document.getElementById('submit-button').innerText = 'Simpan';
    document.getElementById('cancel-button').style.display = 'none';
}
</script>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
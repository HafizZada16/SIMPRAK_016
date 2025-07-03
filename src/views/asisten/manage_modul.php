<?php
require_once __DIR__ . '/../../../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: index.php?page=login");
    exit();
}

// Ambil daftar praktikum untuk dropdown
$list_praktikum = $conn->query("SELECT id, nama_praktikum FROM mata_praktikum ORDER BY nama_praktikum ASC");

$selected_praktikum_id = $_GET['praktikum_id'] ?? null;
if (!$selected_praktikum_id && $list_praktikum->num_rows > 0) {
    $first_prak = $list_praktikum->fetch_assoc();
    $selected_praktikum_id = $first_prak['id'];
    $list_praktikum->data_seek(0);
}

$nama_praktikum_terpilih = 'Pilih Praktikum';
$modul_result = null;

if ($selected_praktikum_id) {
    // Ambil detail praktikum yang dipilih
    $stmt_nama = $conn->prepare("SELECT nama_praktikum FROM mata_praktikum WHERE id = ?");
    $stmt_nama->bind_param("i", $selected_praktikum_id);
    $stmt_nama->execute();
    $nama_praktikum_terpilih = $stmt_nama->get_result()->fetch_assoc()['nama_praktikum'];
    $stmt_nama->close();

    // Ambil modul dari praktikum yang dipilih
    $stmt_modul = $conn->prepare("SELECT * FROM modul WHERE praktikum_id = ? ORDER BY id ASC");
    $stmt_modul->bind_param("i", $selected_praktikum_id);
    $stmt_modul->execute();
    $modul_result = $stmt_modul->get_result();
}

$page_title = 'Kelola Modul: ' . htmlspecialchars($nama_praktikum_terpilih);
include_once __DIR__ . '/../templates/header.php';
?>

<div class="px-4 py-6 sm:px-0">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Kelola Modul & Materi</h1>
        <a href="index.php?page=asisten_dashboard" class="text-indigo-600 hover:underline">&larr; Kembali ke Dasbor</a>
    </div>

    <div class="mb-6">
        <label for="pilih_praktikum" class="block text-sm font-medium text-gray-700">Pilih Mata Praktikum untuk Dikelola:</label>
        <select name="pilih_praktikum" id="pilih_praktikum" onchange="window.location.href=this.value"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            <option disabled <?= !$selected_praktikum_id ? 'selected' : '' ?>>-- Pilih Praktikum --</option>
            <?php while ($prak = $list_praktikum->fetch_assoc()): ?>
                <option value="index.php?page=manage_modul&praktikum_id=<?= $prak['id'] ?>" <?= ($prak['id'] == $selected_praktikum_id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($prak['nama_praktikum']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <?php if ($selected_praktikum_id): ?>
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 id="form-title-modul" class="text-2xl font-semibold text-gray-800 mb-4">Tambah Modul Baru</h2>
            <form id="modul-form" action="index.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" id="form-action-modul" value="add_modul">
                <input type="hidden" name="praktikum_id" value="<?= $selected_praktikum_id ?>">
                <input type="hidden" name="modul_id" id="modul-id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="judul_modul" class="block text-sm font-medium text-gray-700">Judul Modul</label>
                        <input type="text" name="judul_modul" id="judul_modul" required class="mt-1 w-full px-3 py-2 border rounded-md">
                    </div>
                    <div>
                        <label for="file_materi" class="block text-sm font-medium text-gray-700">File Materi (PDF/DOCX)</label>
                        <input type="file" name="file_materi" class="mt-1 w-full text-sm text-gray-500 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-50 file:text-indigo-700">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah file materi.</p>
                    </div>
                    <div class="md:col-span-2">
                        <label for="deskripsi_modul" class="block text-sm font-medium text-gray-700">Deskripsi Singkat</label>
                        <textarea name="deskripsi_modul" id="deskripsi_modul" rows="2" class="mt-1 w-full px-3 py-2 border rounded-md"></textarea>
                    </div>
                </div>
                <div class="mt-4 flex items-center space-x-4">
                    <button type="submit" id="submit-button-modul" class="px-4 py-2 font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Tambah Modul</button>
                    <button type="button" id="cancel-button-modul" onclick="resetModulForm()" class="px-4 py-2 font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300" style="display: none;">Batal</button>
                </div>
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Daftar Modul</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">File Materi</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($modul_result && $modul_result->num_rows > 0): ?>
                            <?php while($modul = $modul_result->fetch_assoc()): ?>
                            <tr>
                                <td class="px-4 py-4"><?= htmlspecialchars($modul['judul_modul']) ?></td>
                                <td class="px-4 py-4">
                                    <?php if(!empty($modul['file_materi'])): ?>
                                        <a href="uploads/materi/<?= htmlspecialchars($modul['file_materi']) ?>" target="_blank" class="text-indigo-600 hover:underline"><?= htmlspecialchars($modul['file_materi']) ?></a>
                                    <?php else: ?>
                                        <span class="text-gray-400">Tidak ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <button onclick="editModulForm(<?= $modul['id'] ?>, '<?= htmlspecialchars(addslashes($modul['judul_modul'])) ?>', '<?= htmlspecialchars(addslashes($modul['deskripsi_modul'])) ?>')" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
                                    <a href="index.php?action=delete_modul&id=<?= $modul['id'] ?>" onclick="return confirm('Yakin ingin menghapus modul ini?')" class="text-red-600 hover:text-red-900">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="px-4 py-4 text-center text-gray-500">Belum ada modul untuk praktikum ini.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function editModulForm(id, judul, deskripsi) {
    document.getElementById('form-title-modul').innerText = 'Ubah Data Modul';
    document.getElementById('form-action-modul').value = 'update_modul';
    document.getElementById('modul-id').value = id;
    document.getElementById('judul_modul').value = judul;
    document.getElementById('deskripsi_modul').value = deskripsi;
    document.getElementById('submit-button-modul').innerText = 'Update Modul';
    document.getElementById('cancel-button-modul').style.display = 'inline-block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetModulForm() {
    document.getElementById('form-title-modul').innerText = 'Tambah Modul Baru';
    document.getElementById('modul-form').reset();
    document.getElementById('form-action-modul').value = 'add_modul';
    document.getElementById('modul-id').value = '';
    document.getElementById('submit-button-modul').innerText = 'Tambah Modul';
    document.getElementById('cancel-button-modul').style.display = 'none';
}
</script>

<?php 
if(isset($stmt_modul)) $stmt_modul->close();
include_once __DIR__ . '/../templates/footer.php'; 
?>
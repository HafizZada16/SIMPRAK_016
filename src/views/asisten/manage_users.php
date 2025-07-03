<?php
require_once __DIR__ . '/../../../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: index.php?page=login");
    exit();
}
$page_title = 'Kelola Akun Pengguna';
include_once __DIR__ . '/../templates/header.php';

// Ambil semua data pengguna untuk ditampilkan
$result = $conn->query("SELECT id, nama_lengkap, username, role FROM users ORDER BY created_at DESC");
?>

<div class="px-4 py-6 sm:px-0">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Kelola Akun Pengguna</h1>
        <a href="index.php?page=asisten_dashboard" class="text-indigo-600 hover:underline">&larr; Kembali ke Dasbor</a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 id="form-title-user" class="text-2xl font-semibold text-gray-800 mb-4">Tambah Pengguna Baru</h2>
        <form id="user-form" action="index.php" method="POST">
            <input type="hidden" name="action" id="form-action-user" value="add_user">
            <input type="hidden" name="id" id="user-id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" required class="w-full px-3 py-2 mt-1 border rounded-md">
                </div>
                 <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" required class="w-full px-3 py-2 mt-1 border rounded-md">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="w-full px-3 py-2 mt-1 border rounded-md" placeholder="Kosongkan jika tidak ingin diubah">
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role" required class="w-full px-3 py-2 mt-1 border rounded-md">
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="asisten">Asisten</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex items-center space-x-4">
                <button type="submit" id="submit-button-user" class="px-4 py-2 font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Simpan</button>
                <button type="button" id="cancel-button-user" onclick="resetUserForm()" class="px-4 py-2 font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300" style="display: none;">Batal</button>
            </div>
        </form>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Daftar Semua Pengguna</h2>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lengkap</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-4 py-4"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                    <td class="px-4 py-4"><?= htmlspecialchars($row['username']) ?></td>
                    <td class="px-4 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?= $row['role'] == 'asisten' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' ?>">
                            <?= ucfirst($row['role']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-4 text-right">
                        <button onclick="editUserForm(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['nama_lengkap'])) ?>', '<?= htmlspecialchars(addslashes($row['username'])) ?>', '<?= $row['role'] ?>')" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                        <?php if ($row['id'] != $_SESSION['user_id']): // Tombol delete tidak muncul untuk diri sendiri ?>
                            <a href="index.php?action=delete_user&id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus pengguna ini? Semua data terkait (laporan, etc) akan ikut terhapus.')" class="text-red-600 hover:text-red-900 ml-4">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function editUserForm(id, nama, username, role) {
    document.getElementById('form-title-user').innerText = 'Ubah Data Pengguna';
    document.getElementById('form-action-user').value = 'update_user';
    document.getElementById('user-id').value = id;
    document.getElementById('nama_lengkap').value = nama;
    document.getElementById('username').value = username;
    document.getElementById('role').value = role;
    document.getElementById('password').placeholder = 'Kosongkan jika tidak ingin diubah';
    document.getElementById('password').required = false;
    document.getElementById('submit-button-user').innerText = 'Update';
    document.getElementById('cancel-button-user').style.display = 'inline-block';
    window.scrollTo(0, 0);
}

function resetUserForm() {
    document.getElementById('form-title-user').innerText = 'Tambah Pengguna Baru';
    document.getElementById('user-form').reset();
    document.getElementById('form-action-user').value = 'add_user';
    document.getElementById('user-id').value = '';
    document.getElementById('password').placeholder = '';
    document.getElementById('password').required = true;
    document.getElementById('submit-button-user').innerText = 'Simpan';
    document.getElementById('cancel-button-user').style.display = 'none';
}
</script>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
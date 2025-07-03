<?php
/**
 * Ini adalah versi final dan yang direkomendasikan.
 * File ini berada di dalam folder /public dan bertindak sebagai router utama.
 */

// Memasukkan file koneksi database & memulai sesi
require_once __DIR__ . '/../config/database.php';

// Mengambil parameter 'action' untuk logika backend dan 'page' untuk tampilan
$action = $_REQUEST['action'] ?? '';
$page = $_REQUEST['page'] ?? 'home';

// =================================================================
// BAGIAN LOGIKA PEMROSESAN (ACTIONS)
// =================================================================
if (!empty($action)) {
    // Daftar aksi yang hanya bisa dilakukan oleh Asisten
    $asisten_actions = [
        'add_praktikum', 'update_praktikum', 'delete_praktikum',
        'add_modul', 'update_modul', 'delete_modul',
        'beri_nilai',
        'add_user', 'update_user', 'delete_user'
    ];
    if (in_array($action, $asisten_actions) && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'asisten')) {
        header("Location: index.php?page=login"); // Tendang jika bukan asisten
        exit();
    }

    switch ($action) {
        // --- AKSI AUTENTIKASI ---
        case 'register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nama_lengkap = $_POST['nama_lengkap'];
                $username = $_POST['username'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $role = 'mahasiswa';
                $stmt = $conn->prepare("INSERT INTO users (nama_lengkap, username, password, role) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $nama_lengkap, $username, $password, $role);
                if ($stmt->execute()) { header("Location: index.php?page=login&status=reg_success"); }
                else { header("Location: index.php?page=register&status=reg_failed"); }
                $stmt->close();
            }
            break;
        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $stmt = $conn->prepare("SELECT id, nama_lengkap, password, role FROM users WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($user = $result->fetch_assoc()) {
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                        $_SESSION['role'] = $user['role'];
                        $dashboard = ($user['role'] === 'asisten') ? 'asisten_dashboard' : 'mahasiswa_dashboard';
                        header("Location: index.php?page=$dashboard");
                        exit();
                    }
                }
                header("Location: index.php?page=login&status=login_failed");
            }
            break;
        case 'logout':
            session_destroy();
            header("Location: index.php?page=login&status=logout_success");
            break;

        // --- AKSI MAHASISWA ---
        case 'daftar_praktikum':
            if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'mahasiswa' && isset($_GET['id'])) {
                $mahasiswa_id = $_SESSION['user_id'];
                $praktikum_id = $_GET['id'];
                $stmt = $conn->prepare("INSERT INTO pendaftaran_praktikum (mahasiswa_id, praktikum_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE mahasiswa_id=mahasiswa_id");
                $stmt->bind_param("ii", $mahasiswa_id, $praktikum_id);
                if ($stmt->execute() && $stmt->affected_rows > 0) { header("Location: index.php?page=mahasiswa_dashboard&status=daftar_sukses"); }
                else { header("Location: index.php?page=cari_praktikum&status=sudah_terdaftar"); }
                $stmt->close();
            } else { header("Location: index.php?page=login"); }
            break;
        case 'kumpul_laporan':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && isset($_POST['modul_id']) && !empty($_FILES['file_laporan']['name'])) {
                $mahasiswa_id = $_SESSION['user_id'];
                $modul_id = $_POST['modul_id'];
                $target_dir = __DIR__ . "/uploads/laporan/";
                $file_name = time() . "_" . basename($_FILES["file_laporan"]["name"]);
                $target_file = $target_dir . $file_name;
                $stmt_get_id = $conn->prepare("SELECT praktikum_id FROM modul WHERE id = ?");
                $stmt_get_id->bind_param("i", $modul_id);
                $stmt_get_id->execute();
                $praktikum_id = $stmt_get_id->get_result()->fetch_assoc()['praktikum_id'];
                $stmt_get_id->close();
                if (move_uploaded_file($_FILES["file_laporan"]["tmp_name"], $target_file)) {
                    $stmt = $conn->prepare("INSERT INTO laporan (modul_id, mahasiswa_id, file_laporan, status) VALUES (?, ?, ?, 'dikumpulkan')");
                    $stmt->bind_param("iis", $modul_id, $mahasiswa_id, $file_name);
                    $stmt->execute();
                    $stmt->close();
                    header("Location: index.php?page=detail_praktikum&id=$praktikum_id&status=upload_sukses");
                } else { header("Location: index.php?page=detail_praktikum&id=$praktikum_id&status=upload_gagal"); }
            }
            break;

        // --- AKSI ASISTEN ---
        case 'add_praktikum': if ($_SERVER['REQUEST_METHOD'] === 'POST') { $stmt = $conn->prepare("INSERT INTO mata_praktikum (nama_praktikum, deskripsi) VALUES (?, ?)"); $stmt->bind_param("ss", $_POST['nama_praktikum'], $_POST['deskripsi']); $stmt->execute(); $stmt->close(); header("Location: index.php?page=manage_praktikum&status=add_success"); } break;
        case 'update_praktikum': if ($_SERVER['REQUEST_METHOD'] === 'POST') { $stmt = $conn->prepare("UPDATE mata_praktikum SET nama_praktikum = ?, deskripsi = ? WHERE id = ?"); $stmt->bind_param("ssi", $_POST['nama_praktikum'], $_POST['deskripsi'], $_POST['id']); $stmt->execute(); $stmt->close(); header("Location: index.php?page=manage_praktikum&status=update_success"); } break;
        case 'delete_praktikum': if (isset($_GET['id'])) { $stmt = $conn->prepare("DELETE FROM mata_praktikum WHERE id = ?"); $stmt->bind_param("i", $_GET['id']); $stmt->execute(); $stmt->close(); header("Location: index.php?page=manage_praktikum&status=delete_success"); } break;
        case 'add_modul':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $praktikum_id = $_POST['praktikum_id'];
                $judul = $_POST['judul_modul'];
                $deskripsi = $_POST['deskripsi_modul'];
                $file_materi = '';
                if (isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] == 0) {
                    $target_dir = __DIR__ . "/uploads/materi/";
                    $file_materi = time() . "_" . basename($_FILES["file_materi"]["name"]);
                    move_uploaded_file($_FILES["file_materi"]["tmp_name"], $target_dir . $file_materi);
                }
                $stmt = $conn->prepare("INSERT INTO modul (praktikum_id, judul_modul, deskripsi_modul, file_materi) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $praktikum_id, $judul, $deskripsi, $file_materi);
                $stmt->execute();
                $stmt->close();
                header("Location: index.php?page=manage_modul&praktikum_id=$praktikum_id&status=add_success");
            }
            break;

        case 'update_modul': // <-- FUNGSI EDIT BARU
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $modul_id = $_POST['modul_id'];
                $praktikum_id = $_POST['praktikum_id'];
                $judul = $_POST['judul_modul'];
                $deskripsi = $_POST['deskripsi_modul'];

                // Cek jika ada file baru yang diunggah
                if (isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] == 0) {
                    // Hapus file lama jika ada
                    $stmt_old_file = $conn->prepare("SELECT file_materi FROM modul WHERE id = ?");
                    $stmt_old_file->bind_param("i", $modul_id);
                    $stmt_old_file->execute();
                    $old_file_result = $stmt_old_file->get_result()->fetch_assoc();
                    if ($old_file_result && !empty($old_file_result['file_materi'])) {
                        $old_file_path = __DIR__ . "/uploads/materi/" . $old_file_result['file_materi'];
                        if (file_exists($old_file_path)) {
                            unlink($old_file_path);
                        }
                    }
                    $stmt_old_file->close();

                    // Unggah file baru
                    $target_dir = __DIR__ . "/uploads/materi/";
                    $new_file_materi = time() . "_" . basename($_FILES["file_materi"]["name"]);
                    move_uploaded_file($_FILES["file_materi"]["tmp_name"], $target_dir . $new_file_materi);

                    // Update database dengan file baru
                    $stmt = $conn->prepare("UPDATE modul SET judul_modul=?, deskripsi_modul=?, file_materi=? WHERE id=?");
                    $stmt->bind_param("sssi", $judul, $deskripsi, $new_file_materi, $modul_id);
                } else {
                    // Update database tanpa mengubah file
                    $stmt = $conn->prepare("UPDATE modul SET judul_modul=?, deskripsi_modul=? WHERE id=?");
                    $stmt->bind_param("ssi", $judul, $deskripsi, $modul_id);
                }
                $stmt->execute();
                $stmt->close();
                header("Location: index.php?page=manage_modul&praktikum_id=$praktikum_id&status=update_success");
            }
            break;

        case 'delete_modul':
            if (isset($_GET['id'])) {
                $modul_id = $_GET['id'];
                $stmt = $conn->prepare("SELECT praktikum_id, file_materi FROM modul WHERE id = ?");
                $stmt->bind_param("i", $modul_id);
                $stmt->execute();
                $modul = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                if ($modul) {
                    if (!empty($modul['file_materi']) && file_exists(__DIR__ . "/uploads/materi/" . $modul['file_materi'])) {
                        unlink(__DIR__ . "/uploads/materi/" . $modul['file_materi']);
                    }
                    $del_stmt = $conn->prepare("DELETE FROM modul WHERE id = ?");
                    $del_stmt->bind_param("i", $modul_id);
                    $del_stmt->execute();
                    $del_stmt->close();
                }
                header("Location: index.php?page=manage_modul&praktikum_id=" . $modul['praktikum_id'] . "&status=delete_success");
            }
            break;

        case 'beri_nilai':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['laporan_id'])) {
                $laporan_id = $_POST['laporan_id'];
                $nilai = $_POST['nilai'];
                $feedback = $_POST['feedback'];
                $stmt = $conn->prepare("UPDATE laporan SET nilai = ?, feedback = ?, status = 'dinilai' WHERE id = ?");
                $stmt->bind_param("isi", $nilai, $feedback, $laporan_id);
                $stmt->execute();
                $stmt->close();
                // URL diubah agar tidak menyertakan status, sehingga tidak bentrok dengan filter
                header("Location: index.php?page=periksa_laporan");
            }
            break;

        case 'add_user': if ($_SERVER['REQUEST_METHOD'] === 'POST') { $nama_lengkap = $_POST['nama_lengkap']; $username = $_POST['username']; $role = $_POST['role']; $password = password_hash($_POST['password'], PASSWORD_DEFAULT); $stmt = $conn->prepare("INSERT INTO users (nama_lengkap, username, password, role) VALUES (?, ?, ?, ?)"); $stmt->bind_param("ssss", $nama_lengkap, $username, $password, $role); $stmt->execute(); $stmt->close(); header("Location: index.php?page=manage_users&status=add_success"); } break;
        case 'update_user':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'];
                $nama_lengkap = $_POST['nama_lengkap'];
                $username = $_POST['username'];
                $role = $_POST['role'];
                if (!empty($_POST['password'])) {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE users SET nama_lengkap=?, username=?, password=?, role=? WHERE id=?");
                    $stmt->bind_param("ssssi", $nama_lengkap, $username, $password, $role, $id);
                } else {
                    $stmt = $conn->prepare("UPDATE users SET nama_lengkap=?, username=?, role=? WHERE id=?");
                    $stmt->bind_param("sssi", $nama_lengkap, $username, $role, $id);
                }
                $stmt->execute();
                $stmt->close();
                header("Location: index.php?page=manage_users&status=update_success");
            }
            break;
        case 'delete_user': if (isset($_GET['id']) && $_GET['id'] != $_SESSION['user_id']) { $id = $_GET['id']; $stmt = $conn->prepare("DELETE FROM users WHERE id = ?"); $stmt->bind_param("i", $id); $stmt->execute(); $stmt->close(); header("Location: index.php?page=manage_users&status=delete_success"); } else { header("Location: index.php?page=manage_users&status=delete_failed"); } break;
    }
    exit();
}

// =================================================================
// BAGIAN MENAMPILKAN HALAMAN (VIEWS)
// =================================================================
switch ($page) {
    // --- Halaman Publik & Auth ---
    case 'register': include __DIR__ . '/../src/views/auth/register.php'; break;
    case 'login': include __DIR__ . '/../src/views/auth/login.php'; break;
    case 'cari_praktikum': include __DIR__ . '/../src/views/cari_praktikum.php'; break;

    // --- Halaman Mahasiswa ---
    case 'mahasiswa_dashboard': include __DIR__ . '/../src/views/mahasiswa/dashboard.php'; break;
    case 'detail_praktikum': include __DIR__ . '/../src/views/mahasiswa/detail_praktikum.php'; break;

    // --- Halaman Asisten ---
    case 'asisten_dashboard': include __DIR__ . '/../src/views/asisten/dashboard.php'; break;
    case 'manage_praktikum': include __DIR__ . '/../src/views/asisten/manage_praktikum.php'; break;
    case 'manage_modul': include __DIR__ . '/../src/views/asisten/manage_modul.php'; break;
    case 'periksa_laporan': include __DIR__ . '/../src/views/asisten/periksa_laporan.php'; break;
    case 'beri_nilai': include __DIR__ . '/../src/views/asisten/beri_nilai.php'; break;
    case 'manage_users': include __DIR__ . '/../src/views/asisten/manage_users.php'; break;
    
    // --- Halaman Default ---
    case 'home':
    default:
        include __DIR__ . '/../src/views/cari_praktikum.php';
        break;
}

// Menutup koneksi database satu kali di akhir skrip
$conn->close();
?>
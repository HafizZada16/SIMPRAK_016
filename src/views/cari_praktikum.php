<?php
$page_title = 'Katalog Praktikum';
include_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/../../config/database.php';

// Ambil data dari database
$result = $conn->query("SELECT * FROM mata_praktikum ORDER BY nama_praktikum ASC");

// Ambil data praktikum yang sudah diikuti oleh mahasiswa (jika login)
$praktikum_terdaftar = [];
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'mahasiswa') {
    $stmt = $conn->prepare("SELECT praktikum_id FROM pendaftaran_praktikum WHERE mahasiswa_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $res_terdaftar = $stmt->get_result();
    while ($row_terdaftar = $res_terdaftar->fetch_assoc()) {
        $praktikum_terdaftar[] = $row_terdaftar['praktikum_id'];
    }
    $stmt->close();
}
?>

<div class="bg-indigo-700 rounded-lg shadow-lg">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
            Katalog Mata Praktikum
        </h1>
        <p class="mt-6 max-w-2xl mx-auto text-xl text-indigo-100">
            Jelajahi semua praktikum yang tersedia dan mulailah perjalanan belajar Anda bersama kami.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 -mt-16">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transform hover:-translate-y-2 transition-transform duration-300">
                <div class="p-8 flex-grow">
                    <h2 class="text-xl font-bold text-indigo-900"><?= htmlspecialchars($row['nama_praktikum']) ?></h2>
                    <p class="mt-3 text-gray-600 leading-relaxed"><?= htmlspecialchars($row['deskripsi']) ?></p>
                </div>
                <div class="p-6 bg-gray-50 border-t border-gray-100">
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'mahasiswa'): ?>
                        <?php if (in_array($row['id'], $praktikum_terdaftar)): ?>
                            <div class="w-full text-center inline-block px-4 py-3 font-semibold text-white bg-green-500 rounded-lg shadow-sm cursor-default">
                                &#10003; Sudah Terdaftar
                            </div>
                        <?php else: ?>
                            <a href="index.php?action=daftar_praktikum&id=<?= $row['id'] ?>" class="w-full text-center block px-4 py-3 font-semibold text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-300">
                                Daftar Praktikum Ini
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="index.php?page=login" class="w-full text-center block px-4 py-3 font-semibold text-indigo-600 bg-white border-2 border-indigo-500 rounded-lg hover:bg-indigo-500 hover:text-white transition-colors duration-300">
                            Login untuk Mendaftar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-full bg-white p-8 rounded-lg shadow-md text-center">
                <p class="text-gray-500 text-lg">Saat ini belum ada mata praktikum yang tersedia.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="bg-gray-50">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900">Mengapa Menggunakan SIMPRAK?</h2>
            <p class="mt-4 text-lg text-gray-600">Sistem kami dirancang untuk membuat proses praktikum lebih efisien dan terorganisir.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="text-center">
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <h3 class="mt-5 text-lg font-medium text-gray-900">Manajemen Terpusat</h3>
                <p class="mt-2 text-base text-gray-500">Semua materi, tugas, dan nilai terkumpul dalam satu dasbor yang mudah diakses.</p>
            </div>
            <div class="text-center">
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <h3 class="mt-5 text-lg font-medium text-gray-900">Proses Efisien</h3>
                <p class="mt-2 text-base text-gray-500">Mengurangi pekerjaan manual bagi asisten dan memudahkan mahasiswa melacak progres.</p>
            </div>
            <div class="text-center">
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="mt-5 text-lg font-medium text-gray-900">Penilaian Transparan</h3>
                <p class="mt-2 text-base text-gray-500">Mahasiswa dapat langsung melihat nilai dan feedback dari asisten setelah laporan diperiksa.</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900">Siap Memulai?</h2>
        <p class="mt-4 text-lg text-gray-600">Daftarkan diri Anda sekarang dan nikmati kemudahan dalam kegiatan praktikum.</p>
        <div class="mt-8">
            <a href="index.php?page=register" class="inline-block bg-indigo-600 text-white font-bold text-lg px-8 py-3 rounded-lg shadow-lg hover:bg-indigo-700 transition-colors">
                Buat Akun Sekarang
            </a>
        </div>
    </div>
</div>


<?php
// Pastikan tidak ada $conn->close() di sini untuk menghindari error
include_once __DIR__ . '/templates/footer.php';
?>
<?php
// Melindungi halaman, hanya bisa diakses jika sudah login sebagai asisten
require_once __DIR__ . '/../../../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: index.php?page=login");
    exit();
}
$page_title = 'Dasbor Asisten';
include_once __DIR__ . '/../templates/header.php';
?>

<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
            Dasbor Asisten
        </h1>
        <p class="text-gray-700 mt-1">Selamat datang, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>. Kelola semua data praktikum dari sini.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        
        <a href="index.php?page=manage_praktikum" class="flex items-start p-4 bg-white rounded-lg shadow-md hover:shadow-lg hover:bg-gray-50 transition-all duration-200">
            <div class="flex-shrink-0 p-3 bg-indigo-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-800">Kelola Mata Praktikum</h3>
                <p class="mt-1 text-sm text-gray-600">Tambah, ubah, atau hapus data master mata praktikum.</p>
            </div>
        </a>

        <a href="index.php?page=manage_modul" class="flex items-start p-4 bg-white rounded-lg shadow-md hover:shadow-lg hover:bg-gray-50 transition-all duration-200">
            <div class="flex-shrink-0 p-3 bg-indigo-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-800">Kelola Modul & Materi</h3>
                <p class="mt-1 text-sm text-gray-600">Atur modul dan unggah file materi untuk setiap praktikum.</p>
            </div>
        </a>

        <a href="index.php?page=periksa_laporan" class="flex items-start p-4 bg-white rounded-lg shadow-md hover:shadow-lg hover:bg-gray-50 transition-all duration-200">
            <div class="flex-shrink-0 p-3 bg-indigo-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-800">Periksa Laporan Masuk</h3>
                <p class="mt-1 text-sm text-gray-600">Lihat, filter, dan beri nilai laporan dari mahasiswa.</p>
            </div>
        </a>

        <a href="index.php?page=manage_users" class="flex items-start p-4 bg-white rounded-lg shadow-md hover:shadow-lg hover:bg-gray-50 transition-all duration-200">
            <div class="flex-shrink-0 p-3 bg-indigo-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-800">Kelola Akun Pengguna</h3>
                <p class="mt-1 text-sm text-gray-600">Manajemen semua akun yang terdaftar di sistem.</p>
            </div>
        </a>

    </div>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
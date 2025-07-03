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
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            Dasbor Asisten
        </h1>
        <p class="text-gray-700 mt-1">Selamat datang, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>. Kelola semua data praktikum dari sini.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <a href="index.php?page=manage_praktikum" class="group flex flex-col items-center justify-center text-center p-6 bg-white rounded-xl shadow-lg hover:shadow-xl hover:bg-indigo-50 transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex-shrink-0 p-4 bg-indigo-100 rounded-full group-hover:bg-indigo-200 transition-colors">
                <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-gray-800">Kelola Praktikum</h3>
            <p class="mt-1 text-sm text-gray-500">Atur data master mata praktikum.</p>
        </a>

        <a href="index.php?page=manage_modul" class="group flex flex-col items-center justify-center text-center p-6 bg-white rounded-xl shadow-lg hover:shadow-xl hover:bg-indigo-50 transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex-shrink-0 p-4 bg-indigo-100 rounded-full group-hover:bg-indigo-200 transition-colors">
                <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-gray-800">Kelola Modul</h3>
            <p class="mt-1 text-sm text-gray-500">Atur modul dan unggah materi.</p>
        </a>

        <a href="index.php?page=periksa_laporan" class="group flex flex-col items-center justify-center text-center p-6 bg-white rounded-xl shadow-lg hover:shadow-xl hover:bg-indigo-50 transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex-shrink-0 p-4 bg-indigo-100 rounded-full group-hover:bg-indigo-200 transition-colors">
                <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-gray-800">Periksa Laporan</h3>
            <p class="mt-1 text-sm text-gray-500">Lihat dan beri nilai laporan.</p>
        </a>

        <a href="index.php?page=manage_users" class="group flex flex-col items-center justify-center text-center p-6 bg-white rounded-xl shadow-lg hover:shadow-xl hover:bg-indigo-50 transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex-shrink-0 p-4 bg-indigo-100 rounded-full group-hover:bg-indigo-200 transition-colors">
                <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197" />
                </svg>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-gray-800">Kelola Pengguna</h3>
            <p class="mt-1 text-sm text-gray-500">Manajemen semua akun sistem.</p>
        </a>

    </div>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
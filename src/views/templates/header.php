<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'SIMPRAK' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans flex flex-col min-h-screen">
    <nav class="bg-white shadow-md" x-data="{ isMobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <a href="index.php" class="text-2xl font-bold text-indigo-600">SIMPRAK</a>
                </div>

                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($_SESSION['role'] === 'mahasiswa'): ?>
                                <a href="index.php?page=mahasiswa_dashboard" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Dasbor Saya</a>
                            <?php else: ?>
                                <a href="index.php?page=asisten_dashboard" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Dasbor Asisten</a>
                            <?php endif; ?>
                            <a href="index.php?page=cari_praktikum" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Cari Praktikum</a>
                            <a href="index.php?action=logout" class="bg-red-500 text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-red-600">Logout</a>
                        <?php else: ?>
                            <a href="index.php?page=cari_praktikum" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Cari Praktikum</a>
                            <a href="index.php?page=login" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                            <a href="index.php?page=register" class="bg-indigo-600 text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Registrasi</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="-mr-2 flex md:hidden">
                    <button @click="isMobileMenuOpen = !isMobileMenuOpen" type="button" class="bg-indigo-50 inline-flex items-center justify-center p-2 rounded-md text-indigo-600 hover:text-indigo-700 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Buka menu utama</span>
                        <svg x-show="!isMobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="isMobileMenuOpen" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="isMobileMenuOpen" @click.away="isMobileMenuOpen = false" class="md:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] === 'mahasiswa'): ?>
                        <a href="index.php?page=mahasiswa_dashboard" class="text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 block px-3 py-2 rounded-md text-base font-medium">Dasbor Saya</a>
                    <?php else: ?>
                        <a href="index.php?page=asisten_dashboard" class="text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 block px-3 py-2 rounded-md text-base font-medium">Dasbor Asisten</a>
                    <?php endif; ?>
                    <a href="index.php?page=cari_praktikum" class="text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 block px-3 py-2 rounded-md text-base font-medium">Cari Praktikum</a>
                    <a href="index.php?action=logout" class="text-gray-700 hover:bg-red-50 hover:text-red-700 block px-3 py-2 rounded-md text-base font-medium">Logout</a>
                <?php else: ?>
                    <a href="index.php?page=cari_praktikum" class="text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 block px-3 py-2 rounded-md text-base font-medium">Cari Praktikum</a>
                    <a href="index.php?page=login" class="text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 block px-3 py-2 rounded-md text-base font-medium">Login</a>
                    <a href="index.php?page=register" class="bg-indigo-600 text-white block px-3 py-2 rounded-md text-base font-medium">Registrasi</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
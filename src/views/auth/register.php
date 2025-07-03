<?php
$page_title = 'Registrasi Akun Baru';
include_once __DIR__ . '/../templates/header.php';
?>

<div class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-xl shadow-lg">
        
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Buat Akun Baru</h2>
            <p class="mt-2 text-sm text-gray-600">Daftarkan diri Anda untuk memulai.</p>
        </div>
        
        <form action="index.php?action=register" method="POST" class="space-y-6">
            
            <div>
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm1 2a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input id="nama_lengkap" name="nama_lengkap" type="text" required 
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Masukkan nama lengkap">
                </div>
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input id="username" name="username" type="text" required 
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Masukkan username">
                </div>
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                             <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input id="password" name="password" type="password" required 
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Buat password baru">
                </div>
            </div>
            
            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Daftar
                </button>
            </div>
        </form>

        <p class="text-sm text-center text-gray-500 pt-4">
            Sudah punya akun? 
            <a href="index.php?page=login" class="font-medium text-indigo-600 hover:text-indigo-500">
                Login di sini
            </a>
        </p>
    </div>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
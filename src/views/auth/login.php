<?php
$page_title = 'Login';
include_once __DIR__ . '/../templates/header.php';
?>

<div class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-xl shadow-lg">
        
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Login ke Akun Anda</h2>
            <p class="mt-2 text-sm text-gray-600">Selamat datang kembali!</p>
        </div>
        
        <form action="index.php?action=login" method="POST" class="space-y-6">
            
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
                           placeholder="Masukkan password">
                </div>
            </div>
            
            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Login
                </button>
            </div>
        </form>

        <p class="text-sm text-center text-gray-500 pt-4">
            Belum punya akun? 
            <a href="index.php?page=register" class="font-medium text-indigo-600 hover:text-indigo-500">
                Daftar di sini
            </a>
        </p>
    </div>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
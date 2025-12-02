<?php // includes/navbar.php 
// Pastikan session sudah dimulai di file induk (index.php, dll)
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="fixed top-4 left-0 right-0 z-50 flex justify-center px-4">
    <nav class="w-full max-w-7xl bg-white/70 backdrop-blur-xl border border-white/40 shadow-xl shadow-pink-500/5 rounded-full transition-all duration-300 hover:bg-white/90">
        <div class="px-6 md:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                
                <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer group" onclick="window.location.href='/ladystyle-shop/index.php'">
                    <div class="relative w-10 h-10 flex items-center justify-center">
                        <div class="absolute inset-0 bg-gradient-to-tr from-ls-400 to-ls-600 rounded-full blur opacity-70 group-hover:opacity-100 transition duration-500"></div>
                        <div class="relative w-full h-full bg-gradient-to-tr from-ls-500 to-ls-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-inner">
                            LS
                        </div>
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-lg font-bold text-gray-800 tracking-tight leading-none group-hover:text-ls-600 transition">LadyStyle</h1>
                        <p class="text-[9px] text-ls-500 font-bold tracking-[0.2em] uppercase">Fashion Store</p>
                    </div>
                </div>

                <div class="hidden md:flex items-center gap-1 bg-gray-100/50 p-1.5 rounded-full border border-white/50">
                    <a href="/ladystyle-shop/index.php" 
                       class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-300 <?= $current_page == 'index.php' ? 'bg-white text-ls-600 shadow-sm' : 'text-gray-500 hover:text-ls-600 hover:bg-white/60' ?>">
                        Beranda
                    </a>
                    <a href="/ladystyle-shop/products.php" 
                       class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-300 <?= $current_page == 'products.php' ? 'bg-white text-ls-600 shadow-sm' : 'text-gray-500 hover:text-ls-600 hover:bg-white/60' ?>">
                        Koleksi
                    </a>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'customer'): ?>
                    <a href="/ladystyle-shop/profile.php" 
                       class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-300 <?= $current_page == 'profile.php' ? 'bg-white text-ls-600 shadow-sm' : 'text-gray-500 hover:text-ls-600 hover:bg-white/60' ?>">
                        Profil
                    </a>
                    <?php endif; ?>
                </div>

                <div class="flex items-center gap-3">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="flex items-center gap-2 pl-2 md:pl-0">
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <a href="/ladystyle-shop/dashboard/index.php" class="hidden md:flex items-center gap-2 px-4 py-2 rounded-full bg-gray-900 text-white text-xs font-bold hover:bg-gray-800 transition shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                    <span>Dashboard</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            <?php else: ?>
                                <a href="/ladystyle-shop/profile.php" class="flex items-center gap-2 pr-4 pl-1 py-1 rounded-full border border-gray-200 hover:border-ls-200 bg-white hover:bg-ls-50 transition group">
                                    <div class="w-8 h-8 rounded-full bg-ls-100 text-ls-600 flex items-center justify-center font-bold text-xs group-hover:bg-ls-200 transition">
                                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                                    </div>
                                    <span class="text-xs font-bold text-gray-700 max-w-[80px] truncate hidden sm:block">
                                        <?= htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]) ?>
                                    </span>
                                </a>
                            <?php endif; ?>
                            
                            <a href="/ladystyle-shop/logout.php" class="w-10 h-10 flex items-center justify-center rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 transition border border-transparent hover:border-red-100" title="Keluar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </a>
                        </div>

                    <?php else: ?>
                        <a href="/ladystyle-shop/login_customer.php" class="text-sm font-bold text-gray-500 hover:text-ls-600 transition px-2">
                            Masuk
                        </a>
                        <a href="/ladystyle-shop/register.php" class="group relative px-6 py-2.5 rounded-full overflow-hidden shadow-md hover:shadow-lg transition-all hover:-translate-y-0.5">
                            <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-ls-500 to-ls-600 group-hover:scale-105 transition-transform duration-300"></div>
                            <span class="relative text-sm font-bold text-white flex items-center gap-2">
                                Daftar
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </span>
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </nav>
</div>
<div class="h-32"></div> 
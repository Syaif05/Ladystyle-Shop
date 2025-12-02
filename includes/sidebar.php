<?php // includes/sidebar.php 
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="w-64 hidden md:flex flex-col fixed inset-y-0 z-50 bg-white/80 backdrop-blur-xl border-r border-gray-100 transition-all duration-300">
    <div class="h-20 flex items-center justify-center border-b border-gray-100">
        <div class="flex items-center gap-2 text-ls-600">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-ls-400 to-ls-600 flex items-center justify-center text-white font-bold text-sm shadow-glow">LS</div>
            <span class="font-bold text-lg tracking-tight">Admin Panel</span>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
        <?php
        $menus = [
            'index.php' => ['label' => 'Dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />'],
            'products.php' => ['label' => 'Produk', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />'],
            'categories.php' => ['label' => 'Kategori', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />'],
            'orders.php' => ['label' => 'Pesanan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />'],
            'reports.php' => ['label' => 'Laporan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />'],
            'settings.php' => ['label' => 'Pengaturan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />'],
        ];

        foreach ($menus as $file => $menu):
            $active = ($currentPage === $file);
            $bgClass = $active ? 'bg-ls-50 text-ls-600 shadow-sm ring-1 ring-ls-100' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900';
        ?>
            <a href="<?= $file ?>" class="<?= $bgClass ?> group flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200">
                <svg class="mr-3 flex-shrink-0 h-5 w-5 transition-colors duration-200 <?= $active ? 'text-ls-500' : 'text-gray-400 group-hover:text-gray-500' ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <?= $menu['icon'] ?>
                </svg>
                <?= $menu['label'] ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="p-4 border-t border-gray-100">
        <a href="/ladystyle-shop/logout.php" class="flex items-center px-4 py-3 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-2xl transition-colors">
            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
            Keluar
        </a>
    </div>
</aside>
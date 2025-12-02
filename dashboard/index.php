<?php // dashboard/index.php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

require_login();

// Ambil Statistik
$stats = [
    'products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
    'orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    'revenue' => $pdo->query("SELECT COALESCE(SUM(total_price), 0) FROM orders WHERE status = 'selesai'")->fetchColumn(),
    'pending' => $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'baru'")->fetchColumn()
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard - LadyStyle Admin</title>
    <?php require __DIR__ . '/../includes/head.php'; ?>
</head>
<body class="bg-gray-50/50">
    <div class="flex h-screen overflow-hidden">
        <?php require __DIR__ . '/../includes/sidebar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/50 p-6 md:p-12">
            <div class="max-w-6xl mx-auto">
                <header class="mb-8 flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                        <p class="text-gray-500 mt-1">Ringkasan aktivitas toko Anda hari ini.</p>
                    </div>
                    <div class="hidden md:flex items-center gap-3 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100">
                        <div class="w-8 h-8 rounded-full bg-ls-100 flex items-center justify-center text-ls-600 font-bold text-xs">
                            <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
                        </div>
                        <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span>
                    </div>
                </header>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="glass-card p-6 rounded-3xl relative overflow-hidden group">
                        <div class="relative z-10">
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Pendapatan</p>
                            <h3 class="text-2xl font-bold text-gray-900">Rp <?= number_format($stats['revenue'], 0, ',', '.') ?></h3>
                        </div>
                        <div class="absolute right-4 top-4 w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>

                    <div class="glass-card p-6 rounded-3xl relative overflow-hidden group">
                        <div class="relative z-10">
                            <p class="text-sm font-medium text-gray-500 mb-1">Pesanan Baru</p>
                            <h3 class="text-2xl font-bold text-gray-900"><?= $stats['pending'] ?> <span class="text-sm font-normal text-gray-400">Pending</span></h3>
                        </div>
                        <div class="absolute right-4 top-4 w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                    </div>

                    <div class="glass-card p-6 rounded-3xl relative overflow-hidden group">
                        <div class="relative z-10">
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Produk</p>
                            <h3 class="text-2xl font-bold text-gray-900"><?= $stats['products'] ?></h3>
                        </div>
                        <div class="absolute right-4 top-4 w-12 h-12 bg-ls-100 rounded-2xl flex items-center justify-center text-ls-600 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                    </div>
                </div>

                <div class="glass-panel p-8 rounded-3xl">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Selamat Datang di LadyStyle Shop</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Gunakan menu di sidebar sebelah kiri untuk mengelola produk, kategori, dan melihat pesanan masuk. 
                        Pastikan untuk selalu update status pesanan agar pelanggan dapat memantau paket mereka.
                    </p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
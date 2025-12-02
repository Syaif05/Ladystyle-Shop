<?php // dashboard/index.php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

require_login();

$userName = $_SESSION['user_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - LadyStyle Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/ladystyle-shop/assets/css/style.css" rel="stylesheet">
</head>
<body class="app-body">
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <span class="brand-mark">LS</span>
                <span class="brand-text">LadyStyle</span>
            </div>
            <nav class="sidebar-nav">
                <a href="/ladystyle-shop/dashboard/index.php" class="nav-link nav-link-active">Dashboard</a>
                <a href="/ladystyle-shop/dashboard/products.php" class="nav-link">Produk</a>
                <a href="/ladystyle-shop/dashboard/categories.php" class="nav-link">Kategori</a>
                <a href="/ladystyle-shop/dashboard/orders.php" class="nav-link">Pesanan</a>
                <a href="/ladystyle-shop/dashboard/reports.php" class="nav-link">Laporan</a>
            </nav>
            <a href="/ladystyle-shop/logout.php" class="nav-link nav-link-danger">Keluar</a>
        </aside>
        <main class="dashboard-main">
            <header class="dashboard-header">
                <h1 class="page-title">Dashboard</h1>
                <div class="user-pill">
                    <span class="user-avatar"><?= strtoupper($userName[0]) ?></span>
                    <span class="user-name"><?= e($userName) ?></span>
                </div>
            </header>
            <section class="dashboard-content">
                <div class="stats-grid">
                    <div class="glass-card stats-card">
                        <h2 class="stats-label">Selamat datang</h2>
                        <p class="stats-value"><?= e($userName) ?></p>
                        <p class="stats-desc">Ini adalah panel admin LadyStyle Shop. Menu produk, kategori, pesanan, dan laporan akan kita bangun bertahap.</p>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <script src="/ladystyle-shop/assets/js/app.js"></script>
</body>
</html>

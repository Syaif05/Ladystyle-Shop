<?php // dashboard/reports.php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

require_login();

$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';
$status = $_GET['status'] ?? 'selesai';

$params = [];
$conditions = [];

if ($startDate !== '') {
    $conditions[] = 'DATE(o.created_at) >= :start_date';
    $params['start_date'] = $startDate;
}
if ($endDate !== '') {
    $conditions[] = 'DATE(o.created_at) <= :end_date';
    $params['end_date'] = $endDate;
}
if ($status !== '') {
    $conditions[] = 'o.status = :status';
    $params['status'] = $status;
}

$where = '';
if (count($conditions) > 0) {
    $where = ' WHERE ' . implode(' AND ', $conditions);
}

$sql = 'SELECT o.*, p.name AS product_name FROM orders o INNER JOIN products p ON o.product_id = p.id' . $where . ' ORDER BY o.created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();

$sqlSummary = 'SELECT COUNT(*) AS total_orders, COALESCE(SUM(total_price),0) AS total_amount FROM orders o' . $where;
$stmtSummary = $pdo->prepare($sqlSummary);
$stmtSummary->execute($params);
$summary = $stmtSummary->fetch();
$totalOrders = (int) $summary['total_orders'];
$totalAmount = (int) $summary['total_amount'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - LadyStyle Shop</title>
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
            <a href="/ladystyle-shop/dashboard/index.php" class="nav-link">Dashboard</a>
            <a href="/ladystyle-shop/dashboard/products.php" class="nav-link">Produk</a>
            <a href="/ladystyle-shop/dashboard/categories.php" class="nav-link">Kategori</a>
            <a href="/ladystyle-shop/dashboard/orders.php" class="nav-link">Pesanan</a>
            <a href="/ladystyle-shop/dashboard/reports.php" class="nav-link nav-link-active">Laporan</a>
        </nav>
        <a href="/ladystyle-shop/logout.php" class="nav-link nav-link-danger">Keluar</a>
    </aside>
    <main class="dashboard-main">
        <header class="dashboard-header">
            <h1 class="page-title">Laporan Penjualan</h1>
            <div class="user-pill">
                <span class="user-avatar"><?= strtoupper(($_SESSION['user_name'] ?? 'A')[0]) ?></span>
                <span class="user-name"><?= e($_SESSION['user_name'] ?? 'Admin') ?></span>
            </div>
        </header>

        <section class="dashboard-content">
            <div class="stats-grid">
                <div class="glass-card stats-card">
                    <h2 class="stats-label">Filter Laporan</h2>
                    <form method="get" class="form-vertical mt-2">
                        <label class="form-label" for="start_date">Tanggal Mulai</label>
                        <input id="start_date" type="date" name="start_date" class="input-control" value="<?= e($startDate) ?>">

                        <label class="form-label" for="end_date">Tanggal Akhir</label>
                        <input id="end_date" type="date" name="end_date" class="input-control" value="<?= e($endDate) ?>">

                        <label class="form-label" for="status">Status Pesanan</label>
                        <select id="status" name="status" class="input-control">
                            <option value="">Semua</option>
                            <option value="baru" <?= $status === 'baru' ? 'selected' : '' ?>>Baru</option>
                            <option value="diproses" <?= $status === 'diproses' ? 'selected' : '' ?>>Diproses</option>
                            <option value="dikirim" <?= $status === 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                            <option value="selesai" <?= $status === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            <option value="dibatalkan" <?= $status === 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                        </select>

                        <button type="submit" class="btn-primary mt-3">Terapkan</button>
                    </form>
                </div>

                <div class="glass-card stats-card">
                    <h2 class="stats-label">Ringkasan</h2>
                    <p class="stats-value"><?= $totalOrders ?> Pesanan</p>
                    <p class="stats-desc">Total omzet: <strong><?= format_rupiah($totalAmount) ?></strong></p>
                    <p class="stats-desc" style="margin-top:6px; font-size:12px;">Data berdasarkan filter tanggal dan status yang dipilih.</p>
                </div>
            </div>

            <div class="stats-grid">
                <div class="glass-card stats-card">
                    <h2 class="stats-label">Detail Laporan</h2>
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Produk</th>
                                <th>Pemesan</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (count($orders) === 0): ?>
                                <tr>
                                    <td colspan="7">Belum ada data untuk filter ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?= e($order['order_code']) ?></td>
                                        <td><?= e($order['product_name']) ?></td>
                                        <td><?= e($order['customer_name']) ?></td>
                                        <td><?= (int) $order['qty'] ?></td>
                                        <td><?= format_rupiah((int) $order['total_price']) ?></td>
                                        <td><?= ucfirst($order['status']) ?></td>
                                        <td><?= e($order['created_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
<script src="/ladystyle-shop/assets/js/app.js"></script>
</body>
</html>

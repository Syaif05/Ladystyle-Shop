<?php // dashboard/orders.php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

require_login();

$error = '';
$success = '';

$allowedStatus = ['baru', 'diproses', 'dikirim', 'selesai', 'dibatalkan'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $status = $_POST['status'] ?? '';
    if ($id > 0 && in_array($status, $allowedStatus, true)) {
        $stmt = $pdo->prepare('UPDATE orders SET status = :status WHERE id = :id');
        $stmt->execute([
            'status' => $status,
            'id' => $id
        ]);
        $success = 'Status pesanan berhasil diperbarui.';
    }
}

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    if ($deleteId > 0) {
        $stmt = $pdo->prepare('DELETE FROM orders WHERE id = :id');
        $stmt->execute(['id' => $deleteId]);
        $success = 'Pesanan berhasil dihapus.';
    }
}

$filterStatus = $_GET['status'] ?? '';
$params = [];
$where = '';
if ($filterStatus !== '' && in_array($filterStatus, $allowedStatus, true)) {
    $where = ' WHERE o.status = :status';
    $params['status'] = $filterStatus;
}

$sql = 'SELECT o.*, p.name AS product_name FROM orders o INNER JOIN products p ON o.product_id = p.id' . $where . ' ORDER BY o.created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pesanan - LadyStyle Shop</title>
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
            <a href="/ladystyle-shop/dashboard/orders.php" class="nav-link nav-link-active">Pesanan</a>
            <a href="/ladystyle-shop/dashboard/reports.php" class="nav-link">Laporan</a>
        </nav>
        <a href="/ladystyle-shop/logout.php" class="nav-link nav-link-danger">Keluar</a>
    </aside>
    <main class="dashboard-main">
        <header class="dashboard-header">
            <h1 class="page-title">Manajemen Pesanan</h1>
            <div class="user-pill">
                <span class="user-avatar"><?= strtoupper(($_SESSION['user_name'] ?? 'A')[0]) ?></span>
                <span class="user-name"><?= e($_SESSION['user_name'] ?? 'Admin') ?></span>
            </div>
        </header>

        <section class="dashboard-content">
            <div class="stats-grid">
                <div class="glass-card stats-card">
                    <h2 class="stats-label">Filter Status</h2>
                    <form method="get" class="form-vertical mt-2">
                        <label class="form-label" for="status">Status Pesanan</label>
                        <select id="status" name="status" class="input-control">
                            <option value="">Semua</option>
                            <?php foreach ($allowedStatus as $status): ?>
                                <option value="<?= $status ?>" <?= $filterStatus === $status ? 'selected' : '' ?>>
                                    <?= ucfirst($status) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn-primary mt-3">Terapkan</button>
                    </form>
                    <?php if ($error !== ''): ?>
                        <p class="helper-text mt-2" style="color:#fecaca;"><?= e($error) ?></p>
                    <?php endif; ?>
                    <?php if ($success !== ''): ?>
                        <p class="helper-text mt-2" style="color:#bbf7d0;"><?= e($success) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="stats-grid">
                <div class="glass-card stats-card">
                    <h2 class="stats-label">Daftar Pesanan</h2>
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
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (count($orders) === 0): ?>
                                <tr>
                                    <td colspan="8">Belum ada pesanan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?= e($order['order_code']) ?></td>
                                        <td><?= e($order['product_name']) ?></td>
                                        <td>
                                            <?= e($order['customer_name']) ?><br>
                                            <span style="font-size:11px; color:#9ca3af;"><?= e($order['customer_phone']) ?></span>
                                        </td>
                                        <td><?= (int) $order['qty'] ?></td>
                                        <td><?= format_rupiah((int) $order['total_price']) ?></td>
                                        <td><?= ucfirst($order['status']) ?></td>
                                        <td><?= e($order['created_at']) ?></td>
                                        <td>
                                            <form method="post" style="display:inline-block; margin-bottom:4px;">
                                                <input type="hidden" name="id" value="<?= (int) $order['id'] ?>">
                                                <select name="status" class="input-control" style="width:120px; display:inline-block; padding:4px 6px; font-size:11px;">
                                                    <?php foreach ($allowedStatus as $status): ?>
                                                        <option value="<?= $status ?>" <?= $order['status'] === $status ? 'selected' : '' ?>>
                                                            <?= ucfirst($status) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" class="btn-secondary" style="padding:4px 10px; font-size:11px; margin-top:4px;">Update</button>
                                            </form>
                                            <a href="/ladystyle-shop/dashboard/orders.php?delete=<?= (int) $order['id'] ?>" class="table-action table-action-danger" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</a>
                                        </td>
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

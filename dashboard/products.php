<?php // dashboard/products.php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

require_login();

$error = '';
$success = '';

$stmt = $pdo->query('SELECT * FROM categories ORDER BY name ASC');
$categories = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $price = (int) ($_POST['price'] ?? 0);
    $stock = (int) ($_POST['stock'] ?? 0);
    $sizeAvailable = trim($_POST['size_available'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $status = $_POST['status'] ?? 'active';

    if ($categoryId <= 0 || $name === '' || $price <= 0) {
        $error = 'Kategori, nama produk, dan harga wajib diisi dengan benar.';
    } else {
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE products SET category_id = :category_id, name = :name, price = :price, stock = :stock, size_available = :size_available, color = :color, description = :description, image = :image, status = :status WHERE id = :id');
            $stmt->execute([
                'category_id' => $categoryId,
                'name' => $name,
                'price' => $price,
                'stock' => $stock,
                'size_available' => $sizeAvailable,
                'color' => $color,
                'description' => $description,
                'image' => $image,
                'status' => $status,
                'id' => $id
            ]);
            $success = 'Produk berhasil diperbarui.';
        } else {
            $stmt = $pdo->prepare('INSERT INTO products (category_id, name, price, stock, size_available, color, description, image, status) VALUES (:category_id, :name, :price, :stock, :size_available, :color, :description, :image, :status)');
            $stmt->execute([
                'category_id' => $categoryId,
                'name' => $name,
                'price' => $price,
                'stock' => $stock,
                'size_available' => $sizeAvailable,
                'color' => $color,
                'description' => $description,
                'image' => $image,
                'status' => $status
            ]);
            $success = 'Produk baru berhasil ditambahkan.';
        }
    }
}

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    if ($deleteId > 0) {
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        try {
            $stmt->execute(['id' => $deleteId]);
            $success = 'Produk berhasil dihapus.';
        } catch (PDOException $e) {
            $error = 'Produk tidak dapat dihapus karena sudah memiliki pesanan.';
        }
    }
}

$editProduct = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    if ($editId > 0) {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $editId]);
        $editProduct = $stmt->fetch();
    }
}

$stmt = $pdo->query('SELECT p.*, c.name AS category_name FROM products p INNER JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC');
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Produk - LadyStyle Shop</title>
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
            <a href="/ladystyle-shop/dashboard/products.php" class="nav-link nav-link-active">Produk</a>
            <a href="/ladystyle-shop/dashboard/categories.php" class="nav-link">Kategori</a>
            <a href="/ladystyle-shop/dashboard/orders.php" class="nav-link">Pesanan</a>
            <a href="/ladystyle-shop/dashboard/reports.php" class="nav-link">Laporan</a>
        </nav>
        <a href="/ladystyle-shop/logout.php" class="nav-link nav-link-danger">Keluar</a>
    </aside>
    <main class="dashboard-main">
        <header class="dashboard-header">
            <h1 class="page-title">Manajemen Produk</h1>
            <div class="user-pill">
                <span class="user-avatar"><?= strtoupper(($_SESSION['user_name'] ?? 'A')[0]) ?></span>
                <span class="user-name"><?= e($_SESSION['user_name'] ?? 'Admin') ?></span>
            </div>
        </header>

        <section class="dashboard-content">
            <div class="stats-grid">
                <div class="glass-card stats-card">
                    <h2 class="stats-label"><?= $editProduct ? 'Edit Produk' : 'Tambah Produk Baru' ?></h2>
                    <form method="post" class="form-vertical mt-2">
                        <input type="hidden" name="id" value="<?= $editProduct['id'] ?? 0 ?>">

                        <label class="form-label" for="category_id">Kategori</label>
                        <select id="category_id" name="category_id" class="input-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= (int) $category['id'] ?>" <?= isset($editProduct['category_id']) && (int) $editProduct['category_id'] === (int) $category['id'] ? 'selected' : '' ?>>
                                    <?= e($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label class="form-label" for="name">Nama Produk</label>
                        <input id="name" type="text" name="name" class="input-control" value="<?= e($editProduct['name'] ?? '') ?>" required>

                        <label class="form-label" for="price">Harga (Rp)</label>
                        <input id="price" type="number" name="price" class="input-control" min="0" value="<?= isset($editProduct['price']) ? (int) $editProduct['price'] : '' ?>" required>

                        <label class="form-label" for="stock">Stok</label>
                        <input id="stock" type="number" name="stock" class="input-control" min="0" value="<?= isset($editProduct['stock']) ? (int) $editProduct['stock'] : 0 ?>">

                        <label class="form-label" for="size_available">Ukuran Tersedia (contoh: S,M,L,XL)</label>
                        <input id="size_available" type="text" name="size_available" class="input-control" value="<?= e($editProduct['size_available'] ?? '') ?>">

                        <label class="form-label" for="color">Warna</label>
                        <input id="color" type="text" name="color" class="input-control" value="<?= e($editProduct['color'] ?? '') ?>">

                        <label class="form-label" for="image">URL Gambar</label>
                        <input id="image" type="text" name="image" class="input-control" value="<?= e($editProduct['image'] ?? '') ?>">

                        <label class="form-label" for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="input-control" rows="3"><?= e($editProduct['description'] ?? '') ?></textarea>

                        <label class="form-label" for="status">Status</label>
                        <select id="status" name="status" class="input-control">
                            <option value="active" <?= isset($editProduct['status']) && $editProduct['status'] === 'inactive' ? '' : 'selected' ?>>Aktif</option>
                            <option value="inactive" <?= isset($editProduct['status']) && $editProduct['status'] === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>

                        <button type="submit" class="btn-primary mt-3">Simpan Produk</button>
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
                    <h2 class="stats-label">Daftar Produk</h2>
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (count($products) === 0): ?>
                                <tr>
                                    <td colspan="7">Belum ada produk.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= (int) $product['id'] ?></td>
                                        <td><?= e($product['name']) ?></td>
                                        <td><?= e($product['category_name']) ?></td>
                                        <td><?= format_rupiah((int) $product['price']) ?></td>
                                        <td><?= (int) $product['stock'] ?></td>
                                        <td><?= $product['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?></td>
                                        <td>
                                            <a href="/ladystyle-shop/dashboard/products.php?edit=<?= (int) $product['id'] ?>" class="table-action">Edit</a>
                                            <a href="/ladystyle-shop/dashboard/products.php?delete=<?= (int) $product['id'] ?>" class="table-action table-action-danger" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
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

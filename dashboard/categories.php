<?php // dashboard/categories.php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

require_login();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($name === '') {
        $error = 'Nama kategori wajib diisi.';
    } else {
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE categories SET name = :name WHERE id = :id');
            $stmt->execute([
                'name' => $name,
                'id' => $id
            ]);
            $success = 'Kategori berhasil diperbarui.';
        } else {
            $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (:name)');
            $stmt->execute(['name' => $name]);
            $success = 'Kategori baru berhasil ditambahkan.';
        }
    }
}

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    if ($deleteId > 0) {
        $stmt = $pdo->prepare('DELETE FROM categories WHERE id = :id');
        $stmt->execute(['id' => $deleteId]);
        $success = 'Kategori berhasil dihapus.';
    }
}

$editCategory = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    if ($editId > 0) {
        $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :id');
        $stmt->execute(['id' => $editId]);
        $editCategory = $stmt->fetch();
    }
}

$stmt = $pdo->query('SELECT * FROM categories ORDER BY id DESC');
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Kategori - LadyStyle Shop</title>
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
            <a href="/ladystyle-shop/dashboard/categories.php" class="nav-link nav-link-active">Kategori</a>
            <a href="/ladystyle-shop/dashboard/orders.php" class="nav-link">Pesanan</a>
            <a href="/ladystyle-shop/dashboard/reports.php" class="nav-link">Laporan</a>
        </nav>
        <a href="/ladystyle-shop/logout.php" class="nav-link nav-link-danger">Keluar</a>
    </aside>
    <main class="dashboard-main">
        <header class="dashboard-header">
            <h1 class="page-title">Manajemen Kategori</h1>
            <div class="user-pill">
                <span class="user-avatar"><?= strtoupper(($_SESSION['user_name'] ?? 'A')[0]) ?></span>
                <span class="user-name"><?= e($_SESSION['user_name'] ?? 'Admin') ?></span>
            </div>
        </header>

        <section class="dashboard-content">
            <div class="stats-grid">
                <div class="glass-card stats-card">
                    <h2 class="stats-label"><?= $editCategory ? 'Edit Kategori' : 'Tambah Kategori Baru' ?></h2>
                    <form method="post" class="form-vertical mt-2">
                        <input type="hidden" name="id" value="<?= $editCategory['id'] ?? 0 ?>">
                        <label class="form-label" for="name">Nama Kategori</label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            class="input-control"
                            value="<?= e($editCategory['name'] ?? '') ?>"
                            required
                        >
                        <button type="submit" class="btn-primary mt-3">
                            Simpan
                        </button>
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
                    <h2 class="stats-label">Daftar Kategori</h2>
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (count($categories) === 0): ?>
                                <tr>
                                    <td colspan="4">Belum ada kategori.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td><?= (int) $category['id'] ?></td>
                                        <td><?= e($category['name']) ?></td>
                                        <td><?= e($category['created_at']) ?></td>
                                        <td>
                                            <a href="/ladystyle-shop/dashboard/categories.php?edit=<?= (int) $category['id'] ?>" class="table-action">Edit</a>
                                            <a href="/ladystyle-shop/dashboard/categories.php?delete=<?= (int) $category['id'] ?>" class="table-action table-action-danger" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
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

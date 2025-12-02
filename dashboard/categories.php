<?php // dashboard/categories.php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

require_login();
$message = '';

// Handle POST Request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($name) {
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE categories SET name = :name WHERE id = :id');
            $stmt->execute(['name' => $name, 'id' => $id]);
            $message = 'Kategori berhasil diperbarui.';
        } else {
            $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (:name)');
            $stmt->execute(['name' => $name]);
            $message = 'Kategori baru ditambahkan.';
        }
    }
}

// Handle Delete & Edit
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM categories WHERE id = :id');
    $stmt->execute(['id' => (int)$_GET['delete']]);
    $message = 'Kategori dihapus.';
}

$editCategory = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :id');
    $stmt->execute(['id' => (int)$_GET['edit']]);
    $editCategory = $stmt->fetch();
}

$categories = $pdo->query('SELECT * FROM categories ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kategori - LadyStyle Admin</title>
    <?php require __DIR__ . '/../includes/head.php'; ?>
</head>
<body class="bg-gray-50/50">
    <div class="flex h-screen overflow-hidden">
        <?php require __DIR__ . '/../includes/sidebar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/50 p-6 md:p-12">
            <div class="max-w-5xl mx-auto">
                <header class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Kategori Produk</h1>
                </header>

                <?php if ($message): ?>
                    <div class="bg-green-50 text-green-700 px-4 py-3 rounded-xl mb-6 border border-green-100 text-sm font-medium">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <div class="grid md:grid-cols-[1fr_1.5fr] gap-8">
                    <div class="glass-panel p-6 rounded-3xl h-fit">
                        <h2 class="text-lg font-bold text-gray-800 mb-4"><?= $editCategory ? 'Edit Kategori' : 'Tambah Kategori' ?></h2>
                        <form method="post" class="space-y-4">
                            <input type="hidden" name="id" value="<?= $editCategory['id'] ?? 0 ?>">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Kategori</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($editCategory['name'] ?? '') ?>" required
                                       class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:ring-2 focus:ring-ls-200 outline-none transition">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 bg-gray-900 text-white py-3 rounded-xl font-medium hover:bg-ls-600 transition">Simpan</button>
                                <?php if ($editCategory): ?>
                                    <a href="categories.php" class="px-4 py-3 bg-gray-100 text-gray-600 rounded-xl font-medium hover:bg-gray-200">Batal</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <div class="glass-panel p-6 rounded-3xl">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left border-b border-gray-100">
                                        <th class="pb-3 text-xs font-bold text-gray-400 uppercase">ID</th>
                                        <th class="pb-3 text-xs font-bold text-gray-400 uppercase">Nama</th>
                                        <th class="pb-3 text-xs font-bold text-gray-400 uppercase text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php foreach ($categories as $cat): ?>
                                    <tr class="group hover:bg-gray-50/50 transition">
                                        <td class="py-4 text-gray-400">#<?= $cat['id'] ?></td>
                                        <td class="py-4 font-medium text-gray-700"><?= htmlspecialchars($cat['name']) ?></td>
                                        <td class="py-4 flex justify-end gap-2">
                                            <a href="?edit=<?= $cat['id'] ?>" class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition">Edit</a>
                                            <a href="?delete=<?= $cat['id'] ?>" onclick="return confirm('Hapus?')" class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">Hapus</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
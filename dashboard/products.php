<?php // dashboard/products.php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

require_login();
$message = '';
$msgType = '';

// Handle Search
$search = $_GET['q'] ?? '';
$searchQuery = $search ? "AND (p.name LIKE :search OR c.name LIKE :search)" : "";

// Handle POST (Tambah/Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'] ?? 0;
        $cat_id = $_POST['category_id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $desc = $_POST['description'];
        $img = $_POST['image'];
        $status = $_POST['status'];
        
        // Defaults
        $size = $_POST['size_available'] ?? 'All Size';
        $color = $_POST['color'] ?? 'Standard';

        $pdo->beginTransaction();

        if ($id > 0) {
            $sql = "UPDATE products SET category_id=?, name=?, price=?, stock=?, size_available=?, color=?, description=?, image=?, status=? WHERE id=?";
            $pdo->prepare($sql)->execute([$cat_id, $name, $price, $stock, $size, $color, $desc, $img, $status, $id]);
            $productId = $id;
            $message = "Produk berhasil diperbarui ?";
        } else {
            $sql = "INSERT INTO products (category_id, name, price, stock, size_available, color, description, image, status) VALUES (?,?,?,?,?,?,?,?,?)";
            $pdo->prepare($sql)->execute([$cat_id, $name, $price, $stock, $size, $color, $desc, $img, $status]);
            $productId = $pdo->lastInsertId();
            $message = "Produk baru berhasil ditambahkan ??";
        }

        // Handle Gallery
        if ($id > 0) {
            $pdo->prepare("DELETE FROM product_gallery WHERE product_id = ?")->execute([$productId]);
        }
        
        if (!empty($_POST['gallery_images'])) {
            $urls = explode("\n", $_POST['gallery_images']);
            $stmtG = $pdo->prepare("INSERT INTO product_gallery (product_id, image_url) VALUES (?, ?)");
            foreach ($urls as $url) {
                if (trim($url)) $stmtG->execute([$productId, trim($url)]);
            }
        }

        $pdo->commit();
        $msgType = 'success';
        
        // Clear edit state if success insert
        if ($id == 0) echo "<script>window.location.href='products.php';</script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Terjadi kesalahan: " . $e->getMessage();
        $msgType = 'error';
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    try {
        $pdo->prepare("DELETE FROM products WHERE id=?")->execute([(int)$_GET['delete']]);
        $message = "Produk berhasil dihapus dari katalog ???";
        $msgType = 'success';
    } catch (Exception $e) {
        $message = "Gagal hapus: Produk sedang digunakan di pesanan.";
        $msgType = 'error';
    }
}

// Fetch Data for Edit
$editItem = null;
$galleryText = "";
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([(int)$_GET['edit']]);
    $editItem = $stmt->fetch();
    
    if ($editItem) {
        $stmtG = $pdo->prepare("SELECT image_url FROM product_gallery WHERE product_id = ?");
        $stmtG->execute([(int)$_GET['edit']]);
        $galleryText = implode("\n", $stmtG->fetchAll(PDO::FETCH_COLUMN));
    }
}

// Fetch All Products & Categories
$sqlProducts = "SELECT p.*, c.name as c_name FROM products p JOIN categories c ON p.category_id = c.id WHERE 1=1 $searchQuery ORDER BY p.id DESC";
$stmtP = $pdo->prepare($sqlProducts);
if ($search) $stmtP->bindValue(':search', "%$search%");
$stmtP->execute();
$products = $stmtP->fetchAll();

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Master Produk - Admin</title>
    <?php require __DIR__ . '/../includes/head.php'; ?>
    <script>
        function toggleForm() {
            const form = document.getElementById('productForm');
            form.classList.toggle('hidden');
            if(!form.classList.contains('hidden')) {
                form.scrollIntoView({behavior: 'smooth'});
            }
        }

        function previewImage(input) {
            const preview = document.getElementById('mainPreview');
            const url = input.value;
            if(url) {
                preview.src = url;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>
</head>
<body class="bg-gray-50/50">
    <div class="flex h-screen overflow-hidden">
        <?php require __DIR__ . '/../includes/sidebar.php'; ?>
        
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/50 p-6 md:p-8">
            <div class="max-w-7xl mx-auto">
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Katalog Produk</h1>
                        <p class="text-gray-500 text-sm mt-1">Kelola inventaris fashion Anda dengan mudah.</p>
                    </div>
                    <div class="flex gap-3">
                        <form class="relative group">
                            <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari produk..." 
                                   class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-ls-200 focus:border-ls-400 outline-none w-64 transition-all">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </form>
                        <button onclick="toggleForm()" class="px-6 py-2.5 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-ls-600 hover:shadow-ls-500/30 transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <?= $editItem ? 'Edit Produk' : 'Tambah Baru' ?>
                        </button>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="animate-fade px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 font-bold text-sm shadow-sm <?= $msgType === 'success' ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-red-50 text-red-700 border border-red-100' ?>">
                        <?= $message ?>
                    </div>
                <?php endif; ?>

                <div id="productForm" class="<?= $editItem ? '' : 'hidden' ?> glass-panel p-8 rounded-[2rem] mb-10 border-2 border-white/50 animate-fade">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-ls-100 text-ls-600 flex items-center justify-center text-sm">?</span>
                            <?= $editItem ? 'Edit Data Produk' : 'Input Produk Baru' ?>
                        </h2>
                        <button onclick="toggleForm()" class="text-gray-400 hover:text-red-500 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>

                    <form method="post" class="grid lg:grid-cols-3 gap-8">
                        <input type="hidden" name="id" value="<?= $editItem['id'] ?? 0 ?>">
                        
                        <div class="lg:col-span-1 space-y-4">
                            <div class="aspect-[4/5] rounded-2xl bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center relative overflow-hidden group hover:border-ls-300 transition-colors">
                                <img id="mainPreview" src="<?= htmlspecialchars($editItem['image'] ?? '') ?>" class="<?= $editItem['image'] ? '' : 'hidden' ?> w-full h-full object-cover absolute inset-0 z-10">
                                <div class="text-center p-4">
                                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-xs text-gray-400 font-bold uppercase">Preview Gambar</p>
                                    <p class="text-[10px] text-gray-400 mt-1">Gambar akan muncul otomatis saat link diisi</p>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Link Gambar Utama</label>
                                <input type="text" name="image" value="<?= htmlspecialchars($editItem['image'] ?? '') ?>" oninput="previewImage(this)" required placeholder="https://..." class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:ring-2 focus:ring-ls-200 outline-none text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Galeri Tambahan (1 URL/Baris)</label>
                                <textarea name="gallery_images" rows="4" placeholder="https://img1.jpg&#10;https://img2.jpg" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:ring-2 focus:ring-ls-200 outline-none text-xs font-mono"><?= htmlspecialchars($galleryText) ?></textarea>
                            </div>
                        </div>

                        <div class="lg:col-span-2 space-y-5">
                            <div class="grid md:grid-cols-2 gap-5">
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Produk</label>
                                    <input type="text" name="name" value="<?= htmlspecialchars($editItem['name'] ?? '') ?>" required placeholder="Contoh: Dress Satin Premium" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:ring-2 focus:ring-ls-200 outline-none font-bold text-gray-800">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori</label>
                                    <div class="relative">
                                        <select name="category_id" required class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:ring-2 focus:ring-ls-200 outline-none appearance-none cursor-pointer">
                                            <?php foreach ($categories as $c): ?>
                                                <option value="<?= $c['id'] ?>" <?= ($editItem['category_id'] ?? 0) == $c['id'] ? 'selected' : '' ?>><?= $c['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="absolute right-4 top-3.5 pointer-events-none text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status Produk</label>
                                    <select name="status" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:ring-2 focus:ring-ls-200 outline-none cursor-pointer">
                                        <option value="active" <?= ($editItem['status'] ?? '') === 'active' ? 'selected' : '' ?>>Aktif (Tampil)</option>
                                        <option value="inactive" <?= ($editItem['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Nonaktif (Sembunyi)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Harga (Rp)</label>
                                    <input type="number" name="price" value="<?= $editItem['price'] ?? '' ?>" required placeholder="150000" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:ring-2 focus:ring-ls-200 outline-none">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Stok Awal</label>
                                    <input type="number" name="stock" value="<?= $editItem['stock'] ?? '' ?>" placeholder="100" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:ring-2 focus:ring-ls-200 outline-none">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Deskripsi Lengkap</label>
                                <textarea name="description" rows="5" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:ring-2 focus:ring-ls-200 outline-none leading-relaxed"><?= htmlspecialchars($editItem['description'] ?? '') ?></textarea>
                            </div>

                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                <button type="button" onclick="toggleForm()" class="px-6 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-100 transition">Batal</button>
                                <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-ls-500 to-ls-600 text-white font-bold shadow-lg shadow-ls-500/30 hover:shadow-xl hover:-translate-y-1 transition-all">
                                    Simpan Produk
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="glass-panel rounded-[2rem] overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-400">
                                    <th class="p-6 font-bold">Produk</th>
                                    <th class="p-6 font-bold">Kategori</th>
                                    <th class="p-6 font-bold">Harga</th>
                                    <th class="p-6 font-bold">Stok</th>
                                    <th class="p-6 font-bold">Status</th>
                                    <th class="p-6 font-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <?php if (empty($products)): ?>
                                    <tr><td colspan="6" class="p-10 text-center text-gray-400">Tidak ada produk ditemukan.</td></tr>
                                <?php endif; ?>
                                
                                <?php foreach ($products as $p): ?>
                                <tr class="hover:bg-gray-50/80 transition-colors group">
                                    <td class="p-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden border border-gray-200 group-hover:border-ls-300 transition-colors">
                                                <img src="<?= htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover">
                                            </div>
                                            <span class="font-bold text-gray-800 line-clamp-1"><?= htmlspecialchars($p['name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold">
                                            <?= htmlspecialchars($p['c_name']) ?>
                                        </span>
                                    </td>
                                    <td class="p-6 font-medium text-gray-600">Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                                    <td class="p-6">
                                        <?php if ($p['stock'] < 5): ?>
                                            <span class="text-red-500 font-bold"><?= $p['stock'] ?> (Low)</span>
                                        <?php else: ?>
                                            <span class="text-gray-600"><?= $p['stock'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-6">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold capitalize 
                                        <?= $p['status'] === 'active' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' ?>">
                                            <span class="w-1.5 h-1.5 rounded-full <?= $p['status'] === 'active' ? 'bg-green-500' : 'bg-gray-400' ?>"></span>
                                            <?= $p['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?>
                                        </span>
                                    </td>
                                    <td class="p-6 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="?edit=<?= $p['id'] ?>" class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </a>
                                            <a href="?delete=<?= $p['id'] ?>" onclick="return confirm('Hapus produk ini?')" class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center text-xs text-gray-400">
                        <span>Menampilkan <?= count($products) ?> produk</span>
                        <div class="flex gap-2">
                            <button disabled class="px-3 py-1 rounded-lg border bg-white disabled:opacity-50">Prev</button>
                            <button disabled class="px-3 py-1 rounded-lg border bg-white disabled:opacity-50">Next</button>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>

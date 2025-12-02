<?php // products.php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

// Logika Filter Kategori
$catId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$whereClauses = ["status = 'active'"];
$params = [];

if ($catId > 0) {
    $whereClauses[] = "category_id = ?";
    $params[] = $catId;
}

if ($search) {
    $whereClauses[] = "name LIKE ?";
    $params[] = "%$search%";
}

$whereSql = implode(' AND ', $whereClauses);
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE $whereSql 
        ORDER BY p.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Ambil Kategori untuk Menu Filter
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Koleksi Eksklusif - LadyStyle</title>
    <?php require __DIR__ . '/includes/head.php'; ?>
    <style>
        /* Hide scrollbar for category list */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans selection:bg-ls-200 selection:text-ls-900">

    <?php require __DIR__ . '/includes/navbar.php'; ?>

    <header class="relative pt-24 pb-12 px-4 text-center overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-gradient-to-r from-ls-200 to-purple-200 rounded-full blur-3xl opacity-30 -z-10 animate-pulse"></div>

        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-4">
            Koleksi <span class="text-transparent bg-clip-text bg-gradient-to-r from-ls-500 to-ls-600">Terbaru</span>
        </h1>
        <p class="text-gray-500 text-sm md:text-base max-w-xl mx-auto leading-relaxed">
            Temukan gaya fashion wanita modern dengan kualitas premium. Tampil percaya diri di setiap momen spesialmu.
        </p>

        <form class="max-w-md mx-auto mt-8 relative group">
            <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Cari dress, hijab, blouse..." 
                   class="w-full px-6 py-3.5 rounded-full border border-gray-200 bg-white/80 backdrop-blur shadow-sm focus:shadow-lg focus:border-ls-300 focus:ring-4 focus:ring-ls-100 transition-all outline-none text-sm font-medium pl-12">
            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2 group-focus-within:text-ls-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </form>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        
        <div class="flex items-center gap-3 overflow-x-auto no-scrollbar pb-8 justify-start md:justify-center">
            <a href="products.php" 
               class="flex-shrink-0 px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-300 transform hover:-translate-y-1 
               <?= $catId === 0 ? 'bg-gray-900 text-white shadow-lg shadow-gray-500/30' : 'bg-white text-gray-500 hover:text-gray-900 border border-gray-100 hover:shadow-md' ?>">
               Semua
            </a>
            <?php foreach ($categories as $cat): ?>
            <a href="?category=<?= $cat['id'] ?>" 
               class="flex-shrink-0 px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-300 transform hover:-translate-y-1 
               <?= $catId === $cat['id'] ? 'bg-ls-600 text-white shadow-lg shadow-ls-500/30' : 'bg-white text-gray-500 hover:text-ls-600 border border-gray-100 hover:shadow-md' ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($products)): ?>
            <div class="flex flex-col items-center justify-center py-20 text-center animate-fade">
                <div class="w-24 h-24 bg-ls-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-ls-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Produk Tidak Ditemukan</h3>
                <p class="text-gray-500 mt-2 max-w-xs mx-auto">Coba kata kunci lain atau cek kategori yang berbeda.</p>
                <a href="products.php" class="mt-6 px-6 py-2 rounded-full bg-white border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition">Reset Filter</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
                <?php foreach ($products as $p): ?>
                <div class="group relative bg-white rounded-[2rem] shadow-sm hover:shadow-xl hover:shadow-ls-500/10 transition-all duration-500 flex flex-col overflow-hidden border border-gray-50">
                    
                    <div class="relative w-full aspect-square overflow-hidden bg-gray-100">
                        <div class="absolute top-4 left-4 z-10">
                            <span class="px-3 py-1 rounded-full bg-white/90 backdrop-blur text-[10px] font-bold uppercase tracking-wider text-gray-800 shadow-sm">
                                <?= htmlspecialchars($p['category_name']) ?>
                            </span>
                        </div>

                        <?php if ($p['image']): ?>
                            <img src="<?= htmlspecialchars($p['image']) ?>" 
                                 class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110 group-hover:rotate-1" 
                                 alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        <?php endif; ?>

                        <?php if ($p['stock'] <= 0): ?>
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center backdrop-blur-sm">
                                <span class="px-4 py-2 bg-white text-gray-900 font-bold text-xs rounded-full uppercase tracking-widest shadow-lg">Habis</span>
                            </div>
                        <?php elseif ($p['stock'] < 5): ?>
                            <div class="absolute bottom-3 right-3">
                                <span class="flex items-center gap-1 px-2 py-1 rounded-md bg-red-500/90 text-white text-[10px] font-bold backdrop-blur shadow-sm animate-pulse">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Sisa <?= $p['stock'] ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 hidden md:block">
                            <a href="product_detail.php?id=<?= $p['id'] ?>" class="block w-full py-3 bg-white/95 backdrop-blur text-gray-900 font-bold text-xs text-center rounded-xl hover:bg-ls-600 hover:text-white transition-colors shadow-lg uppercase tracking-wider">
                                Lihat Detail
                            </a>
                        </div>
                    </div>

                    <div class="p-5 flex flex-col flex-1">
                        <h3 class="font-bold text-gray-900 text-sm md:text-base leading-snug mb-1 line-clamp-2 group-hover:text-ls-600 transition-colors">
                            <a href="product_detail.php?id=<?= $p['id'] ?>">
                                <?= htmlspecialchars($p['name']) ?>
                            </a>
                        </h3>
                        
                        <div class="mt-auto pt-3 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Harga</span>
                                <span class="text-ls-600 font-extrabold text-base md:text-lg">
                                    Rp <?= number_format($p['price'], 0, ',', '.') ?>
                                </span>
                            </div>
                            
                            <a href="product_detail.php?id=<?= $p['id'] ?>" class="md:hidden w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-ls-600 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

    <footer class="bg-white border-t border-gray-100 py-10 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="w-12 h-12 bg-ls-100 text-ls-600 rounded-full flex items-center justify-center mx-auto mb-4 font-bold text-lg">LS</div>
            <p class="text-gray-400 text-xs">&copy; <?= date('Y') ?> LadyStyle Shop. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
'@
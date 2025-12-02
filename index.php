<?php // index.php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';

// 1. Ambil Pengaturan Banner
$landing = $pdo->query("SELECT * FROM landing_settings WHERE id=1")->fetch();

// 2. Ambil Produk "Terbaru Minggu Ini" (Limit 4)
$stmtLatest = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE status = 'active' ORDER BY p.id DESC LIMIT 4");
$latestProducts = $stmtLatest->fetchAll();

// 3. Logika Pagination untuk "Semua Produk"
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 8; // Menampilkan 8 produk per halaman
$offset = ($page - 1) * $limit;

// Hitung Total Produk (untuk pagination)
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products WHERE status = 'active'")->fetchColumn();
$totalPages = ceil($totalProducts / $limit);

// Ambil Data "Semua Produk" dengan Limit & Offset
$sqlAll = "SELECT p.*, c.name as category_name 
           FROM products p 
           JOIN categories c ON p.category_id = c.id 
           WHERE status = 'active' 
           ORDER BY p.id DESC 
           LIMIT :limit OFFSET :offset";
$stmtAll = $pdo->prepare($sqlAll);
$stmtAll->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmtAll->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtAll->execute();
$allProducts = $stmtAll->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>LadyStyle Shop - Fashion Wanita Kekinian</title>
    <?php require __DIR__ . '/includes/head.php'; ?>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeInUp 0.8s ease-out forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <?php require __DIR__ . '/includes/navbar.php'; ?>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6 order-2 md:order-1 animate-fade-up">
                <span class="inline-block px-4 py-2 rounded-full bg-ls-50 text-ls-600 text-xs font-bold uppercase tracking-wider border border-ls-100 shadow-sm">
                    ? New Collection Available
                </span>
                <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 leading-tight">
                    <?= htmlspecialchars($landing['hero_title']) ?>
                </h1>
                <p class="text-lg text-gray-500 max-w-md leading-relaxed">
                    <?= htmlspecialchars($landing['hero_subtitle']) ?>
                </p>
                <div class="flex gap-4 pt-4">
                    <a href="<?= htmlspecialchars($landing['cta_link']) ?>" class="px-8 py-4 rounded-full bg-gray-900 text-white font-bold shadow-xl hover:shadow-2xl hover:bg-ls-600 hover:-translate-y-1 transition-all duration-300">
                        <?= htmlspecialchars($landing['cta_text']) ?>
                    </a>
                </div>
            </div>
            <div class="relative order-1 md:order-2 animate-fade-up delay-200">
                <div class="absolute -inset-4 bg-gradient-to-r from-ls-200 to-purple-200 rounded-[3rem] blur-3xl opacity-50 animate-pulse"></div>
                <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl rotate-2 hover:rotate-0 transition-all duration-700 group">
                    <img src="<?= htmlspecialchars($landing['hero_image']) ?>" class="w-full h-[500px] object-cover object-top group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute bottom-0 inset-x-0 h-32 bg-gradient-to-t from-black/60 to-transparent"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 border-b border-gray-200">
        <div class="flex justify-between items-end mb-10 animate-fade-up">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Terbaru Minggu Ini</h2>
                <p class="text-gray-500 mt-2">Koleksi paling anyar yang baru saja mendarat.</p>
            </div>
            <a href="products.php" class="hidden md:inline-flex items-center gap-2 text-sm font-bold text-ls-600 hover:text-ls-700 transition">
                Lihat Katalog <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 animate-fade-up delay-100">
            <?php foreach ($latestProducts as $p): ?>
            <a href="product_detail.php?id=<?= $p['id'] ?>" class="group block relative">
                <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl hover:shadow-ls-500/20 transition-all duration-500 border border-gray-100">
                    <div class="relative w-full aspect-square overflow-hidden bg-gray-100">
                        <span class="absolute top-3 left-3 z-10 px-2 py-1 rounded-full bg-white/90 backdrop-blur text-[10px] font-bold uppercase tracking-wider text-gray-800 shadow-sm">
                            <?= htmlspecialchars($p['category_name']) ?>
                        </span>
                        <img src="<?= htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110" alt="<?= htmlspecialchars($p['name']) ?>">
                        
                        <?php if ($p['stock'] < 5): ?>
                            <div class="absolute bottom-3 right-3">
                                <span class="px-2 py-1 rounded-md bg-red-500/90 text-white text-[10px] font-bold backdrop-blur shadow-sm animate-pulse">Sisa <?= $p['stock'] ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <span class="px-4 py-2 bg-white/90 rounded-full text-xs font-bold text-gray-900 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                Lihat Detail
                            </span>
                        </div>
                    </div>
                    <div class="p-5 text-center">
                        <h3 class="font-bold text-gray-900 text-sm mb-1 line-clamp-1 group-hover:text-ls-600 transition-colors">
                            <?= htmlspecialchars($p['name']) ?>
                        </h3>
                        <p class="text-ls-600 font-extrabold text-base">Rp <?= number_format($p['price'], 0, ',', '.') ?></p>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="all-products" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 bg-gray-50/50">
        <div class="text-center mb-12 animate-fade-up">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">Our Catalog</span>
            <h2 class="text-3xl font-bold text-gray-900 mt-2">Semua Produk</h2>
        </div>

        <?php if (empty($allProducts)): ?>
            <div class="text-center py-10 text-gray-400">Belum ada produk tambahan.</div>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 animate-fade-up delay-100">
                <?php foreach ($allProducts as $p): ?>
                <a href="product_detail.php?id=<?= $p['id'] ?>" class="group block bg-white rounded-3xl p-3 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-ls-200">
                    <div class="relative w-full aspect-square rounded-2xl overflow-hidden bg-gray-100 mb-4">
                        <img src="<?= htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php if ($p['stock'] <= 0): ?>
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                <span class="px-3 py-1 bg-white rounded-full text-xs font-bold uppercase">Habis</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="px-1">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-gray-800 text-sm line-clamp-1 group-hover:text-ls-600 transition"><?= htmlspecialchars($p['name']) ?></h3>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-gray-500 text-xs"><?= htmlspecialchars($p['category_name']) ?></p>
                            <p class="font-bold text-ls-600 text-sm">Rp <?= number_format($p['price'], 0, ',', '.') ?></p>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
            <div class="mt-16 flex justify-center gap-2 animate-fade-up">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>#all-products" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-ls-600 hover:text-white hover:border-ls-600 transition shadow-sm">
                        &larr;
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>#all-products" class="w-10 h-10 flex items-center justify-center rounded-full font-bold text-sm transition shadow-sm <?= $i == $page ? 'bg-ls-600 text-white shadow-ls-500/30' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>#all-products" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-ls-600 hover:text-white hover:border-ls-600 transition shadow-sm">
                        &rarr;
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        <?php endif; ?>
    </section>

    <footer class="bg-white border-t border-gray-100 pt-16 pb-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="inline-flex items-center gap-2 mb-6">
                <div class="w-8 h-8 rounded-full bg-ls-600 flex items-center justify-center text-white font-bold text-sm">LS</div>
                <span class="font-bold text-xl tracking-tight text-gray-800">LadyStyle</span>
            </div>
            <p class="text-gray-400 text-sm mb-8 max-w-sm mx-auto">Platform fashion wanita modern dengan koleksi terlengkap dan pengalaman belanja yang menyenangkan.</p>
            <div class="flex justify-center gap-6 text-sm font-bold text-gray-500 mb-8">
                <a href="https://www.instagram.com/manchesterunited/" class="hover:text-ls-600 transition" target="_blank">Instagram</a>
                <a href="#" class="hover:text-ls-600 transition">WhatsApp</a>
                <a href="https://www.youtube.com/watch?v=JuRD8gSdmps&list=RDJuRD8gSdmps&start_radio=1" class="hover:text-ls-600 transition" target="_blank">Tentang Kami</a>
            </div>
            <p class="text-gray-300 text-xs">&copy; <?= date('Y') ?> LadyStyle Shop Project. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

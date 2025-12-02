<?php // product_detail.php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) { echo "Produk tidak ditemukan"; exit; }

// Fetch Gallery (Gabungkan Main Image + Gallery untuk Slider)
$sliderImages = [$product['image']]; 
$stmtG = $pdo->prepare("SELECT image_url FROM product_gallery WHERE product_id = ?");
$stmtG->execute([$id]);
$galleryData = $stmtG->fetchAll(PDO::FETCH_COLUMN);
$sliderImages = array_merge($sliderImages, $galleryData);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= htmlspecialchars($product['name']) ?> - LadyStyle</title>
    <?php require __DIR__ . '/includes/head.php'; ?>
    <style>
        /* Custom Scrollbar Hide */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Line Clamp Custom */
        .line-clamp-custom {
            display: -webkit-box;
            -webkit-line-clamp: 4; /* Tampilkan 4 baris awal */
            -webkit-box-orient: vertical;
            overflow: hidden;
            mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
        }
        .expanded {
            -webkit-line-clamp: unset;
            mask-image: none;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <?php require __DIR__ . '/includes/navbar.php'; ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 pb-24">
        
        <nav class="flex text-sm text-gray-500 mb-6">
            <a href="index.php" class="hover:text-ls-600 transition">Beranda</a>
            <span class="mx-2">/</span>
            <a href="products.php" class="hover:text-ls-600 transition">Koleksi</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 font-medium truncate"><?= htmlspecialchars($product['name']) ?></span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <div class="lg:col-span-8 space-y-10">
                
                <div class="space-y-4">
                    <div class="relative group rounded-[2rem] overflow-hidden bg-white shadow-sm border border-gray-100">
                        <div id="productSlider" class="flex overflow-x-auto hide-scroll snap-x snap-mandatory scroll-smooth w-full aspect-square">
                            <?php foreach ($sliderImages as $idx => $img): ?>
                            <div class="w-full flex-shrink-0 snap-center relative">
                                <img src="<?= htmlspecialchars($img) ?>" class="w-full h-full object-cover" id="slide-<?= $idx ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if(count($sliderImages) > 1): ?>
                        <button onclick="scrollSlider(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 backdrop-blur rounded-full flex items-center justify-center text-gray-800 hover:bg-white shadow-lg transition opacity-0 group-hover:opacity-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button onclick="scrollSlider(1)" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 backdrop-blur rounded-full flex items-center justify-center text-gray-800 hover:bg-white shadow-lg transition opacity-0 group-hover:opacity-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <?php endif; ?>

                        <div class="absolute top-4 left-4 flex gap-2">
                            <span class="px-3 py-1 rounded-full bg-white/90 backdrop-blur text-xs font-bold text-gray-800 shadow-sm uppercase tracking-wider">
                                <?= htmlspecialchars($product['category_name']) ?>
                            </span>
                            <?php if ($product['stock'] < 5): ?>
                                <span class="px-3 py-1 rounded-full bg-red-500/90 backdrop-blur text-xs font-bold text-white shadow-sm animate-pulse">
                                    Sisa <?= $product['stock'] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if(count($sliderImages) > 1): ?>
                    <div class="flex gap-3 overflow-x-auto hide-scroll pb-2">
                        <?php foreach ($sliderImages as $idx => $img): ?>
                        <button onclick="goToSlide(<?= $idx ?>)" class="relative w-20 h-20 flex-shrink-0 rounded-xl overflow-hidden border-2 border-transparent hover:border-ls-400 focus:border-ls-600 transition-all">
                            <img src="<?= htmlspecialchars($img) ?>" class="w-full h-full object-cover">
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="glass-panel p-8 rounded-3xl border-t-4 border-ls-500">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-ls-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Detail & Spesifikasi
                    </h3>
                    
                    <div class="relative">
                        <div id="descContent" class="text-gray-600 leading-relaxed whitespace-pre-line text-sm md:text-base line-clamp-custom transition-all duration-500">
                            <?= htmlspecialchars($product['description']) ?>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <button id="toggleDescBtn" onclick="toggleDescription()" class="inline-flex items-center gap-2 text-sm font-bold text-ls-600 hover:text-ls-800 transition">
                                <span>Lihat Selengkapnya</span>
                                <svg id="toggleIcon" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-4 relative">
                <div class="sticky top-24 space-y-6">
                    
                    <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100">
                        <h1 class="text-2xl font-bold text-gray-900 leading-tight mb-2"><?= htmlspecialchars($product['name']) ?></h1>
                        
                        <div class="flex items-end gap-2 mb-6 pb-6 border-b border-gray-100">
                            <span class="text-3xl font-extrabold text-ls-600">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                        </div>

                        <form action="checkout.php" method="GET" class="space-y-6">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 block">Pilih Ukuran</label>
                                <div class="flex flex-wrap gap-2">
                                    <?php 
                                    $sizes = explode(',', $product['size_available'] ?: 'All Size');
                                    foreach($sizes as $idx => $size): 
                                    ?>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="size" class="peer sr-only" value="<?= trim($size) ?>" <?= $idx===0 ? 'checked' : '' ?>>
                                            <div class="px-4 py-2 rounded-xl border border-gray-200 text-sm font-bold text-gray-500 peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900 transition hover:border-gray-400">
                                                <?= trim($size) ?>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 block">Jumlah Barang</label>
                                <div class="flex items-center justify-between bg-gray-50 rounded-2xl p-1 border border-gray-200">
                                    <button type="button" onclick="updateQty(-1)" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-gray-600 shadow-sm hover:text-red-500 transition font-bold text-lg">-</button>
                                    <input type="number" name="qty" id="qtyInput" value="1" min="1" max="<?= $product['stock'] ?>" class="w-16 text-center bg-transparent border-none focus:ring-0 font-bold text-gray-900 text-lg" readonly>
                                    <button type="button" onclick="updateQty(1)" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-gray-600 shadow-sm hover:text-green-500 transition font-bold text-lg">+</button>
                                </div>
                                <p class="text-xs text-gray-400 mt-2 text-right">Stok tersedia: <?= $product['stock'] ?></p>
                            </div>

                            <div class="pt-2">
                                <?php if ($product['stock'] > 0): ?>
                                    <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-ls-500 to-ls-600 text-white font-bold text-lg shadow-lg shadow-ls-500/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                                        Pesan Sekarang
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                <?php else: ?>
                                    <button type="button" disabled class="w-full py-4 rounded-xl bg-gray-200 text-gray-400 font-bold text-lg cursor-not-allowed">
                                        Stok Habis
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <div class="glass-panel p-5 rounded-2xl grid grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-xs font-bold text-gray-600">Garansi Original</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="text-xs font-bold text-gray-600">Kirim Cepat</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <script>
        // --- Slider Logic ---
        function scrollSlider(direction) {
            const container = document.getElementById('productSlider');
            const scrollAmount = container.offsetWidth;
            container.scrollBy({ left: scrollAmount * direction, behavior: 'smooth' });
        }

        function goToSlide(index) {
            const container = document.getElementById('productSlider');
            const scrollAmount = container.offsetWidth;
            container.scrollTo({ left: scrollAmount * index, behavior: 'smooth' });
        }

        // --- Description Toggle ---
        function toggleDescription() {
            const content = document.getElementById('descContent');
            const btnText = document.querySelector('#toggleDescBtn span');
            const icon = document.getElementById('toggleIcon');
            
            content.classList.toggle('expanded');
            content.classList.toggle('line-clamp-custom');
            
            if (content.classList.contains('expanded')) {
                btnText.innerText = "Tutup Deskripsi";
                icon.style.transform = "rotate(180deg)";
            } else {
                btnText.innerText = "Lihat Selengkapnya";
                icon.style.transform = "rotate(0deg)";
            }
        }

        // --- Quantity Logic ---
        function updateQty(change) {
            const input = document.getElementById('qtyInput');
            let val = parseInt(input.value) + change;
            if (val < 1) val = 1;
            if (val > <?= $product['stock'] ?>) val = <?= $product['stock'] ?>;
            input.value = val;
        }
    </script>
</body>
</html>

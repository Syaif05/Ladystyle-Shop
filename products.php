<?php // products.php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/functions.php';

$stmt = $pdo->query('SELECT * FROM categories ORDER BY name ASC');
$categories = $stmt->fetchAll();

$selectedCategoryId = isset($_GET['category']) ? (int) $_GET['category'] : 0;
$params = [];
$where = ' WHERE p.status = "active"';

if ($selectedCategoryId > 0) {
    $where .= ' AND p.category_id = :category_id';
    $params['category_id'] = $selectedCategoryId;
}

$sql = 'SELECT p.*, c.name AS category_name FROM products p INNER JOIN categories c ON p.category_id = c.id' . $where . ' ORDER BY p.id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Koleksi Produk - LadyStyle Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ls-bg': '#fdf2f8',
                        'ls-bg-soft': '#fee2f2',
                        'ls-pink': '#fb7185',
                        'ls-pink-soft': '#fecdd3',
                        'ls-ink': '#0f172a'
                    },
                    boxShadow: {
                        'ls-soft': '0 18px 45px rgba(251, 113, 133, 0.35)'
                    },
                    borderRadius: {
                        '3xl': '1.75rem'
                    }
                }
            }
        };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="/ladystyle-shop/assets/css/style.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-b from-white via-ls-bg-soft to-white text-ls-ink">
<div class="relative overflow-hidden">
    <div class="pointer-events-none absolute -top-40 -left-40 h-80 w-80 rounded-full bg-ls-bg blur-3xl opacity-70"></div>
    <div class="pointer-events-none absolute -bottom-40 -right-32 h-96 w-96 rounded-full bg-ls-bg-soft blur-3xl opacity-80"></div>

    <header class="sticky top-0 z-20 bg-white/70 backdrop-blur-xl border-b border-pink-100/70">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
            <div class="flex items-center gap-2">
                <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-gradient-to-tr from-ls-pink to-rose-400 shadow-ls-soft">
                    <span class="text-xs font-semibold tracking-widest text-white">LS</span>
                </div>
                <div class="leading-tight">
                    <p class="text-sm font-semibold tracking-[0.18em] text-ls-ink/80 uppercase">LadyStyle</p>
                    <p class="text-[11px] text-ls-ink/60">Soft & Modern Fashion Store</p>
                </div>
            </div>
            <div class="hidden items-center gap-6 text-sm font-medium text-ls-ink/70 md:flex">
                <a href="/ladystyle-shop/index.php" class="transition-colors hover:text-ls-pink">Beranda</a>
                <a href="/ladystyle-shop/products.php" class="transition-colors text-ls-pink">Koleksi</a>
                <a href="#filter" class="transition-colors hover:text-ls-pink">Filter</a>
            </div>
            <div class="flex items-center gap-2">
                <a href="/ladystyle-shop/login.php" class="hidden rounded-full border border-pink-200 px-3 py-1.5 text-xs font-medium text-ls-ink/80 shadow-sm transition hover:border-ls-pink hover:bg-ls-pink-soft/50 md:inline-flex">
                    Login Admin
                </a>
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-6xl px-4 pb-16 pt-8">
        <section class="space-y-2">
            <p class="text-xs font-semibold tracking-[0.22em] text-ls-pink uppercase">Koleksi</p>
            <h1 class="text-2xl font-semibold text-ls-ink sm:text-3xl">Semua produk LadyStyle</h1>
            <p class="max-w-xl text-sm text-ls-ink/70">
                Pilih kategori di bawah ini untuk memfilter produk. Desain lembut, warna pastel, dan nyaman dipakai untuk berbagai suasana.
            </p>
        </section>

        <section id="filter" class="mt-6 space-y-4">
            <div class="flex flex-wrap items-center gap-2">
                <a href="/ladystyle-shop/products.php" class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs font-medium transition
                    <?php if ($selectedCategoryId === 0): ?>
                        border-ls-pink bg-ls-pink-soft text-ls-ink
                    <?php else: ?>
                        border-pink-200 bg-white/80 text-ls-ink/70 hover:border-ls-pink hover:bg-ls-bg-soft
                    <?php endif; ?>
                ">
                    Semua
                </a>
                <?php foreach ($categories as $category): ?>
                    <a href="/ladystyle-shop/products.php?category=<?= (int) $category['id'] ?>" class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs font-medium transition
                        <?php if ($selectedCategoryId === (int) $category['id']): ?>
                            border-ls-pink bg-ls-pink-soft text-ls-ink
                        <?php else: ?>
                            border-pink-200 bg-white/80 text-ls-ink/70 hover:border-ls-pink hover:bg-ls-bg-soft
                        <?php endif; ?>
                    ">
                        <?= e($category['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="mt-6">
            <?php if (count($products) === 0): ?>
                <div class="mt-6 rounded-3xl bg-white/90 p-6 text-sm text-ls-ink/70 shadow-md ring-1 ring-pink-100">
                    Belum ada produk tersedia untuk kategori ini.
                </div>
            <?php else: ?>
                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($products as $product): ?>
                        <article class="group rounded-3xl bg-white/95 p-3 shadow-md ring-1 ring-pink-100/80 transition hover:-translate-y-1 hover:shadow-ls-soft hover:ring-ls-pink">
                            <div class="relative overflow-hidden rounded-2xl bg-ls-bg-soft">
                                <?php if ($product['image']): ?>
                                    <div class="h-40 w-full bg-cover bg-center" style="background-image:url('<?= e($product['image']) ?>');"></div>
                                <?php else: ?>
                                    <div class="flex h-40 w-full items-center justify-center bg-[radial-gradient(circle_at_20%_20%,#fecdd3,#fee2e2)] text-xs font-medium text-ls-ink/60">
                                        <?= e($product['category_name']) ?>
                                    </div>
                                <?php endif; ?>
                                <span class="absolute left-3 top-3 rounded-full bg-white/85 px-2 py-1 text-[10px] font-medium text-ls-pink">
                                    <?= e($product['category_name']) ?>
                                </span>
                            </div>
                            <div class="mt-3 space-y-1">
                                <h2 class="text-sm font-semibold text-ls-ink line-clamp-2"><?= e($product['name']) ?></h2>
                                <p class="text-xs text-ls-ink/60">
                                    Stok: <?= (int) $product['stock'] ?> •
                                    <?= $product['size_available'] ? 'Size: ' . e($product['size_available']) : 'Size all' ?>
                                </p>
                                <p class="text-sm font-semibold text-ls-pink"><?= format_rupiah((int) $product['price']) ?></p>
                            </div>
                            <div class="mt-3 flex items-center justify-between gap-2">
                                <a href="/ladystyle-shop/product_detail.php?id=<?= (int) $product['id'] ?>" class="inline-flex flex-1 items-center justify-center rounded-full bg-ls-pink px-3 py-1.5 text-xs font-semibold text-white shadow-md transition hover:bg-rose-500">
                                    Lihat detail
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>
</body>
</html>

<?php // product_detail.php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(404);
    echo 'Produk tidak ditemukan.';
    exit;
}

$stmt = $pdo->prepare('SELECT p.*, c.name AS category_name FROM products p INNER JOIN categories c ON p.category_id = c.id WHERE p.id = :id AND p.status = "active"');
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    echo 'Produk tidak ditemukan.';
    exit;
}

$orderSuccess = '';
$orderError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerName = trim($_POST['customer_name'] ?? '');
    $customerPhone = trim($_POST['customer_phone'] ?? '');
    $customerAddress = trim($_POST['customer_address'] ?? '');
    $size = trim($_POST['size'] ?? '');
    $qty = (int) ($_POST['qty'] ?? 1);

    if ($customerName === '' || $customerPhone === '' || $customerAddress === '' || $qty <= 0) {
        $orderError = 'Nama, nomor WhatsApp, alamat, dan jumlah wajib diisi.';
    } else {
        $totalPrice = $product['price'] * $qty;
        $orderCode = 'ORD-' . date('Ymd-His') . '-' . $product['id'];

        $stmt = $pdo->prepare('INSERT INTO orders (order_code, product_id, customer_name, customer_phone, customer_address, size, qty, total_price, status) VALUES (:order_code, :product_id, :customer_name, :customer_phone, :customer_address, :size, :qty, :total_price, :status)');
        $stmt->execute([
            'order_code' => $orderCode,
            'product_id' => $product['id'],
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'customer_address' => $customerAddress,
            'size' => $size,
            'qty' => $qty,
            'total_price' => $totalPrice,
            'status' => 'baru'
        ]);

        $orderSuccess = 'Pesanan berhasil dibuat dengan kode ' . $orderCode . '. Admin akan menghubungi kamu melalui WhatsApp.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= e($product['name']) ?> - LadyStyle Shop</title>
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
                <a href="/ladystyle-shop/products.php" class="transition-colors hover:text-ls-pink">Koleksi</a>
            </div>
            <div class="flex items-center gap-2">
                <a href="/ladystyle-shop/login.php" class="hidden rounded-full border border-pink-200 px-3 py-1.5 text-xs font-medium text-ls-ink/80 shadow-sm transition hover:border-ls-pink hover:bg-ls-pink-soft/50 md:inline-flex">
                    Login Admin
                </a>
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-6xl px-4 pb-16 pt-8">
        <section class="grid gap-8 md:grid-cols-[1.1fr_1.1fr] md:items-start">
            <div class="space-y-4">
                <div class="rounded-3xl bg-white/90 p-3 shadow-md ring-1 ring-pink-100/80">
                    <div class="overflow-hidden rounded-2xl bg-ls-bg-soft">
                        <?php if ($product['image']): ?>
                            <div class="h-72 w-full bg-cover bg-center sm:h-80" style="background-image:url('<?= e($product['image']) ?>');"></div>
                        <?php else: ?>
                            <div class="flex h-72 w-full items-center justify-center bg-[radial-gradient(circle_at_20%_20%,#fecdd3,#fee2e2)] text-sm font-medium text-ls-ink/60 sm:h-80">
                                <?= e($product['category_name']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="hidden rounded-3xl bg-white/90 p-4 text-xs text-ls-ink/70 shadow-md ring-1 ring-pink-100 md:block">
                    <p class="font-semibold text-ls-ink/80">Catatan styling:</p>
                    <p class="mt-1">
                        Kamu bisa jelaskan di laporan bahwa halaman ini tidak hanya menampilkan data produk,
                        tapi juga simulasi pengalaman belanja: lihat foto produk, info ukuran, warna, dan langsung pesan.
                    </p>
                </div>
            </div>

            <div class="space-y-5">
                <div class="rounded-3xl bg-white/95 p-5 shadow-md ring-1 ring-pink-100/90">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold tracking-[0.22em] text-ls-pink uppercase">
                                <?= e($product['category_name']) ?>
                            </p>
                            <h1 class="mt-1 text-xl font-semibold text-ls-ink sm:text-2xl">
                                <?= e($product['name']) ?>
                            </h1>
                        </div>
                        <div class="rounded-full bg-ls-bg-soft px-3 py-1 text-xs text-ls-ink/70">
                            ID: <?= (int) $product['id'] ?>
                        </div>
                    </div>

                    <p class="mt-3 text-lg font-semibold text-ls-pink">
                        <?= format_rupiah((int) $product['price']) ?>
                    </p>

                    <div class="mt-2 flex flex-wrap gap-3 text-xs text-ls-ink/70">
                        <span class="inline-flex items-center rounded-full bg-ls-bg-soft px-3 py-1">
                            Stok: <?= (int) $product['stock'] ?>
                        </span>
                        <?php if ($product['size_available']): ?>
                            <span class="inline-flex items-center rounded-full bg-ls-bg-soft px-3 py-1">
                                Size: <?= e($product['size_available']) ?>
                            </span>
                        <?php endif; ?>
                        <?php if ($product['color']): ?>
                            <span class="inline-flex items-center rounded-full bg-ls-bg-soft px-3 py-1">
                                Warna: <?= e($product['color']) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($product['description']): ?>
                        <p class="mt-4 text-sm leading-relaxed text-ls-ink/80">
                            <?= nl2br(e($product['description'])) ?>
                        </p>
                    <?php endif; ?>
                </div>

                <div class="rounded-3xl bg-white/95 p-5 shadow-md ring-1 ring-pink-100/90">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-ls-ink sm:text-base">Form pemesanan</h2>
                        <span class="rounded-full bg-ls-bg-soft px-3 py-1 text-[11px] font-medium text-ls-pink">
                            Pesanan dikirim ke admin
                        </span>
                    </div>

                    <?php if ($orderError !== ''): ?>
                        <div class="mt-3 rounded-2xl bg-red-50 px-3 py-2 text-xs text-red-700 border border-red-100">
                            <?= e($orderError) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($orderSuccess !== ''): ?>
                        <div class="mt-3 rounded-2xl bg-emerald-50 px-3 py-2 text-xs text-emerald-700 border border-emerald-100">
                            <?= e($orderSuccess) ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" class="mt-4 space-y-3 text-sm">
                        <div>
                            <label for="customer_name" class="mb-1 block text-xs font-medium text-ls-ink/80">Nama lengkap</label>
                            <input id="customer_name" name="customer_name" type="text" required
                                   class="w-full rounded-2xl border border-pink-100 bg-white/90 px-3 py-2 text-sm text-ls-ink shadow-sm outline-none ring-0 transition focus:border-ls-pink focus:ring-2 focus:ring-ls-pink-soft">
                        </div>

                        <div>
                            <label for="customer_phone" class="mb-1 block text-xs font-medium text-ls-ink/80">No. WhatsApp</label>
                            <input id="customer_phone" name="customer_phone" type="text" required
                                   class="w-full rounded-2xl border border-pink-100 bg-white/90 px-3 py-2 text-sm text-ls-ink shadow-sm outline-none ring-0 transition focus:border-ls-pink focus:ring-2 focus:ring-ls-pink-soft">
                        </div>

                        <div>
                            <label for="customer_address" class="mb-1 block text-xs font-medium text-ls-ink/80">Alamat lengkap</label>
                            <textarea id="customer_address" name="customer_address" rows="3" required
                                      class="w-full rounded-2xl border border-pink-100 bg-white/90 px-3 py-2 text-sm text-ls-ink shadow-sm outline-none ring-0 transition focus:border-ls-pink focus:ring-2 focus:ring-ls-pink-soft"></textarea>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label for="size" class="mb-1 block text-xs font-medium text-ls-ink/80">Ukuran (opsional)</label>
                                <input id="size" name="size" type="text" placeholder="Contoh: M"
                                       class="w-full rounded-2xl border border-pink-100 bg-white/90 px-3 py-2 text-sm text-ls-ink shadow-sm outline-none ring-0 transition focus:border-ls-pink focus:ring-2 focus:ring-ls-pink-soft">
                            </div>
                            <div>
                                <label for="qty" class="mb-1 block text-xs font-medium text-ls-ink/80">Jumlah</label>
                                <input id="qty" name="qty" type="number" min="1" value="1" required
                                       class="w-full rounded-2xl border border-pink-100 bg-white/90 px-3 py-2 text-sm text-ls-ink shadow-sm outline-none ring-0 transition focus:border-ls-pink focus:ring-2 focus:ring-ls-pink-soft">
                            </div>
                        </div>

                        <button type="submit" class="mt-2 inline-flex w-full items-center justify-center rounded-full bg-ls-pink px-4 py-2.5 text-sm font-semibold text-white shadow-ls-soft transition hover:bg-rose-500 active:scale-95">
                            Kirim pesanan
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>

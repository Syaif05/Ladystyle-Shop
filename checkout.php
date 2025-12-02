<?php // checkout.php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

// Pastikan Login
if (!is_logged_in()) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login_customer.php');
    exit;
}

// Ambil Data Produk
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$qty = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) redirect('products.php');

// Ambil Data User (Alamat)
$userStmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$userStmt->execute([$_SESSION['user_id']]);
$user = $userStmt->fetch();

// Proses Submit Order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courier_data = explode('|', $_POST['shipping']); // Format: Courier|Service|Cost
    $payment_data = explode('|', $_POST['payment']);  // Format: Method|Fee

    $shipping_courier = $courier_data[0];
    $shipping_service = $courier_data[1];
    $shipping_cost = (int)$courier_data[2];
    
    $payment_method = $payment_data[0];
    $payment_fee = (int)$payment_data[1];
    
    $platform_fee = 1000;
    $item_total = $product['price'] * $qty;
    $grand_total = $item_total + $shipping_cost + $payment_fee + $platform_fee;

    $orderCode = 'INV-' . date('ymd') . rand(1000, 9999);

    // Simpan ke DB
    $sql = "INSERT INTO orders (order_code, product_id, customer_name, customer_phone, customer_address, qty, total_price, shipping_courier, shipping_service, shipping_cost, payment_method, payment_fee, platform_fee, grand_total, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'baru')";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $orderCode, $product['id'], $_POST['name'], $_POST['phone'], $_POST['address'], 
        $qty, $item_total, $shipping_courier, $shipping_service, $shipping_cost, 
        $payment_method, $payment_fee, $platform_fee, $grand_total
    ]);

    // Redirect Sukses
    echo "<script>alert('Pesanan Berhasil! Silakan lakukan pembayaran.'); window.location.href='profile.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Checkout - LadyStyle</title>
    <?php require __DIR__ . '/includes/head.php'; ?>
    <style>
        .radio-card:checked + div { border-color: #ec4899; background-color: #fdf2f8; box-shadow: 0 4px 6px -1px rgba(236, 72, 153, 0.1); }
        .radio-card:checked + div .check-icon { display: block; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    
    <nav class="bg-white border-b border-gray-100 py-4 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 flex items-center gap-3">
            <a href="product_detail.php?id=<?= $id ?>" class="text-gray-400 hover:text-gray-800"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a>
            <h1 class="text-lg font-bold text-gray-900">Checkout Pengiriman</h1>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 py-8">
        <form method="POST" id="checkoutForm" class="grid grid-cols-1 md:grid-cols-[1.5fr_1fr] gap-8">
            
            <div class="space-y-8">
                
                <section class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-ls-100 text-ls-600 flex items-center justify-center text-xs">1</span>
                        Alamat Pengiriman
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Penerima</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border-gray-200 text-sm font-medium">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">WhatsApp</label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border-gray-200 text-sm font-medium">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Alamat Lengkap</label>
                            <textarea name="address" required rows="2" class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border-gray-200 text-sm font-medium"><?= htmlspecialchars($user['address']) ?></textarea>
                        </div>
                    </div>
                </section>

                <section class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-ls-100 text-ls-600 flex items-center justify-center text-xs">2</span>
                        Pilih Pengiriman
                    </h2>
                    <div class="space-y-3">
                        <?php 
                        $couriers = [
                            ['JNE', 'Reguler', 10000, 'Estimasi 2-3 Hari'],
                            ['JNE', 'YES (Next Day)', 18000, 'Estimasi 1 Hari'],
                            ['J&T', 'EZ', 10000, 'Estimasi 2-3 Hari'],
                            ['SiCepat', 'Gokil (Cargo)', 25000, 'Min 5kg, Hemat'],
                            ['AnterAja', 'Reguler', 9000, 'Estimasi 2-4 Hari']
                        ];
                        foreach($couriers as $i => $c): 
                            $val = "$c[0]|$c[1]|$c[2]";
                        ?>
                        <label class="block cursor-pointer relative">
                            <input type="radio" name="shipping" value="<?= $val ?>" class="peer sr-only radio-card" required onchange="calculateTotal()" <?= $i===0 ? 'checked' : '' ?>>
                            <div class="p-4 rounded-2xl border border-gray-200 hover:bg-gray-50 transition flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center font-bold text-xs text-gray-500"><?= substr($c[0],0,3) ?></div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm"><?= $c[0] ?> - <?= $c[1] ?></p>
                                        <p class="text-xs text-gray-400"><?= $c[3] ?></p>
                                    </div>
                                </div>
                                <span class="font-bold text-gray-900 text-sm">Rp <?= number_format($c[2]) ?></span>
                            </div>
                            <div class="check-icon absolute top-1/2 right-4 -translate-y-1/2 text-ls-600 hidden">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-ls-100 text-ls-600 flex items-center justify-center text-xs">3</span>
                        Metode Pembayaran
                    </h2>
                    <div class="space-y-3">
                        <?php 
                        $payments = [
                            ['BCA Virtual Account', 1000],
                            ['Mandiri Virtual Account', 1000],
                            ['BRI Virtual Account', 1000],
                            ['QRIS (Gopay/Ovo/Dana)', 0],
                            ['ShopeePay', 500]
                        ];
                        foreach($payments as $i => $p): 
                            $val = "$p[0]|$p[1]";
                        ?>
                        <label class="block cursor-pointer relative">
                            <input type="radio" name="payment" value="<?= $val ?>" class="peer sr-only radio-card" required onchange="calculateTotal()" <?= $i===0 ? 'checked' : '' ?>>
                            <div class="p-4 rounded-2xl border border-gray-200 hover:bg-gray-50 transition flex justify-between items-center">
                                <span class="font-bold text-gray-900 text-sm"><?= $p[0] ?></span>
                                <?php if($p[1] > 0): ?>
                                    <span class="text-xs text-orange-500 bg-orange-50 px-2 py-1 rounded-md font-bold">+ Admin Rp <?= $p[1] ?></span>
                                <?php else: ?>
                                    <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-md font-bold">Bebas Biaya</span>
                                <?php endif; ?>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </section>

            </div>

            <div class="relative">
                <div class="sticky top-24 bg-white p-6 rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/50">
                    <h3 class="font-bold text-gray-900 mb-4">Ringkasan Belanja</h3>
                    
                    <div class="flex gap-4 mb-6 pb-6 border-b border-gray-100">
                        <img src="<?= htmlspecialchars($product['image']) ?>" class="w-16 h-16 rounded-xl object-cover bg-gray-100">
                        <div>
                            <h4 class="font-bold text-sm text-gray-800 line-clamp-2"><?= htmlspecialchars($product['name']) ?></h4>
                            <p class="text-xs text-gray-500 mt-1"><?= $qty ?> x Rp <?= number_format($product['price']) ?></p>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm mb-6">
                        <div class="flex justify-between text-gray-500">
                            <span>Total Harga (<?= $qty ?> Barang)</span>
                            <span class="font-medium text-gray-900" id="subtotalDisplay">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Total Ongkos Kirim</span>
                            <span class="font-medium text-gray-900" id="shippingDisplay">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Biaya Jasa Aplikasi</span>
                            <span class="font-medium text-gray-900">Rp 1.000</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Biaya Admin</span>
                            <span class="font-medium text-gray-900" id="adminFeeDisplay">Rp 0</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-900">Total Tagihan</span>
                            <span class="text-xl font-extrabold text-ls-600" id="grandTotalDisplay">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-xl bg-gray-900 text-white font-bold shadow-lg hover:bg-ls-600 transition-all duration-300 transform hover:-translate-y-1">
                        Buat Pesanan
                    </button>
                    
                    <p class="text-[10px] text-gray-400 text-center mt-4">
                        Dengan melanjutkan, Anda menyetujui Syarat & Ketentuan LadyStyle Shop.
                    </p>
                </div>
            </div>

        </form>
    </main>

    <script>
        const price = <?= $product['price'] ?>;
        const qty = <?= $qty ?>;
        const platformFee = 1000;

        function formatRupiah(num) {
            return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function calculateTotal() {
            // Get Shipping Cost
            const shipData = document.querySelector('input[name="shipping"]:checked').value.split('|');
            const shipCost = parseInt(shipData[2]);

            // Get Payment Fee
            const payData = document.querySelector('input[name="payment"]:checked').value.split('|');
            const payFee = parseInt(payData[1]);

            // Calc
            const subtotal = price * qty;
            const grandTotal = subtotal + shipCost + payFee + platformFee;

            // Update UI
            document.getElementById('subtotalDisplay').innerText = formatRupiah(subtotal);
            document.getElementById('shippingDisplay').innerText = formatRupiah(shipCost);
            document.getElementById('adminFeeDisplay').innerText = formatRupiah(payFee);
            document.getElementById('grandTotalDisplay').innerText = formatRupiah(grandTotal);
        }

        // Run on load
        calculateTotal();
    </script>
</body>
</html>
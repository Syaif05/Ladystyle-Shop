<?php // dashboard/orders.php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

require_login();
$message = '';

// Update Status Pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $allowed = ['baru', 'diproses', 'dikirim', 'selesai', 'dibatalkan'];

    if ($id > 0 && in_array($status, $allowed)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        $message = 'Status pesanan diperbarui.';
    }
}

// Hapus Pesanan
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM orders WHERE id = ?")->execute([(int)$_GET['delete']]);
    $message = 'Pesanan berhasil dihapus.';
}

// Ambil Data Pesanan
$orders = $pdo->query("
    SELECT o.*, p.name as product_name, p.image 
    FROM orders o 
    JOIN products p ON o.product_id = p.id 
    ORDER BY o.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pesanan - LadyStyle Admin</title>
    <?php require __DIR__ . '/../includes/head.php'; ?>
</head>
<body class="bg-gray-50/50">
    <div class="flex h-screen overflow-hidden">
        <?php require __DIR__ . '/../includes/sidebar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/50 p-6 md:p-12">
            <div class="max-w-6xl mx-auto">
                <header class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Pesanan Masuk</h1>
                    <p class="text-gray-500 mt-1">Kelola status pesanan pelanggan di sini.</p>
                </header>

                <?php if ($message): ?>
                    <div class="bg-green-50 text-green-700 px-4 py-3 rounded-xl mb-6 border border-green-100 text-sm font-medium">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <div class="glass-panel rounded-3xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100 text-left">
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Order ID</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Pelanggan</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Produk</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Total</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Status</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($orders as $o): ?>
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                            <?= htmlspecialchars($o['order_code']) ?>
                                        </span>
                                        <div class="text-[10px] text-gray-400 mt-1"><?= date('d M Y, H:i', strtotime($o['created_at'])) ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900"><?= htmlspecialchars($o['customer_name']) ?></div>
                                        <div class="text-xs text-gray-500"><?= htmlspecialchars($o['customer_phone']) ?></div>
                                        <div class="text-xs text-gray-400 truncate w-32" title="<?= htmlspecialchars($o['customer_address']) ?>">
                                            <?= htmlspecialchars($o['customer_address']) ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <?php if ($o['image']): ?>
                                                <img src="<?= htmlspecialchars($o['image']) ?>" class="w-10 h-10 rounded-lg object-cover shadow-sm">
                                            <?php else: ?>
                                                <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center text-[10px]">No Img</div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 line-clamp-1"><?= htmlspecialchars($o['product_name']) ?></div>
                                                <div class="text-xs text-gray-500">Qty: <?= $o['qty'] ?> • Size: <?= htmlspecialchars($o['size'] ?: '-') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-ls-600 text-sm">
                                        Rp <?= number_format($o['total_price'], 0, ',', '.') ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="post" class="flex items-center gap-2">
                                            <input type="hidden" name="id" value="<?= $o['id'] ?>">
                                            <select name="status" onchange="this.form.submit()" 
                                                class="text-xs font-bold px-3 py-1.5 rounded-full border-none outline-none cursor-pointer transition shadow-sm
                                                <?= match($o['status']) {
                                                    'baru' => 'bg-blue-50 text-blue-600 hover:bg-blue-100',
                                                    'diproses' => 'bg-orange-50 text-orange-600 hover:bg-orange-100',
                                                    'dikirim' => 'bg-purple-50 text-purple-600 hover:bg-purple-100',
                                                    'selesai' => 'bg-green-50 text-green-600 hover:bg-green-100',
                                                    'dibatalkan' => 'bg-red-50 text-red-600 hover:bg-red-100',
                                                    default => 'bg-gray-50 text-gray-600'
                                                } ?>">
                                                <option value="baru" <?= $o['status'] == 'baru' ? 'selected' : '' ?>>Baru</option>
                                                <option value="diproses" <?= $o['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                                <option value="dikirim" <?= $o['status'] == 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                                <option value="selesai" <?= $o['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                                <option value="dibatalkan" <?= $o['status'] == 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="?delete=<?= $o['id'] ?>" onclick="return confirm('Yakin hapus pesanan ini?')" 
                                           class="text-red-400 hover:text-red-600 p-2 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if (empty($orders)): ?>
                            <div class="p-10 text-center text-gray-400">Belum ada pesanan masuk.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
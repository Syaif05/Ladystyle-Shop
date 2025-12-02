<?php // dashboard/reports.php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

require_login();

// Filter Logic
$startDate = $_GET['start'] ?? date('Y-m-01'); // Default awal bulan ini
$endDate = $_GET['end'] ?? date('Y-m-d');      // Default hari ini
$status = $_GET['status'] ?? '';

$sql = "SELECT o.*, p.name as product_name 
        FROM orders o 
        JOIN products p ON o.product_id = p.id 
        WHERE DATE(o.created_at) BETWEEN :start AND :end";
$params = ['start' => $startDate, 'end' => $endDate];

if ($status) {
    $sql .= " AND o.status = :status";
    $params['status'] = $status;
}

$sql .= " ORDER BY o.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reports = $stmt->fetchAll();

// Hitung Ringkasan
$totalOmzet = 0;
$totalQty = 0;
foreach ($reports as $r) {
    if ($r['status'] !== 'dibatalkan') { // Hanya hitung yang valid
        $totalOmzet += $r['total_price'];
        $totalQty += $r['qty'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Laporan - LadyStyle Admin</title>
    <?php require __DIR__ . '/../includes/head.php'; ?>
</head>
<body class="bg-gray-50/50">
    <div class="flex h-screen overflow-hidden">
        <?php require __DIR__ . '/../includes/sidebar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/50 p-6 md:p-12">
            <div class="max-w-6xl mx-auto">
                <header class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Laporan Penjualan</h1>
                </header>

                <div class="glass-panel p-6 rounded-3xl mb-8">
                    <form class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Dari Tanggal</label>
                            <input type="date" name="start" value="<?= $startDate ?>" class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-200 outline-none focus:ring-2 focus:ring-ls-200">
                        </div>
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Sampai Tanggal</label>
                            <input type="date" name="end" value="<?= $endDate ?>" class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-200 outline-none focus:ring-2 focus:ring-ls-200">
                        </div>
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status Pesanan</label>
                            <select name="status" class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-200 outline-none focus:ring-2 focus:ring-ls-200">
                                <option value="">Semua Status</option>
                                <option value="selesai" <?= $status == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                <option value="diproses" <?= $status == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                <option value="dikirim" <?= $status == 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                <option value="baru" <?= $status == 'baru' ? 'selected' : '' ?>>Baru</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full md:w-auto px-8 py-3 bg-ls-600 text-white font-bold rounded-xl hover:bg-ls-700 transition shadow-lg shadow-ls-200">
                            Tampilkan
                        </button>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="glass-card p-6 rounded-3xl bg-gradient-to-br from-green-50 to-emerald-50 border border-green-100">
                        <p class="text-sm font-bold text-green-600 uppercase tracking-wide">Total Omzet</p>
                        <h3 class="text-3xl font-extrabold text-green-700 mt-2">Rp <?= number_format($totalOmzet, 0, ',', '.') ?></h3>
                        <p class="text-xs text-green-500 mt-2">Periode <?= date('d M', strtotime($startDate)) ?> - <?= date('d M Y', strtotime($endDate)) ?></p>
                    </div>
                    <div class="glass-card p-6 rounded-3xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100">
                        <p class="text-sm font-bold text-blue-600 uppercase tracking-wide">Produk Terjual</p>
                        <h3 class="text-3xl font-extrabold text-blue-700 mt-2"><?= $totalQty ?> <span class="text-lg font-medium">Items</span></h3>
                        <p class="text-xs text-blue-500 mt-2">Total akumulasi qty pesanan</p>
                    </div>
                </div>

                <div class="glass-panel rounded-3xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50/50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 font-bold text-gray-400">Tanggal</th>
                                    <th class="px-6 py-4 font-bold text-gray-400">Kode</th>
                                    <th class="px-6 py-4 font-bold text-gray-400">Produk</th>
                                    <th class="px-6 py-4 font-bold text-gray-400">Qty</th>
                                    <th class="px-6 py-4 font-bold text-gray-400">Total</th>
                                    <th class="px-6 py-4 font-bold text-gray-400">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($reports as $r): ?>
                                <tr class="hover:bg-gray-50/50">
                                    <td class="px-6 py-4 text-gray-500"><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
                                    <td class="px-6 py-4 font-mono text-gray-600"><?= htmlspecialchars($r['order_code']) ?></td>
                                    <td class="px-6 py-4 font-medium text-gray-800"><?= htmlspecialchars($r['product_name']) ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?= $r['qty'] ?></td>
                                    <td class="px-6 py-4 font-bold text-gray-800">Rp <?= number_format($r['total_price'], 0, ',', '.') ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold capitalize 
                                        <?= match($r['status']) {
                                            'selesai' => 'bg-green-100 text-green-700',
                                            'dibatalkan' => 'bg-red-100 text-red-700',
                                            'diproses' => 'bg-orange-100 text-orange-700',
                                            default => 'bg-gray-100 text-gray-600'
                                        } ?>">
                                            <?= $r['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($reports)): ?>
                                    <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Tidak ada data untuk periode ini.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>
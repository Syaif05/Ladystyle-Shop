<?php // profile.php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

if (!is_logged_in()) {
    redirect('/ladystyle-shop/login_customer.php');
}

$id = $_SESSION['user_id'];
$message = '';
$msgType = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    // Logic Upload Avatar
    $avatarPath = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['avatar']['tmp_name'];
        $fileName = $_FILES['avatar']['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($fileExt, $allowed)) {
            // Format Nama: NamaAkun-NomerRandom.ext
            // Hapus spasi dari nama agar rapi
            $cleanName = preg_replace('/[^a-zA-Z0-9]/', '', $name);
            $newFileName = $cleanName . '-' . rand(1000, 9999) . '.' . $fileExt;
            
            $uploadDir = 'assets/images/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $destPath = $uploadDir . $newFileName;
            
            if (move_uploaded_file($fileTmp, $destPath)) {
                $avatarPath = $destPath;
            }
        }
    }

    // Prepare SQL Update
    $sqlPass = "";
    $sqlAvatar = "";
    $params = [$name, $phone, $address];

    if (!empty($_POST['password'])) {
        $sqlPass = ", password = ?";
        $params[] = $_POST['password'];
    }

    if ($avatarPath) {
        $sqlAvatar = ", avatar = ?";
        $params[] = $avatarPath;
    }

    $params[] = $id; 

    $sql = "UPDATE users SET name = ?, phone = ?, address = ? $sqlPass $sqlAvatar WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute($params)) {
        $_SESSION['user_name'] = $name;
        $message = "Profil berhasil diperbarui!";
        $msgType = "success";
    } else {
        $message = "Gagal memperbarui profil.";
        $msgType = "error";
    }
}

// Ambil Data User Terbaru
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

// Avatar Default jika kosong
$userAvatar = $user['avatar'] ?? 'https://ui-avatars.com/api/?name='.urlencode($user['name']).'&background=ec4899&color=fff&size=200';

// Ambil Riwayat Pesanan
$orders = [];
if (!empty($user['phone'])) {
    $stmtO = $pdo->prepare("
        SELECT o.*, p.name as product_name, p.image 
        FROM orders o 
        JOIN products p ON o.product_id = p.id 
        WHERE o.customer_phone = ? 
        ORDER BY o.created_at DESC
    ");
    $stmtO->execute([$user['phone']]);
    $orders = $stmtO->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Akun Saya - LadyStyle</title>
    <?php require __DIR__ . '/includes/head.php'; ?>
    <style>
        .input-group { position: relative; }
        .input-group input, .input-group textarea { padding-left: 2.8rem; transition: all 0.3s; }
        .input-group input:focus, .input-group textarea:focus { border-color: #ec4899; box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.1); background: white; }
        .input-icon { position: absolute; left: 1rem; top: 1rem; color: #9ca3af; transition: color 0.3s; }
        .input-group input:focus ~ .input-icon, .input-group textarea:focus ~ .input-icon { color: #ec4899; }
        .avatar-upload:hover .avatar-overlay { opacity: 1; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <?php require __DIR__ . '/includes/navbar.php'; ?>

    <main class="max-w-6xl mx-auto px-4 py-8 pb-20">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Akun Saya</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola informasi profil dan riwayat belanja Anda.</p>
            </div>
            <div class="flex bg-white p-1 rounded-xl border border-gray-200 shadow-sm">
                <button onclick="switchTab('profile')" id="btn-profile" class="px-6 py-2 rounded-lg text-sm font-bold bg-ls-600 text-white shadow-md transition-all">
                    Edit Profil
                </button>
                <button onclick="switchTab('orders')" id="btn-orders" class="px-6 py-2 rounded-lg text-sm font-bold text-gray-500 hover:bg-gray-50 transition-all">
                    Riwayat Pesanan
                </button>
            </div>
        </div>

        <div class="grid lg:grid-cols-[1fr_2.5fr] gap-8">
            
            <div class="h-fit space-y-6">
                <div class="glass-card p-8 rounded-3xl text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-br from-ls-400 to-ls-600"></div>
                    
                    <div class="relative w-32 h-32 mx-auto -mt-4 mb-4 rounded-full border-4 border-white shadow-lg overflow-hidden bg-white avatar-upload cursor-pointer group">
                        <img id="avatarPreview" src="<?= htmlspecialchars($userAvatar) ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        
                        <div onclick="document.getElementById('avatarInput').click()" 
                             class="avatar-overlay absolute inset-0 bg-black/40 flex flex-col items-center justify-center opacity-0 transition duration-300">
                            <svg class="w-8 h-8 text-white mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-[10px] font-bold text-white uppercase tracking-wider">Ganti Foto</span>
                        </div>
                    </div>

                    <h2 class="font-bold text-xl text-gray-900"><?= htmlspecialchars($user['name']) ?></h2>
                    <p class="text-sm text-gray-500 mb-6"><?= htmlspecialchars($user['email']) ?></p>
                    
                    <div class="grid grid-cols-2 gap-2 border-t border-gray-100 pt-4">
                        <div class="text-center">
                            <span class="block text-lg font-bold text-ls-600"><?= count($orders) ?></span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Pesanan</span>
                        </div>
                        <div class="text-center border-l border-gray-100">
                            <span class="block text-lg font-bold text-gray-700">Member</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Status</span>
                        </div>
                    </div>
                </div>

                <a href="/ladystyle-shop/logout.php" class="flex items-center justify-center gap-2 w-full py-4 rounded-2xl bg-white border border-red-100 text-red-500 font-bold text-sm hover:bg-red-50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar Akun
                </a>
            </div>

            <div class="space-y-6">
                
                <?php if ($message): ?>
                    <div class="flex items-center gap-3 p-4 rounded-2xl text-sm font-bold border animate-fade <?= $msgType === 'success' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' ?>">
                        <?php if ($msgType === 'success'): ?>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <?php else: ?>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <?php endif; ?>
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <div id="tab-profile" class="glass-panel p-8 rounded-3xl animate-fade">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-ls-100 text-ls-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </span>
                        Ubah Informasi
                    </h3>

                    <form method="post" enctype="multipart/form-data" class="space-y-6">
                        <input type="file" name="avatar" id="avatarInput" class="hidden" accept="image/*" onchange="previewImage(event)">

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="input-group">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Nama Lengkap</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required class="w-full px-4 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 text-sm font-medium">
                                <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div class="input-group">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">WhatsApp</label>
                                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="08..." class="w-full px-4 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 text-sm font-medium">
                                <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Alamat Pengiriman</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 text-sm font-medium leading-relaxed"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                            <svg class="input-icon w-5 h-5 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>

                        <div class="pt-6 border-t border-gray-100">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Password Baru (Opsional)</label>
                            <div class="input-group">
                                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengganti" class="w-full px-4 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 text-sm font-medium">
                                <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-8 py-3.5 rounded-xl bg-gray-900 text-white font-bold shadow-lg shadow-gray-200 hover:bg-ls-600 hover:shadow-ls-200 transition-all duration-300 transform hover:-translate-y-1">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <div id="tab-orders" class="glass-panel p-6 rounded-3xl animate-fade hidden">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </span>
                        Pesanan Saya
                    </h3>
                    
                    <?php if (empty($orders)): ?>
                        <div class="text-center py-16 bg-white/50 rounded-3xl border border-dashed border-gray-200">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">🛍️</div>
                            <p class="text-gray-900 font-bold">Belum ada pesanan</p>
                            <p class="text-sm text-gray-400 mb-4">Yuk mulai tambah koleksi fashion kamu!</p>
                            <a href="/ladystyle-shop/products.php" class="text-ls-600 font-bold hover:underline">Mulai Belanja &rarr;</a>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($orders as $o): ?>
                            <div class="group bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col md:flex-row gap-5">
                                <div class="w-full md:w-24 h-24 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden relative">
                                    <img src="<?= htmlspecialchars($o['image']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                </div>
                                <div class="flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start mb-1">
                                            <span class="font-mono text-[10px] text-gray-400 bg-gray-50 px-2 py-1 rounded-md uppercase tracking-wider"><?= $o['order_code'] ?></span>
                                            <span class="text-[10px] font-bold text-gray-400"><?= date('d M Y', strtotime($o['created_at'])) ?></span>
                                        </div>
                                        <h4 class="font-bold text-gray-900 text-lg"><?= htmlspecialchars($o['product_name']) ?></h4>
                                        <p class="text-xs text-gray-500 mt-1 font-medium">Qty: <?= $o['qty'] ?> • Size: <?= htmlspecialchars($o['size'] ?: 'All Size') ?></p>
                                    </div>
                                    <div class="mt-3 md:mt-0 pt-3 md:pt-0 border-t md:border-t-0 border-gray-50 flex justify-between items-end">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            <?= match($o['status']) {
                                                'selesai' => 'bg-green-100 text-green-700',
                                                'dikirim' => 'bg-purple-100 text-purple-700',
                                                'diproses' => 'bg-orange-100 text-orange-700',
                                                'dibatalkan' => 'bg-red-100 text-red-700',
                                                default => 'bg-blue-50 text-blue-700'
                                            } ?>">
                                            <?= $o['status'] ?>
                                        </span>
                                        <p class="text-ls-600 font-extrabold text-lg">Rp <?= number_format($o['total_price']) ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </main>

    <script>
        // Tab Switcher Logic
        function switchTab(tabName) {
            document.getElementById('tab-profile').classList.add('hidden');
            document.getElementById('tab-orders').classList.add('hidden');
            
            // Style Reset
            const inactiveClass = "text-gray-500 hover:bg-gray-50 bg-transparent";
            const activeClass = "bg-ls-600 text-white shadow-md";
            
            document.getElementById('btn-profile').className = `px-6 py-2 rounded-lg text-sm font-bold transition-all ${inactiveClass}`;
            document.getElementById('btn-orders').className = `px-6 py-2 rounded-lg text-sm font-bold transition-all ${inactiveClass}`;
            
            // Activate Selected
            document.getElementById('tab-' + tabName).classList.remove('hidden');
            document.getElementById('btn-' + tabName).className = `px-6 py-2 rounded-lg text-sm font-bold transition-all ${activeClass}`;
        }

        // Image Preview Logic
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('avatarPreview');
                output.src = reader.result;
            }
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</body>
</html>
<?php // register.php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

if (is_logged_in()) {
    redirect('/ladystyle-shop/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Semua kolom wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = 'Email tersebut sudah terdaftar. Silakan gunakan email lain atau login.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'customer')");
            $saved = $stmt->execute([$name, $email, $password]);

            if ($saved) {
                header('Location: /ladystyle-shop/login_customer.php?registered=true');
                exit;
            } else {
                $error = 'Terjadi kesalahan sistem. Silakan coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Akun - LadyStyle</title>
    <?php require __DIR__ . '/includes/head.php'; ?>
</head>
<body class="flex items-center justify-center min-h-screen bg-ls-50 py-10 px-4">
    <div class="w-full max-w-md glass-card rounded-3xl p-8 md:p-10 relative overflow-hidden shadow-xl animate-fade">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-ls-400 to-ls-600"></div>
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Buat Akun Baru</h1>
            <p class="text-gray-500 mt-2 text-sm">Bergabunglah untuk pengalaman belanja terbaik</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Nama Lengkap</label>
                <input type="text" name="name" required value="<?= htmlspecialchars($name ?? '') ?>"
                       class="w-full px-5 py-3 rounded-xl bg-white/50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-ls-300 focus:border-ls-500 transition-all outline-none text-gray-800 placeholder-gray-400"
                       placeholder="Contoh: Sarah Putri">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Alamat Email</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($email ?? '') ?>"
                       class="w-full px-5 py-3 rounded-xl bg-white/50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-ls-300 focus:border-ls-500 transition-all outline-none text-gray-800 placeholder-gray-400"
                       placeholder="nama@email.com">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Password</label>
                    <input type="password" name="password" required
                           class="w-full px-5 py-3 rounded-xl bg-white/50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-ls-300 focus:border-ls-500 transition-all outline-none text-gray-800 placeholder-gray-400"
                           placeholder="••••••">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Ulangi Password</label>
                    <input type="password" name="confirm_password" required
                           class="w-full px-5 py-3 rounded-xl bg-white/50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-ls-300 focus:border-ls-500 transition-all outline-none text-gray-800 placeholder-gray-400"
                           placeholder="••••••">
                </div>
            </div>

            <button type="submit" 
                    class="w-full py-3.5 mt-2 rounded-xl bg-gradient-to-r from-ls-500 to-ls-600 text-white font-bold shadow-lg shadow-pink-500/30 hover:shadow-pink-500/50 hover:-translate-y-0.5 transition-all duration-300">
                Daftar Sekarang
            </button>
        </form>

        <div class="mt-8 text-center pt-6 border-t border-gray-100">
            <p class="text-sm text-gray-500">
                Sudah punya akun? 
                <a href="/ladystyle-shop/login_customer.php" class="font-bold text-ls-600 hover:text-ls-700 hover:underline transition">Masuk di sini</a>
            </p>
            <div class="mt-4">
                <a href="/ladystyle-shop/index.php" class="text-xs text-gray-400 hover:text-gray-600 transition flex items-center justify-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>
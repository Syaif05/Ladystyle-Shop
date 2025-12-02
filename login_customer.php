<?php // login_customer.php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

if (is_logged_in()) {
    redirect('/ladystyle-shop/index.php');
}

$error = '';
$registeredMsg = '';

if (isset($_GET['registered']) && $_GET['registered'] === 'true') {
    $registeredMsg = 'Pendaftaran berhasil! Silakan login dengan akun baru Anda.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'customer'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && $user['password'] === $password) {
            login_user($user);
            redirect('/ladystyle-shop/index.php');
        } else {
            $error = 'Email atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Masuk - LadyStyle</title>
    <?php require __DIR__ . '/includes/head.php'; ?>
</head>
<body class="flex items-center justify-center min-h-screen bg-ls-50 py-10 px-4">
    <div class="w-full max-w-md glass-card rounded-3xl p-8 md:p-10 relative overflow-hidden shadow-xl animate-fade">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-tr from-ls-500 to-ls-600 rounded-2xl mx-auto flex items-center justify-center text-white font-bold text-2xl shadow-lg mb-4">
                LS
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Selamat Datang</h1>
            <p class="text-gray-500 mt-2 text-sm">Masuk untuk melanjutkan belanja</p>
        </div>

        <?php if ($registeredMsg): ?>
            <div class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span><?= htmlspecialchars($registeredMsg) ?></span>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Email</label>
                <input type="email" name="email" required 
                       class="w-full px-5 py-3 rounded-xl bg-white/50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-ls-300 focus:border-ls-500 transition-all outline-none text-gray-800 placeholder-gray-400"
                       placeholder="nama@email.com">
            </div>

            <div>
                <div class="flex justify-between items-center mb-2 ml-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Password</label>
                    <a href="/ladystyle-shop/forgot_password.php" class="text-xs font-bold text-ls-500 hover:text-ls-700 hover:underline">Lupa password?</a>
                </div>
                <input type="password" name="password" required 
                       class="w-full px-5 py-3 rounded-xl bg-white/50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-ls-300 focus:border-ls-500 transition-all outline-none text-gray-800 placeholder-gray-400"
                       placeholder="••••••">
            </div>

            <button type="submit" 
                    class="w-full py-3.5 mt-2 rounded-xl bg-gray-900 text-white font-bold shadow-lg hover:bg-gray-800 hover:-translate-y-0.5 transition-all duration-300">
                Masuk
            </button>
        </form>

        <div class="mt-8 text-center pt-6 border-t border-gray-100">
            <p class="text-sm text-gray-500">
                Belum punya akun? 
                <a href="/ladystyle-shop/register.php" class="font-bold text-ls-600 hover:text-ls-700 hover:underline transition">Daftar sekarang</a>
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
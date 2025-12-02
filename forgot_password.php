<?php // forgot_password.php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/functions.php';

$step = 1;
$error = '';
$success = '';
$email = '';

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Stage 1: Cek Email
    if (isset($_POST['check_email'])) {
        $email = trim($_POST['email'] ?? '');
        if (empty($email)) {
            $error = 'Email wajib diisi.';
        } else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $step = 2; // Lanjut ke step ganti password (Simulasi diterima)
            } else {
                $error = "Email tidak ditemukan dalam sistem kami.";
            }
        }
    }
    // Stage 2: Reset Password (Simulasi)
    elseif (isset($_POST['reset_password'])) {
        $email = $_POST['email'] ?? '';
        $pass1 = $_POST['password'] ?? '';
        $pass2 = $_POST['confirm_password'] ?? '';

        if (empty($pass1) || empty($pass2)) {
            $error = 'Password tidak boleh kosong.';
            $step = 2;
        } elseif ($pass1 !== $pass2) {
            $error = 'Konfirmasi password tidak cocok.';
            $step = 2;
        } else {
            // Update Password di Database
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            if ($stmt->execute([$pass1, $email])) {
                $step = 3; // Selesai
            } else {
                $error = 'Terjadi kesalahan sistem.';
                $step = 2;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Reset Password - LadyStyle</title>
    <?php require __DIR__ . '/includes/head.php'; ?>
    <style>
        /* Tooltip Custom Style */
        .tooltip { position: relative; display: inline-block; cursor: help; }
        .tooltip .tooltip-text {
            visibility: hidden; width: 240px; background-color: #1f2937; color: #fff; text-align: center;
            border-radius: 8px; padding: 10px; position: absolute; z-index: 10; bottom: 130%; 
            left: 50%; margin-left: -120px; opacity: 0; transition: opacity 0.3s; font-size: 11px; font-weight: normal; line-height: 1.4;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .tooltip .tooltip-text::after {
            content: ""; position: absolute; top: 100%; left: 50%; margin-left: -5px;
            border-width: 5px; border-style: solid; border-color: #1f2937 transparent transparent transparent;
        }
        .tooltip:hover .tooltip-text { visibility: visible; opacity: 1; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-ls-50 py-10 px-4">
    <div class="w-full max-w-md glass-card rounded-3xl p-8 relative overflow-hidden shadow-xl animate-fade">
        
        <div class="text-center mb-6 relative">
            <h1 class="text-2xl font-bold text-gray-800">Reset Password</h1>
            
            <div class="tooltip absolute top-0 right-0 text-gray-400 hover:text-ls-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="tooltip-text">
                    <strong>INFO SIMULASI:</strong><br>
                    Pada aplikasi nyata, link dikirim ke email. Di sini, sistem langsung mengarahkan Anda ke form ganti password untuk kemudahan pengujian.
                </span>
            </div>

            <?php if ($step === 1): ?>
                <p class="text-gray-500 mt-2 text-sm">Masukkan email yang terdaftar untuk akun Anda</p>
            <?php elseif ($step === 2): ?>
                <p class="text-gray-500 mt-2 text-sm">Verifikasi berhasil! Silakan buat password baru</p>
            <?php endif; ?>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2 animate-fade">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <?php if ($step === 1): ?>
        <form method="post" class="space-y-5 animate-fade">
            <input type="hidden" name="check_email" value="1">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Email</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($email) ?>"
                       class="w-full px-5 py-3 rounded-xl bg-white/50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-ls-300 focus:border-ls-500 transition-all outline-none text-gray-800 placeholder-gray-400"
                       placeholder="nama@email.com">
            </div>
            <button type="submit" class="w-full py-3.5 mt-2 rounded-xl bg-ls-600 text-white font-bold shadow-lg hover:bg-ls-700 transition-all hover:-translate-y-0.5">
                Verifikasi Email
            </button>
        </form>
        <?php endif; ?>

        <?php if ($step === 2): ?>
        <form method="post" class="space-y-5 animate-fade">
            <input type="hidden" name="reset_password" value="1">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            
            <div class="bg-green-50 text-green-700 px-4 py-3 rounded-xl text-xs font-bold border border-green-100 mb-4">
                Email ditemukan: <?= htmlspecialchars($email) ?>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Password Baru</label>
                <input type="password" name="password" required 
                       class="w-full px-5 py-3 rounded-xl bg-white/50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-ls-300 focus:border-ls-500 transition-all outline-none text-gray-800"
                       placeholder="••••••">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Konfirmasi Password</label>
                <input type="password" name="confirm_password" required 
                       class="w-full px-5 py-3 rounded-xl bg-white/50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-ls-300 focus:border-ls-500 transition-all outline-none text-gray-800"
                       placeholder="••••••">
            </div>
            <button type="submit" class="w-full py-3.5 mt-2 rounded-xl bg-gray-900 text-white font-bold shadow-lg hover:bg-ls-600 transition-all hover:-translate-y-0.5">
                Simpan Password Baru
            </button>
        </form>
        <?php endif; ?>

        <?php if ($step === 3): ?>
        <div class="text-center py-6 animate-fade">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 text-green-600 shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Password Diperbarui!</h2>
            <p class="text-gray-500 text-sm mb-6">Akun Anda aman. Silakan login dengan password baru.</p>
            <a href="/ladystyle-shop/login_customer.php" class="inline-block w-full py-3.5 rounded-xl bg-ls-600 text-white font-bold shadow-lg hover:bg-ls-700 transition-all hover:-translate-y-0.5">
                Login Sekarang
            </a>
        </div>
        <?php endif; ?>

        <div class="mt-8 text-center pt-6 border-t border-gray-100">
            <?php if ($step !== 3): ?>
            <a href="/ladystyle-shop/login_customer.php" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition flex items-center justify-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Login
            </a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php // login.php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

if (is_logged_in()) {
    redirect('dashboard/index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && $user['password'] === $password) {
        login_user($user);
        redirect('dashboard/index.php');
    } else {
        $error = 'Email atau password tidak valid.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login Admin - LadyStyle</title>
    <?php require __DIR__ . '/includes/head.php'; ?>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md glass-card rounded-3xl p-8 sm:p-10 relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-ls-300 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-pulse"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-purple-300 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-pulse"></div>

        <div class="text-center mb-10 relative z-10">
            <div class="mx-auto w-16 h-16 bg-gradient-to-tr from-ls-400 to-ls-600 rounded-2xl flex items-center justify-center shadow-lg mb-4 text-white font-bold text-2xl">
                LS
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Welcome Back!</h2>
            <p class="text-sm text-gray-500 mt-2">Masuk untuk mengelola toko Anda</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-100 text-red-600 text-sm rounded-xl p-4 mb-6 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-5 relative z-10">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Email Address</label>
                <input type="email" name="email" required 
                       class="w-full px-5 py-3 rounded-xl bg-white/50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-ls-200 focus:border-ls-400 transition-all outline-none text-gray-700"
                       placeholder="admin@ladystyle.local">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Password</label>
                <input type="password" name="password" required 
                       class="w-full px-5 py-3 rounded-xl bg-white/50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-ls-200 focus:border-ls-400 transition-all outline-none text-gray-700"
                       placeholder="••••••••">
            </div>

            <button type="submit" 
                    class="w-full py-3.5 rounded-xl bg-gray-900 text-white font-bold shadow-lg hover:bg-ls-600 hover:shadow-ls-500/30 transition-all duration-300 transform hover:-translate-y-1">
                Masuk Dashboard
            </button>
        </form>

        <div class="mt-8 text-center text-xs text-gray-400">
            &copy; 2025 LadyStyle Shop System
        </div>
    </div>

</body>
</html>
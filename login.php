
<?php // login.php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

if (is_logged_in()) {
    redirect('/ladystyle-shop/dashboard/index.php');
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Email dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && $user['password'] === $password) {
            login_user($user);
            redirect('/ladystyle-shop/dashboard/index.php');
        } else {
            $error = 'Email atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - LadyStyle Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/ladystyle-shop/assets/css/style.css" rel="stylesheet">
</head>
<body class="app-body">
    <div class="center-wrapper">
        <div class="glass-card auth-card">
            <h1 class="app-title">LadyStyle Shop</h1>
            <p class="app-subtitle">Panel Admin</p>

            <?php if ($error !== ''): ?>
                <div class="alert alert-error">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" class="form-vertical">
                <label class="form-label" for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="input-control"
                    value="<?= e($email) ?>"
                    required
                >

                <label class="form-label" for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="input-control"
                    required
                >

                <button type="submit" class="btn-primary w-100 mt-3">
                    Masuk
                </button>
            </form>

            <p class="helper-text mt-2">
                Gunakan akun admin awal:
                <br>Email: <strong>admin@ladystyle.local</strong><br>
                Password: <strong>admin123</strong>
            </p>
        </div>
    </div>
    <script src="/ladystyle-shop/assets/js/app.js"></script>
</body>
</html>

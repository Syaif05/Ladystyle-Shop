
<?php // logout.php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

if (is_logged_in()) {
    logout_user();
}

redirect('/ladystyle-shop/login.php');

<?php // logout.php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_unset();
session_destroy();

redirect('/ladystyle-shop/login_customer.php');
?>
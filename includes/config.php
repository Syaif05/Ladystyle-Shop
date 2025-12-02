<?php // includes/config.php
$host = 'localhost';
$port = '3306';
$dbName = 'ladystyle_shop_db';
$username = 'root';
$password = '';
$dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Database connection failed');
}

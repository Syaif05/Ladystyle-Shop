<?php // includes/functions.php
function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function format_rupiah(int $amount): string
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

<?php
require_once __DIR__ . '/includes/functions.php';

$slug = trim($_GET['slug'] ?? '');

if ($slug) {
    $stmt = db()->prepare('SELECT affiliate_url FROM products WHERE slug = ? AND active = 1');
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
}

if (!empty($row['affiliate_url'])) {
    header('Location: ' . $row['affiliate_url']);
} else {
    header('Location: /shop.php');
}
exit;

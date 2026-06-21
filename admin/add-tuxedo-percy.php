<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo = db();

$catStmt = $pdo->prepare('SELECT id FROM categories WHERE slug = ?');
$catStmt->execute(['robot-cats']);
$catId = $catStmt->fetchColumn();

if (!$catId) {
    die('<p style="font-family:sans-serif;color:red">robot-cats category not found.</p>');
}

$pdo->prepare("INSERT INTO products
    (name, slug, description, price, compare_at_price, image, category_id, active, featured, affiliate_url, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, 1, 0, NULL, NOW())")
->execute([
    'Percy Tuxedo Cat 1.1 — Voice, Purring & Heartbeat',
    'percy-tuxedo-cat-1-1',
    'The original Percy — a classic black and white tuxedo robotic cat with a simulated heartbeat, gentle purring, and voice interaction. Touch sensors trigger realistic responses, and Percy can learn and respond to a personalized name. Weighted for tactile comfort and designed to ease anxiety, loneliness, and restlessness. A trusted companion for seniors, individuals with dementia, and anyone who loves cats but can\'t keep a live pet. Easy USB-C charging, 1-year warranty.',
    '99.00',
    '139.00',
    'https://chongker.com/cdn/shop/files/percy_d7ab54e1-de70-4144-9d15-f02e39595d52.jpg',
    $catId,
]);

echo '<p style="font-family:sans-serif;color:green">✓ Tuxedo Percy 1.1 added. <a href="/admin/products.php">Go to Products</a> — you can now delete this file.</p>';

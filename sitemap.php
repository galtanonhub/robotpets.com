<?php
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: application/xml; charset=utf-8');

$static = [
    ['/',                 '1.0', 'daily'],
    ['/shop.php',         '0.9', 'daily'],
    ['/about.php',        '0.7', 'monthly'],
    ['/faq.php',          '0.7', 'monthly'],
    ['/contact.php',      '0.5', 'monthly'],
    ['/blog.php',         '0.8', 'daily'],
    ['/learn.php',        '0.8', 'weekly'],
    ['/find-my-pet.php',  '0.7', 'monthly'],
    ['/for-seniors.php',  '0.8', 'monthly'],
    ['/for-kids.php',     '0.8', 'monthly'],
    ['/for-allergies.php','0.8', 'monthly'],
    ['/for-gifts.php',    '0.8', 'monthly'],
];

$products = db()->query(
    "SELECT slug FROM products WHERE active = 1 ORDER BY created_at DESC"
)->fetchAll(PDO::FETCH_COLUMN);

$posts = db()->query(
    "SELECT slug, type FROM posts WHERE active = 1 ORDER BY created_at DESC"
)->fetchAll();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($static as [$path, $priority, $freq]): ?>
  <url>
    <loc><?= SITE_URL . $path ?></loc>
    <changefreq><?= $freq ?></changefreq>
    <priority><?= $priority ?></priority>
  </url>
<?php endforeach; ?>
<?php foreach ($products as $slug): ?>
  <url>
    <loc><?= SITE_URL ?>/product.php?slug=<?= urlencode($slug) ?></loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
<?php endforeach; ?>
<?php foreach ($posts as $post): ?>
  <url>
    <loc><?= SITE_URL ?>/<?= $post['type'] === 'guide' ? 'guide' : 'blog-post' ?>.php?slug=<?= urlencode($post['slug']) ?></loc>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
<?php endforeach; ?>
</urlset>

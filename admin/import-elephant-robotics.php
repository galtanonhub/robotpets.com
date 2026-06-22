<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo = db();

// Look up existing category IDs by slug — no new categories created
function catId(PDO $pdo, string $slug): ?int {
    $stmt = $pdo->prepare('SELECT id FROM categories WHERE slug = ?');
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    return $row ? (int)$row['id'] : null;
}

$products = [
    [
        'name'             => 'Elephant Robotics metaCat – Ragdoll',
        'description'      => 'An interactive robotic cat companion featuring voice recognition technology. Realistic head and tail movements powered by AI deep learning, up to 10 hours of battery life on a single Type-C charge, and responds to voice commands for interactive play. Made with soft, safe materials suitable for children and elderly users seeking companionship. Ragdoll colorway.',
        'price'            => 159.00,
        'compare_at_price' => 189.00,
        'image_url'        => 'https://shop.elephantrobotics.com/cdn/shop/files/Cat-Ragdoll-1.jpg?v=1736244025',
        'supplier_url'     => 'https://shop.elephantrobotics.com/products/metacat',
        'category_slug'    => 'cats',
    ],
    [
        'name'             => 'Elephant Robotics metaCat – Bicolor B&W',
        'description'      => 'An interactive robotic cat companion featuring voice recognition technology. Realistic head and tail movements powered by AI deep learning, up to 10 hours of battery life on a single Type-C charge, and responds to voice commands for interactive play. Made with soft, safe materials suitable for children and elderly users seeking companionship. Bicolor black & white colorway.',
        'price'            => 159.00,
        'compare_at_price' => 189.00,
        'image_url'        => 'https://shop.elephantrobotics.com/cdn/shop/files/Cat-Bicolor.webp?v=1726310051',
        'supplier_url'     => 'https://shop.elephantrobotics.com/products/metacat-purring-robotic-companion-pet-bicolor',
        'category_slug'    => 'cats',
    ],
    [
        'name'             => 'Elephant Robotics metaCat – Persian',
        'description'      => 'An interactive robotic cat companion featuring voice recognition technology. Realistic head and tail movements powered by AI deep learning, up to 10 hours of battery life on a single Type-C charge, and responds to voice commands for interactive play. Made with soft, safe materials suitable for children and elderly users seeking companionship. Persian colorway.',
        'price'            => 159.00,
        'compare_at_price' => 189.00,
        'image_url'        => 'https://shop.elephantrobotics.com/cdn/shop/files/Cat-Persian.webp?v=1726310051',
        'supplier_url'     => 'https://shop.elephantrobotics.com/products/metacat-yellow',
        'category_slug'    => 'cats',
    ],
    [
        'name'             => 'Elephant Robotics metaDog – Husky',
        'description'      => 'An interactive robotic pet dog featuring voice recognition, customizable wake-up words, and a mobile gaming app. Up to 10 hours of continuous operation on a single Type-C charge (45-minute charge time). Responds to voice commands like "Shake head," "Look up," and "Shake Tail." Designed as a companion for all ages — especially seniors and children with allergies or conditions preventing real pet ownership. Husky colorway.',
        'price'            => 189.00,
        'compare_at_price' => 258.00,
        'image_url'        => 'https://shop.elephantrobotics.com/cdn/shop/files/metadog2.png?v=1760955798',
        'supplier_url'     => 'https://shop.elephantrobotics.com/products/metadog-gift-the-purr-fect-companion-robotic-pet-dog',
        'category_slug'    => 'dogs',
    ],
    [
        'name'             => 'Elephant Robotics metaDog – Shiba Inu',
        'description'      => 'An interactive robotic pet dog featuring voice recognition, customizable wake-up words, and a mobile gaming app. Up to 10 hours of continuous operation on a single Type-C charge (45-minute charge time). Responds to voice commands like "Shake head," "Look up," and "Shake Tail." Designed as a companion for all ages — especially seniors and children with allergies or conditions preventing real pet ownership. Shiba Inu colorway.',
        'price'            => 189.00,
        'compare_at_price' => 258.00,
        'image_url'        => 'https://shop.elephantrobotics.com/cdn/shop/files/metadog1.png?v=1760955824',
        'supplier_url'     => 'https://shop.elephantrobotics.com/products/metadog-gift-the-purr-fect-companion-robotic-pet-dog-shiba-inu',
        'category_slug'    => 'dogs',
    ],
    [
        'name'             => 'Elephant Robotics metaPanda',
        'description'      => 'An interactive robotic panda companion featuring touch-responsive sensors, customizable wake-up words, voice command recognition (30+ commands), expressive eye animations, and a simulated heartbeat. Responds to affection with hugs and arm movements, includes a feeding interaction mode with included bamboo toy, and offers 12 hours of battery life on a single Type-C charge.',
        'price'            => 349.00,
        'compare_at_price' => null,
        'image_url'        => 'https://shop.elephantrobotics.com/cdn/shop/files/metaPanda-main.webp?v=1724827109',
        'supplier_url'     => 'https://shop.elephantrobotics.com/products/metapanda',
        'category_slug'    => 'robot-birds-exotics',
    ],
];

$inserted = 0;
$skipped  = 0;
$errors   = [];

foreach ($products as $p) {
    // Skip if already exists by name
    $check = $pdo->prepare('SELECT id FROM products WHERE name = ?');
    $check->execute([$p['name']]);
    if ($check->fetch()) {
        $skipped++;
        continue;
    }

    $slug = slugify($p['name']);
    $dupeCheck = $pdo->prepare('SELECT COUNT(*) FROM products WHERE slug LIKE ?');
    $dupeCheck->execute(["$slug%"]);
    if ((int)$dupeCheck->fetchColumn() > 0) {
        $slug .= '-' . ((int)$dupeCheck->fetchColumn() + 1);
    }

    $image      = mirror_image_url($p['image_url']);
    $categoryId = $p['category_slug'] ? catId($pdo, $p['category_slug']) : null;

    try {
        $pdo->prepare('
            INSERT INTO products (name, slug, description, price, compare_at_price, affiliate_url, supplier_url, image, category_id, featured, is_hero, active)
            VALUES (?, ?, ?, ?, ?, NULL, ?, ?, ?, 0, 0, 1)
        ')->execute([
            $p['name'],
            $slug,
            $p['description'],
            $p['price'],
            $p['compare_at_price'],
            $p['supplier_url'],
            $image,
            $categoryId,
        ]);
        $inserted++;
    } catch (Exception $e) {
        $errors[] = $p['name'] . ': ' . $e->getMessage();
    }
}

$title = 'Import: Elephant Robotics';
include __DIR__ . '/includes/admin-header.php';
?>

<div class="page-head">
  <h1>Import: Elephant Robotics</h1>
  <a href="/admin/products.php" class="btn-sm">← Back to products</a>
</div>

<div class="panel" style="padding:1.5rem;">
  <p>✅ Inserted: <strong><?= $inserted ?></strong></p>
  <p>⏭ Skipped (already exist): <strong><?= $skipped ?></strong></p>
  <?php if ($errors): ?>
    <p style="color:#dc2626;">Errors:</p>
    <ul><?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul>
  <?php endif; ?>
  <p style="margin-top:1rem;color:var(--text-dim);font-size:.875rem;">
    You can now delete <code>admin/import-elephant-robotics.php</code> — it's a one-time script.
  </p>
</div>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>

<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo = db();

function catId(PDO $pdo, string $slug): ?int {
    $stmt = $pdo->prepare('SELECT id FROM categories WHERE slug = ?');
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    return $row ? (int)$row['id'] : null;
}

$products = [
    [
        'name'             => 'Petoi Bittle X Robot Dog',
        'description'      => 'A programmable ESP32-based quadruped robot dog designed for coding education and hobbyist robotics. Assembles in about 60 minutes and supports block-based coding, Python, and C++. Features 35+ lifelike movements, voice command control with 35+ commands, and optional robotic arm gripper attachment for object-handling. Compatible with AI and IoT sensor expansion. Ideal for ages 10+, K–12 schools, colleges, and robotics camps — free coding curriculum included.',
        'price'            => 319.00,
        'compare_at_price' => null,
        'image_url'        => 'https://cdn.shopify.com/s/files/1/0550/2015/9160/files/BittleBlackYellowcleanbg.jpg?v=1764746859',
        'supplier_url'     => 'https://www.petoi.com/products/petoi-robot-dog-bittle-x-voice-controlled',
        'category_slug'    => 'dogs',
    ],
    [
        'name'             => 'Petoi Bittle Robot Dog',
        'description'      => 'A DIY Arduino-based quadruped robot dog that assembles in about 60 minutes. Supports block-based coding, Python, and C++ with 35+ lifelike movements controlled via remote or mobile app. Features AI and IoT integration options including optional camera and sensors. Built on the open-source OpenCat framework with Raspberry Pi support for advanced users. Great for ages 10+, K–12 classrooms, robotics camps, and team coding activities — free curriculum included.',
        'price'            => 349.00,
        'compare_at_price' => null,
        'image_url'        => 'https://www.petoi.com/cdn/shop/files/black_yellow_light_blue_bittle_stands_with_text_1946x.png',
        'supplier_url'     => 'https://www.petoi.com/products/petoi-bittle-robot-dog',
        'category_slug'    => 'dogs',
    ],
    [
        'name'             => 'Petoi Nybble Robot Cat',
        'description'      => 'A DIY Arduino-based robot cat you build from scratch in 3–4 hours. Features 11 degrees of freedom across 11 joints, programmable LED lights, ultrasonic sensors, and mobile app control. Supports block-based coding, C++, and Python via the open-source OpenCat framework. Can sit, stretch, sleep, balance, and perform tricks. A hands-on STEM project for ages 10+ with plenty of room to customize and expand.',
        'price'            => 299.00,
        'compare_at_price' => null,
        'image_url'        => 'https://www.petoi.com/cdn/shop/files/1-petoiopensourcearduinonybblerobotcat_465ed315-c595-4d3a-8860-08de17142cf6_1946x.jpg',
        'supplier_url'     => 'https://www.petoi.com/products/petoi-nybble-robot-cat',
        'category_slug'    => 'cats',
    ],
    [
        'name'             => 'Petoi Nybble Q Robot Cat',
        'description'      => 'An advanced ESP32/Arduino 3D-printed robot cat with 11 joints and 35+ lifelike movements. Features four touch sensors, an ultrasonic sensor, voice control with customizable commands, and approximately one hour of battery playtime. Supports block-based coding, Python, and C++ with AI and IoT expansion options. Available in cream or white colorways with lite or alloy servo options. Designed for ages 10+, educational settings, and hobbyists serious about robotics.',
        'price'            => 435.00,
        'compare_at_price' => null,
        'image_url'        => 'https://cdn.shopify.com/s/files/1/0550/2015/9160/files/nybble_q_robot_cat_knees_down_and_says_hi_-_4x3_d57bc584-7c1e-452b-b611-324692585bd3.jpg?v=1775846242',
        'supplier_url'     => 'https://www.petoi.com/products/petoi-nybble-q-robot-cat',
        'category_slug'    => 'cats',
    ],
];

$inserted = 0;
$skipped  = 0;
$errors   = [];

foreach ($products as $p) {
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

$title = 'Import: Petoi';
include __DIR__ . '/includes/admin-header.php';
?>

<div class="page-head">
  <h1>Import: Petoi</h1>
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
    You can now delete <code>admin/import-petoi.php</code> — it's a one-time script.
  </p>
</div>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>

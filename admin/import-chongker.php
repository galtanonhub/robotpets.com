<?php
/**
 * One-time Chongker product import
 * Run once, then delete this file.
 * Affiliate URLs must be added via the admin panel afterward.
 */
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo = db();

// Resolve category IDs
$cats = [];
foreach ($pdo->query('SELECT id, slug FROM categories') as $row) {
    $cats[$row['slug']] = (int)$row['id'];
}

$missing = array_diff(['robot-cats','robot-dogs','robot-birds-exotics','accessories-parts'], array_keys($cats));
if ($missing) {
    die('<pre>Missing categories: ' . implode(', ', $missing) . "\nPlease create them in the admin panel first.</pre>");
}

$products = [
    [
        'name'        => 'Matecat Pro — Hyper-Realistic Bionic Cat',
        'slug'        => 'matecat-pro-hyper-realistic-bionic-cat',
        'price'       => '199.00',
        'sale_price'  => '199.00',
        'category'    => 'robot-cats',
        'featured'    => 1,
        'image'       => 'https://chongker.com/cdn/shop/files/7_8ecc3d98-2c28-4c69-834a-e4b6e60f45e4.jpg',
        'description' => 'The Matecat Pro is Chongker\'s flagship robotic cat — hand-finished with highly detailed realistic fur and expressive features that are almost indistinguishable from the real thing. Dynamic head turning, swishing tail, and natural resting poses create a genuinely lifelike presence. Designed for pet lovers of all ages, it requires zero care while delivering all the comfort of a real companion. No vet bills, no allergies, no guilt when you travel.',
    ],
    [
        'name'        => 'Percy Robot Cat — Interactive Emotional Support Pet',
        'slug'        => 'percy-interactive-cat',
        'price'       => '139.00',
        'sale_price'  => '99.00',
        'category'    => 'robot-cats',
        'featured'    => 1,
        'image'       => 'https://chongker.com/cdn/shop/files/percy_d7ab54e1-de70-4144-9d15-f02e39595d52.jpg',
        'description' => 'Percy is an interactive robotic companion cat with a simulated heartbeat, gentle purring, and a soft weighted design built to calm and comfort. Ideal for seniors, individuals managing Alzheimer\'s, dementia, or anxiety, Percy offers a drug-free source of emotional support. Touch sensors trigger realistic responses; a customizable voice feature lets Percy learn a personal name and respond to it. Available in Tuxedo, Ragdoll, Ginger, and Golden Retriever styles. 1-year warranty included.',
    ],
    [
        'name'        => 'Percy Robot Ginger Cat 1.1 — Voice, Purring & Heartbeat',
        'slug'        => 'percy-robot-ginger-cat-1-1-voice-purring-heartbeat-for-comfort',
        'price'       => '139.00',
        'sale_price'  => '109.00',
        'category'    => 'robot-cats',
        'featured'    => 1,
        'image'       => 'https://chongker.com/cdn/shop/files/6391ace427ade714b70fb966024ae804_d196458c-aae3-4f57-9c5f-8ca9e7bd72c0.jpg',
        'description' => 'The Percy Ginger Cat 1.1 is not just a plush — it\'s a comforting companion. Featuring lifelike purring, a simulated heartbeat, and voice interaction, this robotic ginger cat is weighted for tactile comfort and designed to soothe anxiety, loneliness, and restlessness. Beloved by seniors and caregivers alike as a gentle, drug-free companion for those with dementia or memory loss.',
    ],
    [
        'name'        => 'Breathing Calico Percy 2.0 — Heartbeat, Purr & Voice',
        'slug'        => 'breathing-calico-percy-2-0-heartbeat-pur-voice-robonic-cat',
        'price'       => '139.00',
        'sale_price'  => '135.00',
        'category'    => 'robot-cats',
        'featured'    => 1,
        'image'       => 'https://chongker.com/cdn/shop/files/Companion_Robot_Cat.webp',
        'description' => 'The Breathing Calico Percy 2.0 takes companionship to the next level with fully handmade construction and realistic breathing movements layered over a comforting heartbeat and gentle purring. Voice-responsive and easy to charge via USB-C, this robotic calico cat is crafted for seniors, comfort seekers, and anyone who wants the warmth of a real pet without the responsibilities.',
    ],
    [
        'name'        => 'Percy 1.1 Robotic Dog — Border Collie Companion',
        'slug'        => 'percy-1-1-robotic-dog-border-collie-robotic-companion-dog',
        'price'       => '139.00',
        'sale_price'  => '99.00',
        'category'    => 'robot-dogs',
        'featured'    => 1,
        'image'       => 'https://chongker.com/cdn/shop/files/4_a559f2fc-d9f0-467f-9e24-daf979ff8201.jpg',
        'description' => 'Percy the robotic Border Collie is built for connection. Multi-touch sensors across the head, back, neck, tail, and front leg trigger authentic dog sounds. Teach Percy your name — it remembers and responds to it. Voice commands like "Good boy" and "I love you" are met with genuine reactions. A built-in soothing heartbeat, five volume levels, and a 2000 mAh battery (USB-C, ~4 hrs) round out this ideal companion for seniors, memory care residents, and anyone who misses having a dog.',
    ],
    [
        'name'        => 'Percy 1.1 Robotic Dog — Comfort Companion',
        'slug'        => 'percy-robotic-dog-companion-designed-for-comfort',
        'price'       => '139.00',
        'sale_price'  => '99.00',
        'category'    => 'robot-dogs',
        'featured'    => 1,
        'image'       => 'https://chongker.com/cdn/shop/files/lQDPKHblXg-RwenNBLDNBLCwWuJOIeb9A1gJ4XMzX61aAA_1200_1200.jpg',
        'description' => 'The Percy Robotic Dog Companion is designed to ease loneliness, anxiety, and depression through lifelike interaction. Multi-touch sensors respond like a real dog; a customizable name feature lets Percy learn to respond to voice commands. Includes a soothing heartbeat, auto sleep mode after 30 minutes, and five adjustable volume levels. Available in Golden Retriever and Border Collie styles. CPSC & CPSIA compliant. 1-year free replacement warranty.',
    ],
    [
        'name'        => 'Percy Ragdoll Cat 1.1 — Interactive Robot Cat',
        'slug'        => 'ragdoll-percy-interactive-cat-1-1',
        'price'       => '139.00',
        'sale_price'  => '99.00',
        'category'    => 'robot-cats',
        'featured'    => 1,
        'image'       => 'https://chongker.com/cdn/shop/files/8d4dd0dd16748f293fd9559e5f0ea2fd.jpg',
        'description' => 'The Percy Ragdoll Cat 1.1 combines the gentle, floppy charm of a real Ragdoll with interactive robotic features that bring genuine comfort. Voice-responsive purring, a calming heartbeat, and easy USB charging make this robotic companion a perfect match for seniors, gift buyers, or anyone who loves Ragdolls but can\'t keep a live pet.',
    ],
    [
        'name'        => 'Breathing Red Panda — Lifelike Calming Plush Companion',
        'slug'        => 'breathing-red-panda',
        'price'       => '139.00',
        'sale_price'  => null,
        'category'    => 'robot-birds-exotics',
        'featured'    => 1,
        'image'       => 'https://chongker.com/cdn/shop/files/Breathing_Red_Panda.jpg',
        'description' => 'The Chongker Breathing Red Panda is a handcrafted plush companion with rhythmic breathing that mimics a real animal, delivering a deeply soothing presence. Choose from three calming breathing modes — Calming, Balanced, and Restful — to match your mood or bedtime routine. Made with vegan materials, complete with a fluffy tail and vivid eyes, and USB-C rechargeable for up to 4 hours of use. Arrives gift-ready with a blanket included.',
    ],
    [
        'name'        => 'Percy 2.0 Golden British Shorthair — Breathing, Heartbeat & Voice',
        'slug'        => 'breathing-calico-percy-2-0-heartbeat-pur-voice-robonic-cat-golden',
        'price'       => '149.00',
        'sale_price'  => null,
        'category'    => 'robot-cats',
        'featured'    => 1,
        'image'       => 'https://chongker.com/cdn/shop/files/d91f024ab79cbb09141eddc4085cec19_3a488266-6a30-4df3-a6a8-3b14ec3150fa.jpg',
        'description' => 'The Percy 2.0 Golden British Shorthair is a next-generation robotic companion cat with realistic breathing, a soothing heartbeat, gentle purring, and voice interaction — all wrapped in the round, plush frame of a British Shorthair. Fully handmade and designed to bring a calming presence wherever it goes. Ideal for seniors, allergy sufferers, and anyone who loves the look and feel of a cat without the care requirements.',
    ],
    [
        'name'        => 'MateCat 1.1 — Interactive Robot Cat for Sensory Comfort',
        'slug'        => 'matecat10-interactive-cat',
        'price'       => '199.00',
        'sale_price'  => '159.00',
        'category'    => 'robot-cats',
        'featured'    => 0,
        'image'       => 'https://chongker.com/cdn/shop/files/mate_1.1.3.jpg',
        'description' => 'Built after 200+ hours of real cat form simulation, the MateCat 1.1 is Chongker\'s most carefully engineered robotic cat. The face is hand-shaped through 47 individual processes for authentic proportions, and the artificial fur matches genuine cat hair texture. Lifelike heartbeat, purring, tail wag, and head shake bring the companion to life. Great for sensory comfort and developing empathy in children. An ideal birthday or holiday gift.',
    ],
    [
        'name'        => 'Bopping Hamster Charm Keychain',
        'slug'        => 'bopping-hamster-charm-keychain-pendant',
        'price'       => '49.00',
        'sale_price'  => '29.99',
        'category'    => 'accessories-parts',
        'featured'    => 0,
        'image'       => 'https://chongker.com/cdn/shop/files/Electronic_Lifelike_Hamster.webp',
        'description' => 'A handmade collectible hamster keychain crafted with ultra-soft realistic fur and premium plush materials. Measures 12 cm tall × 8 cm wide and weighs just 60 grams — the perfect bag charm, camera strap accessory, or desk companion. Includes a metal keychain clip with safety ring. Charge via USB-C before first use. Blends fashion, fun, and nostalgia into a pocket-sized robotic pet.',
    ],
    [
        'name'        => 'USB-C Charging Cable for Percy Robot Cats',
        'slug'        => 'charging-cable-for-percy-robot-cats-usb-c',
        'price'       => '8.99',
        'sale_price'  => null,
        'category'    => 'accessories-parts',
        'featured'    => 0,
        'image'       => 'https://chongker.com/cdn/shop/files/typec.jpg',
        'description' => 'Official USB-C replacement and backup charging cable for Percy robot cats. Designed specifically for Percy models to ensure reliable charging performance and full compatibility. Keep your companion powered and ready.',
    ],
];

$inserted = 0;
$skipped  = 0;
$errors   = [];

$sql = "INSERT INTO products
    (name, slug, description, price, sale_price, image, category_id, active, featured, affiliate_url, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, NULL, NOW())
    ON DUPLICATE KEY UPDATE name=name"; // skip duplicates silently

$stmt = $pdo->prepare($sql);

foreach ($products as $p) {
    $cat_id = $cats[$p['category']] ?? null;
    if (!$cat_id) {
        $errors[] = "No category ID for '{$p['category']}' (product: {$p['name']})";
        continue;
    }
    try {
        $stmt->execute([
            $p['name'],
            $p['slug'],
            $p['description'],
            $p['price'],
            $p['sale_price'],
            $p['image'],
            $cat_id,
            $p['featured'],
        ]);
        if ($stmt->rowCount() > 0) {
            $inserted++;
        } else {
            $skipped++;
        }
    } catch (PDOException $e) {
        $errors[] = "Error inserting '{$p['name']}': " . $e->getMessage();
    }
}
?>
<!DOCTYPE html><html><head><title>Chongker Import</title>
<style>body{font-family:sans-serif;max-width:700px;margin:2rem auto;padding:0 1rem}
.ok{color:#16a34a}.skip{color:#ca8a04}.err{color:#dc2626}
table{border-collapse:collapse;width:100%}td,th{border:1px solid #ddd;padding:.5rem .75rem;text-align:left}
</style></head><body>
<h2>Chongker Product Import</h2>
<?php if ($errors): ?>
  <h3 class="err">Errors (<?= count($errors) ?>)</h3>
  <ul><?php foreach ($errors as $e): ?><li class="err"><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
<?php endif; ?>
<p class="ok">✓ <?= $inserted ?> product(s) inserted.</p>
<?php if ($skipped): ?><p class="skip">↷ <?= $skipped ?> product(s) skipped (already existed).</p><?php endif; ?>
<h3>Next steps</h3>
<ol>
  <li>Go to <a href="/admin/products.php">Admin → Products</a> and add the affiliate URL for each product from your Chongker partner portal.</li>
  <li>Mark any products you want featured from the admin panel.</li>
  <li>Delete <code>admin/import-chongker.php</code> — it has no further use.</li>
</ol>
</body></html>

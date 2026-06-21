<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo = db();

$products = $pdo->query(
    "SELECT id, name, image FROM products WHERE image LIKE '%chongker.com%' OR image LIKE '%cdn.shopify%'"
)->fetchAll();

if (!$products) {
    echo '<p style="font-family:sans-serif;color:#4ade80">✓ No external images found — all product images are already hosted locally.</p>';
    exit;
}

$ok = 0; $fail = 0; $results = [];

foreach ($products as $p) {
    $local = mirror_image_url($p['image']);
    if ($local === $p['image']) {
        // mirror failed, kept original
        $fail++;
        $results[] = ['name' => $p['name'], 'status' => 'fail', 'url' => $p['image']];
    } else {
        $stmt = $pdo->prepare('UPDATE products SET image = ? WHERE id = ?');
        $stmt->execute([$local, $p['id']]);
        $ok++;
        $results[] = ['name' => $p['name'], 'status' => 'ok', 'old' => $p['image'], 'new' => $local];
    }
}
?>
<!DOCTYPE html><html><head><title>Mirror Images</title>
<style>body{font-family:sans-serif;max-width:800px;margin:2rem auto;padding:0 1rem;background:#111;color:#f0e6d0}
.ok{color:#4ade80}.fail{color:#f87171}table{border-collapse:collapse;width:100%;font-size:.85rem}
td,th{padding:.5rem .75rem;border-bottom:1px solid #2e1f08;text-align:left}th{background:#1e1408}
</style></head><body>
<h2>Product Image Mirror</h2>
<p class="ok">✓ <?= $ok ?> image(s) mirrored successfully.</p>
<?php if ($fail): ?><p class="fail">✗ <?= $fail ?> image(s) failed — original URL kept.</p><?php endif; ?>
<table>
  <tr><th>Product</th><th>Status</th><th>Local URL</th></tr>
  <?php foreach ($results as $r): ?>
  <tr>
    <td><?= htmlspecialchars($r['name']) ?></td>
    <td class="<?= $r['status'] === 'ok' ? 'ok' : 'fail' ?>"><?= $r['status'] === 'ok' ? '✓ mirrored' : '✗ failed' ?></td>
    <td><small><?= htmlspecialchars($r['status'] === 'ok' ? $r['new'] : $r['url']) ?></small></td>
  </tr>
  <?php endforeach; ?>
</table>
<p style="margin-top:1.5rem;color:#a8906c">Delete this file when done: <code>admin/mirror-product-images.php</code></p>
</body></html>

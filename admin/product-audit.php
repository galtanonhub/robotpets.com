<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$products = db()->query(
    "SELECT p.*, c.name AS category_name FROM products p
     LEFT JOIN categories c ON c.id = p.category_id
     ORDER BY c.name, p.name"
)->fetchAll();
?>
<!DOCTYPE html><html><head><title>Product Audit</title>
<style>
body { font-family: sans-serif; max-width: 1000px; margin: 2rem auto; padding: 0 1rem; background: #111; color: #f0e6d0; }
h2 { margin-bottom: 1.5rem; }
table { border-collapse: collapse; width: 100%; font-size: .88rem; }
th { text-align: left; padding: .5rem .75rem; background: #1e1408; border-bottom: 2px solid #2e1f08; }
td { padding: .5rem .75rem; border-bottom: 1px solid #2e1f08; vertical-align: top; }
tr:hover td { background: #1a0f05; }
.ok  { color: #4ade80; font-weight: 600; }
.bad { color: #f87171; font-weight: 600; }
.warn { color: #fbbf24; font-weight: 600; }
.tag { display: inline-block; padding: .1rem .45rem; border-radius: 999px; font-size: .75rem; font-weight: 600; margin-right: .25rem; }
.tag-yes { background: #14532d; color: #4ade80; }
.tag-no  { background: #450a0a; color: #f87171; }
.tag-warn { background: #451a03; color: #fbbf24; }
a { color: #f59e0b; }
.summary { margin-bottom: 1.5rem; padding: 1rem; background: #1e1408; border-radius: 8px; display: flex; gap: 2rem; }
.summary-item span { display: block; font-size: 1.8rem; font-weight: 700; }
.summary-item label { font-size: .8rem; color: #a8906c; }
</style>
</head><body>
<h2>Product Audit</h2>

<?php
$totalProducts   = count($products);
$missingAffiliate = 0;
$missingImage    = 0;
$missingCategory = 0;
$inactive        = 0;

foreach ($products as $p) {
    if (empty($p['affiliate_url']))  $missingAffiliate++;
    if (empty($p['image']))          $missingImage++;
    if (empty($p['category_id']))    $missingCategory++;
    if (!$p['active'])               $inactive++;
}
?>

<div class="summary">
  <div class="summary-item"><span><?= $totalProducts ?></span><label>Total Products</label></div>
  <div class="summary-item"><span class="<?= $missingAffiliate ? 'bad' : 'ok' ?>"><?= $missingAffiliate ?></span><label>Missing Affiliate URL</label></div>
  <div class="summary-item"><span class="<?= $missingImage ? 'bad' : 'ok' ?>"><?= $missingImage ?></span><label>Missing Image</label></div>
  <div class="summary-item"><span class="<?= $missingCategory ? 'bad' : 'ok' ?>"><?= $missingCategory ?></span><label>Missing Category</label></div>
  <div class="summary-item"><span class="<?= $inactive ? 'warn' : 'ok' ?>"><?= $inactive ?></span><label>Inactive</label></div>
</div>

<table>
  <thead>
    <tr>
      <th>Product</th>
      <th>Category</th>
      <th>Price</th>
      <th>Affiliate URL</th>
      <th>Image</th>
      <th>Flags</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($products as $p): ?>
    <tr>
      <td><strong><?= h($p['name']) ?></strong><br><small style="color:#a8906c"><?= h($p['slug']) ?></small></td>
      <td><?= $p['category_name'] ? h($p['category_name']) : '<span class="bad">None</span>' ?></td>
      <td>
        <?= money($p['price']) ?>
        <?php if (!empty($p['compare_at_price'])): ?>
          <br><small style="color:#a8906c;text-decoration:line-through"><?= money($p['compare_at_price']) ?></small>
        <?php endif; ?>
      </td>
      <td>
        <?php if (!empty($p['affiliate_url'])): ?>
          <span class="tag tag-yes">✓ set</span>
          <br><small style="color:#a8906c;word-break:break-all"><?= h(substr($p['affiliate_url'], 0, 60)) ?>...</small>
        <?php else: ?>
          <span class="tag tag-no">✗ missing</span>
        <?php endif; ?>
      </td>
      <td>
        <?php if (!empty($p['image'])): ?>
          <span class="tag tag-yes">✓ set</span>
        <?php else: ?>
          <span class="tag tag-no">✗ missing</span>
        <?php endif; ?>
      </td>
      <td>
        <?php if ($p['active']): ?><span class="tag tag-yes">active</span><?php else: ?><span class="tag tag-no">hidden</span><?php endif; ?>
        <?php if (!empty($p['is_hero'])): ?><span class="tag tag-yes">hero</span><?php endif; ?>
        <?php if ($p['featured']): ?><span class="tag" style="background:#1c1254;color:#a78bfa">featured</span><?php endif; ?>
      </td>
      <td><a href="/admin/product-form.php?id=<?= (int)$p['id'] ?>">Edit</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</body></html>

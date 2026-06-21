<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $stmt = db()->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([(int)$_POST['id']]);
    header('Location: /admin/products.php');
    exit;
}

$products = db()->query(
    'SELECT p.*, c.name AS category_name FROM products p
     LEFT JOIN categories c ON c.id = p.category_id ORDER BY p.created_at DESC'
)->fetchAll();

$title = 'Products';
include __DIR__ . '/includes/admin-header.php';
?>

<div class="page-head">
  <h1>Products</h1>
  <a href="/admin/product-form.php" class="btn-primary">+ Add Product</a>
</div>

<div class="panel">
  <table>
    <thead><tr><th></th><th>Name</th><th>SKU</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($products as $p): ?>
        <tr>
          <td class="thumb-cell">
            <?php if (!empty($p['image'])): ?><img src="<?= h($p['image']) ?>" alt="" class="thumb"><?php else: ?><span class="thumb thumb-empty">🤖</span><?php endif; ?>
          </td>
          <td>
            <strong><?= h($p['name']) ?></strong>
            <?php if (!empty($p['is_hero'])): ?> <span class="tag" style="background:var(--accent);color:#000;">hero</span><?php endif; ?>
            <?php if ($p['featured']): ?> <span class="tag">featured</span><?php endif; ?>
          </td>
          <td><?= h($p['sku'] ?: '—') ?></td>
          <td><?= h($p['category_name'] ?: '—') ?></td>
          <td><?= money($p['price']) ?></td>
          <td><?= (int)$p['stock'] ?></td>
          <td><span class="status <?= $p['active'] ? 'status-delivered' : 'status-cancelled' ?>"><?= $p['active'] ? 'active' : 'hidden' ?></span></td>
          <td class="actions-cell">
            <a href="/admin/product-form.php?id=<?= (int)$p['id'] ?>" class="btn-sm">Edit</a>
            <form method="post" onsubmit="return confirm('Delete <?= h(addslashes($p['name'])) ?>?')">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
              <button type="submit" class="btn-sm btn-danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>

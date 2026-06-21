<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo = db();
$id = (int)($_GET['id'] ?? 0);

$validStatuses = ['pending', 'paid', 'ordered_from_supplier', 'shipped', 'delivered', 'cancelled'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($_POST['status'] ?? '', $validStatuses, true)) {
    $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
    $stmt->execute([$_POST['status'], $id]);
    header("Location: /admin/order-detail.php?id=$id");
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
$stmt->execute([$id]);
$order = $stmt->fetch();
if (!$order) {
    header('Location: /admin/orders.php');
    exit;
}

$stmt = $pdo->prepare(
    'SELECT oi.*, p.supplier_url, p.supplier_price, p.image FROM order_items oi
     LEFT JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?'
);
$stmt->execute([$id]);
$items = $stmt->fetchAll();

$title = 'Order #' . $order['id'];
include __DIR__ . '/includes/admin-header.php';
?>

<div class="page-head">
  <h1>Order #<?= (int)$order['id'] ?></h1>
  <a href="/admin/orders.php" class="btn-sm">← All orders</a>
</div>

<div class="order-detail-grid">
  <div class="panel">
    <h2>Items <span class="muted">(supplier links for drop-ship ordering)</span></h2>
    <table>
      <thead><tr><th></th><th>Product</th><th>Qty</th><th>Your Price</th><th>Supplier Cost</th><th>Supplier</th></tr></thead>
      <tbody>
        <?php foreach ($items as $i): ?>
          <tr>
            <td class="thumb-cell"><?php if (!empty($i['image'])): ?><img src="<?= h($i['image']) ?>" alt="" class="thumb"><?php else: ?><span class="thumb thumb-empty">🤖</span><?php endif; ?></td>
            <td><?= h($i['product_name']) ?></td>
            <td><?= (int)$i['quantity'] ?></td>
            <td><?= money($i['unit_price']) ?></td>
            <td><?= $i['supplier_price'] !== null ? money($i['supplier_price']) : '—' ?></td>
            <td><?php if (!empty($i['supplier_url'])): ?><a href="<?= h($i['supplier_url']) ?>" target="_blank" rel="noopener" class="btn-sm">Order from supplier ↗</a><?php else: ?>—<?php endif; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <p class="order-total">Order total: <strong><?= money($order['total']) ?></strong></p>
  </div>

  <div>
    <div class="panel">
      <h2>Status</h2>
      <form method="post" class="inline-form">
        <select name="status">
          <?php foreach ($validStatuses as $s): ?>
            <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>><?= str_replace('_', ' ', $s) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-primary">Update</button>
      </form>
    </div>
    <div class="panel">
      <h2>Customer</h2>
      <p><strong><?= h($order['customer_name']) ?></strong><br>
      <a href="mailto:<?= h($order['customer_email']) ?>"><?= h($order['customer_email']) ?></a></p>
      <p><?= h($order['address_line1']) ?><br>
      <?php if ($order['address_line2']): ?><?= h($order['address_line2']) ?><br><?php endif; ?>
      <?= h($order['city']) ?><?= $order['state'] ? ', ' . h($order['state']) : '' ?> <?= h($order['postal_code']) ?><br>
      <?= h($order['country']) ?></p>
      <p class="muted">Placed <?= date('n/j/Y g:i A', strtotime($order['created_at'])) ?></p>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>

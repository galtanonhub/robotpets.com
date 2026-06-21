<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$orders = db()->query('SELECT * FROM orders ORDER BY created_at DESC')->fetchAll();

$title = 'Orders';
include __DIR__ . '/includes/admin-header.php';
?>

<div class="page-head"><h1>Orders</h1></div>

<div class="panel">
  <?php if (!$orders): ?>
    <p class="muted">No orders yet. They'll appear here as customers check out.</p>
  <?php else: ?>
    <table>
      <thead><tr><th>#</th><th>Customer</th><th>Email</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td><?= (int)$o['id'] ?></td>
            <td><?= h($o['customer_name']) ?></td>
            <td><?= h($o['customer_email']) ?></td>
            <td><?= money($o['total']) ?></td>
            <td><span class="status status-<?= h($o['status']) ?>"><?= h(str_replace('_', ' ', $o['status'])) ?></span></td>
            <td><?= date('n/j/Y g:i A', strtotime($o['created_at'])) ?></td>
            <td><a href="/admin/order-detail.php?id=<?= (int)$o['id'] ?>" class="btn-sm">View</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>

<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo = db();
$stats = [
    'products' => (int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn(),
    'orders' => (int)$pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn(),
    'pending' => (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status IN ('pending','paid')")->fetchColumn(),
    'revenue' => (float)$pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status != 'cancelled'")->fetchColumn(),
];
$recentOrders = $pdo->query('SELECT * FROM orders ORDER BY created_at DESC LIMIT 5')->fetchAll();

$title = 'Dashboard';
include __DIR__ . '/includes/admin-header.php';
?>

<h1>Dashboard</h1>
<div class="stat-grid">
  <div class="stat-card"><span class="stat-value"><?= $stats['products'] ?></span><span class="stat-label">Products</span></div>
  <div class="stat-card"><span class="stat-value"><?= $stats['orders'] ?></span><span class="stat-label">Total Orders</span></div>
  <div class="stat-card"><span class="stat-value"><?= $stats['pending'] ?></span><span class="stat-label">Needs Action</span></div>
  <div class="stat-card"><span class="stat-value"><?= money($stats['revenue']) ?></span><span class="stat-label">Revenue</span></div>
</div>

<div class="panel">
  <div class="panel-head">
    <h2>Recent Orders</h2>
    <a href="/admin/orders.php" class="btn-sm">All orders →</a>
  </div>
  <?php if (!$recentOrders): ?>
    <p class="muted">No orders yet.</p>
  <?php else: ?>
    <table>
      <thead><tr><th>#</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($recentOrders as $o): ?>
          <tr>
            <td><?= (int)$o['id'] ?></td>
            <td><?= h($o['customer_name']) ?></td>
            <td><?= money($o['total']) ?></td>
            <td><span class="status status-<?= h($o['status']) ?>"><?= h(str_replace('_', ' ', $o['status'])) ?></span></td>
            <td><?= date('n/j/Y', strtotime($o['created_at'])) ?></td>
            <td><a href="/admin/order-detail.php?id=<?= (int)$o['id'] ?>" class="btn-sm">View</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>

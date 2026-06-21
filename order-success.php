<?php
require_once __DIR__ . '/includes/functions.php';
$orderId = (int)($_GET['id'] ?? 0);
$total = (float)($_GET['total'] ?? 0);

$title = 'Order Confirmed';
include __DIR__ . '/includes/header.php';
?>

<section class="section container">
  <div class="empty-state success-state">
    <div class="success-icon">✓</div>
    <h1>Order #<?= $orderId ?> Confirmed!</h1>
    <p>Thank you for your purchase of <strong><?= money($total) ?></strong>. A confirmation email is on its way with payment and tracking details.</p>
    <a href="/shop.php" class="btn btn-primary btn-lg">Continue Shopping</a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

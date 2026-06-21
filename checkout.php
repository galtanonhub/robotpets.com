<?php
require_once __DIR__ . '/includes/functions.php';

[$items, $total] = cart_items();
if (!$items) {
    header('Location: /cart.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address1 = trim($_POST['address1'] ?? '');
    $address2 = trim($_POST['address2'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $postal = trim($_POST['postal'] ?? '');
    $country = trim($_POST['country'] ?? 'United States');

    if ($name && $email && $address1 && $city && $postal) {
        $pdo = db();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO orders (customer_name, customer_email, address_line1, address_line2, city, state, postal_code, country, total)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([$name, $email, $address1, $address2 ?: null, $city, $state ?: null, $postal, $country, $total]);
            $orderId = (int)$pdo->lastInsertId();

            $stmt = $pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, product_name, unit_price, quantity) VALUES (?, ?, ?, ?, ?)'
            );
            foreach ($items as $item) {
                $stmt->execute([$orderId, $item['product']['id'], $item['product']['name'], $item['product']['price'], $item['qty']]);
            }
            $pdo->commit();
            $_SESSION['cart'] = [];
            header("Location: /order-success.php?id=$orderId&total=" . urlencode((string)$total));
            exit;
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}

$title = 'Checkout';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container"><h1>Checkout</h1></div>
</section>

<section class="section container">
  <div class="cart-layout">
    <form action="/checkout.php" method="post" class="checkout-form">
      <h3>Shipping Details</h3>
      <div class="form-row">
        <label>Full Name<input type="text" name="name" required></label>
        <label>Email<input type="email" name="email" required></label>
      </div>
      <label>Address Line 1<input type="text" name="address1" required></label>
      <label>Address Line 2 (optional)<input type="text" name="address2"></label>
      <div class="form-row">
        <label>City<input type="text" name="city" required></label>
        <label>State / Province<input type="text" name="state"></label>
      </div>
      <div class="form-row">
        <label>Postal Code<input type="text" name="postal" required></label>
        <label>Country<input type="text" name="country" value="United States" required></label>
      </div>
      <p class="checkout-note">Payment is collected after order confirmation. You'll receive an email with payment instructions. (Connect Stripe/PayPal here when ready to go live.)</p>
      <button type="submit" class="btn btn-primary btn-lg btn-block">Place Order — <?= money($total) ?></button>
    </form>
    <aside class="cart-summary">
      <h3>Your Order</h3>
      <?php foreach ($items as $item): ?>
        <div class="summary-row"><span><?= h($item['product']['name']) ?> × <?= (int)$item['qty'] ?></span><span><?= money($item['line_total']) ?></span></div>
      <?php endforeach; ?>
      <div class="summary-row"><span>Shipping</span><span>FREE</span></div>
      <div class="summary-row summary-total"><span>Total</span><span><?= money($total) ?></span></div>
    </aside>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<?php
require_once __DIR__ . '/includes/functions.php';
[$items, $total] = cart_items();

$title = 'Your Cart';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container"><h1>Your Cart</h1></div>
</section>

<section class="section container">
  <?php if (!$items): ?>
    <div class="empty-state">
      <p>Your cart is empty.</p>
      <a href="/shop.php" class="btn btn-primary">Start Shopping</a>
    </div>
  <?php else: ?>
    <div class="cart-layout">
      <div class="cart-items">
        <?php foreach ($items as $item): $p = $item['product']; ?>
          <div class="cart-item">
            <a href="/product.php?slug=<?= h($p['slug']) ?>" class="cart-item-image">
              <?php if (!empty($p['image'])): ?>
                <img src="<?= h($p['image']) ?>" alt="<?= h($p['name']) ?>">
              <?php else: ?>
                <div class="image-placeholder">🤖</div>
              <?php endif; ?>
            </a>
            <div class="cart-item-info">
              <h3><a href="/product.php?slug=<?= h($p['slug']) ?>"><?= h($p['name']) ?></a></h3>
              <span class="price"><?= money($p['price']) ?></span>
            </div>
            <form action="/cart-action.php" method="post" class="cart-item-qty">
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="productId" value="<?= (int)$p['id'] ?>">
              <input type="number" name="qty" value="<?= (int)$item['qty'] ?>" min="0" max="<?= (int)$p['stock'] ?>">
              <button type="submit" class="btn btn-sm">Update</button>
            </form>
            <div class="cart-item-total"><?= money($item['line_total']) ?></div>
            <form action="/cart-action.php" method="post">
              <input type="hidden" name="action" value="remove">
              <input type="hidden" name="productId" value="<?= (int)$p['id'] ?>">
              <button type="submit" class="btn-remove" aria-label="Remove item">✕</button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
      <aside class="cart-summary">
        <h3>Order Summary</h3>
        <div class="summary-row"><span>Subtotal</span><span><?= money($total) ?></span></div>
        <div class="summary-row"><span>Shipping</span><span>FREE</span></div>
        <div class="summary-row summary-total"><span>Total</span><span><?= money($total) ?></span></div>
        <a href="/checkout.php" class="btn btn-primary btn-block btn-lg">Checkout</a>
        <a href="/shop.php" class="link-arrow">← Continue shopping</a>
      </aside>
    </div>
  <?php endif; ?>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

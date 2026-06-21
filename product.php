<?php
require_once __DIR__ . '/includes/functions.php';

$slug = $_GET['slug'] ?? '';
$stmt = db()->prepare(
    "SELECT p.*, c.name AS category_name FROM products p
     LEFT JOIN categories c ON c.id = p.category_id
     WHERE p.slug = ? AND p.active = 1"
);
$stmt->execute([$slug]);
$product = $stmt->fetch();
if (!$product) {
    http_response_code(404);
    include __DIR__ . '/404.php';
    exit;
}

$stmt = db()->prepare(
    'SELECT * FROM products WHERE active = 1 AND category_id <=> ? AND id != ? ORDER BY RAND() LIMIT 4'
);
$stmt->execute([$product['category_id'], $product['id']]);
$related = $stmt->fetchAll();

$title = $product['name'];
include __DIR__ . '/includes/header.php';
?>

<section class="section container product-detail">
  <div class="product-detail-grid">
    <div class="product-detail-image">
      <?php if (!empty($product['image'])): ?>
        <img src="<?= h($product['image']) ?>" alt="<?= h($product['name']) ?>">
      <?php else: ?>
        <div class="image-placeholder image-placeholder-lg">🤖</div>
      <?php endif; ?>
    </div>
    <div class="product-detail-info">
      <?php if (!empty($product['category_name'])): ?><span class="product-category"><?= h($product['category_name']) ?></span><?php endif; ?>
      <h1><?= h($product['name']) ?></h1>
      <?php if (!empty($product['sku'])): ?><p class="sku">SKU: <?= h($product['sku']) ?></p><?php endif; ?>
      <div class="product-price product-price-lg">
        <span class="price"><?= money($product['price']) ?></span>
        <?php if (!empty($product['compare_at_price']) && (float)$product['compare_at_price'] > (float)$product['price']): ?>
          <span class="compare-price"><?= money($product['compare_at_price']) ?></span>
          <span class="badge badge-sale">Save <?= money((float)$product['compare_at_price'] - (float)$product['price']) ?></span>
        <?php endif; ?>
      </div>
      <p class="product-description"><?= nl2br(h($product['description'])) ?></p>
      <?php if ((int)$product['stock'] > 0): ?>
        <p class="stock-status in-stock">✓ In stock — ships within 24 hours</p>
        <form class="add-form">
          <label>Qty
            <input type="number" name="qty" value="1" min="1" max="<?= (int)$product['stock'] ?>" id="qtyInput">
          </label>
          <button type="button" class="btn btn-primary btn-lg add-to-cart" data-product-id="<?= (int)$product['id'] ?>" data-qty-input="qtyInput">Add to Cart</button>
        </form>
      <?php else: ?>
        <p class="stock-status out-of-stock">Currently out of stock</p>
      <?php endif; ?>
      <ul class="product-perks">
        <li>🚚 Free worldwide shipping</li>
        <li>↩️ 30-day no-questions returns</li>
        <li>🛡 1-year warranty included</li>
      </ul>
    </div>
  </div>

  <?php if ($related): ?>
    <div class="section-head" style="margin-top:4rem"><h2>You May Also Like</h2></div>
    <div class="product-grid">
      <?php foreach ($related as $p) include __DIR__ . '/includes/product-card.php'; ?>
    </div>
  <?php endif; ?>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

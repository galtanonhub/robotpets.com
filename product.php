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

// Load approved reviews + stats
$stmt = db()->prepare(
    "SELECT * FROM reviews WHERE product_id = ? AND approved = 1 ORDER BY created_at DESC"
);
$stmt->execute([$product['id']]);
$reviews = $stmt->fetchAll();

$reviewCount = count($reviews);
$avgRating   = $reviewCount ? array_sum(array_column($reviews, 'rating')) / $reviewCount : 0;


$title       = $product['name'];
$description = mb_substr(strip_tags($product['description'] ?? ''), 0, 155) ?: 'Shop ' . $product['name'] . ' at RobotPets — lifelike robotic companion.';
$og_image    = !empty($product['image']) ? $product['image'] : null;
$og_type     = 'product';
$canonical   = SITE_URL . '/product.php?slug=' . urlencode($product['slug']);
$json_ld     = json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'Product',
    'name'        => $product['name'],
    'description' => strip_tags($product['description'] ?? ''),
    'image'       => $product['image'] ?? '',
    'offers'      => [
        '@type'         => 'Offer',
        'price'         => number_format((float)$product['price'], 2),
        'priceCurrency' => 'USD',
        'availability'  => 'https://schema.org/InStock',
        'url'           => SITE_URL . '/go/' . $product['slug'],
    ],
], JSON_UNESCAPED_SLASHES);

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

      <?php if ($reviewCount > 0): ?>
        <div class="rating-summary">
          <span class="stars-display"><?= str_repeat('★', round($avgRating)) . str_repeat('☆', 5 - round($avgRating)) ?></span>
          <span class="rating-avg"><?= number_format($avgRating, 1) ?></span>
          <a href="#reviews" class="rating-count"><?= $reviewCount ?> review<?= $reviewCount !== 1 ? 's' : '' ?></a>
        </div>
      <?php endif; ?>

      <div class="product-price product-price-lg">
        <span class="price"><?= money($product['price']) ?></span>
        <?php if (!empty($product['compare_at_price']) && (float)$product['compare_at_price'] > (float)$product['price']): ?>
          <span class="compare-price"><?= money($product['compare_at_price']) ?></span>
          <span class="badge badge-sale">Save <?= money((float)$product['compare_at_price'] - (float)$product['price']) ?></span>
        <?php endif; ?>
      </div>
      <p class="product-description"><?= nl2br(h($product['description'])) ?></p>
      <?php if (!empty($product['affiliate_url'])): ?>
        <a href="/go/<?= h($product['slug']) ?>" target="_blank" rel="noopener" class="btn btn-primary btn-lg" style="align-self:flex-start;">Check Price →</a>
        <p style="font-size:.8rem;color:var(--text-dim);margin-top:.7rem;max-width:440px;">RobotPets may earn a commission from this link, at no extra cost to you. <a href="/disclosure.php" style="color:inherit;text-decoration:underline;">Learn more</a></p>
      <?php else: ?>
        <p class="stock-status" style="color:var(--text-dim);">Availability coming soon</p>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($related): ?>
    <div class="section-head" style="margin-top:4rem"><h2>You May Also Like</h2></div>
    <div class="product-grid">
      <?php foreach ($related as $p) include __DIR__ . '/includes/product-card.php'; ?>
    </div>
  <?php endif; ?>
</section>

<?php if ($reviewCount > 0): ?>
<!-- Reviews — only shown when reviews exist -->
<section class="section container" id="reviews">
  <div class="reviews-wrap">
    <div class="reviews-header">
      <h2><?= $reviewCount ?> Review<?= $reviewCount !== 1 ? 's' : '' ?></h2>
      <div class="rating-bar">
        <span class="stars-display stars-lg"><?= str_repeat('★', round($avgRating)) . str_repeat('☆', 5 - round($avgRating)) ?></span>
        <span><?= number_format($avgRating, 1) ?> out of 5</span>
      </div>
    </div>
    <div class="review-list">
      <?php foreach ($reviews as $r): ?>
        <div class="review-card">
          <div class="review-meta">
            <strong><?= h($r['name']) ?></strong>
            <span class="stars-display"><?= str_repeat('★', (int)$r['rating']) . str_repeat('☆', 5 - (int)$r['rating']) ?></span>
            <span class="review-date"><?= date('F j, Y', strtotime($r['created_at'])) ?></span>
          </div>
          <?php if ($r['body']): ?><p><?= h($r['body']) ?></p><?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>

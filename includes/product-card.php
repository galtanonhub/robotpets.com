<?php /* expects $p = product row (optionally with category_name) */ ?>
<div class="product-card">
  <a href="/product.php?slug=<?= h($p['slug']) ?>" class="product-image">
    <?php if (!empty($p['image'])): ?>
      <img src="<?= h($p['image']) ?>" alt="<?= h($p['name']) ?>" loading="lazy">
    <?php else: ?>
      <div class="image-placeholder">🤖</div>
    <?php endif; ?>
    <?php if (!empty($p['compare_at_price']) && (float)$p['compare_at_price'] > (float)$p['price']): ?>
      <span class="badge badge-sale">SALE</span>
    <?php endif; ?>
  </a>
  <div class="product-info">
    <?php if (!empty($p['category_name'])): ?><span class="product-category"><?= h($p['category_name']) ?></span><?php endif; ?>
    <h3><a href="/product.php?slug=<?= h($p['slug']) ?>"><?= h($p['name']) ?></a></h3>
    <div class="product-price">
      <span class="price"><?= money($p['price']) ?></span>
      <?php if (!empty($p['compare_at_price']) && (float)$p['compare_at_price'] > (float)$p['price']): ?>
        <span class="compare-price"><?= money($p['compare_at_price']) ?></span>
      <?php endif; ?>
    </div>
    <?php if ((int)$p['stock'] > 0): ?>
      <button class="btn btn-primary btn-block add-to-cart" data-product-id="<?= (int)$p['id'] ?>">Add to Cart</button>
    <?php else: ?>
      <button class="btn btn-block" disabled>Out of Stock</button>
    <?php endif; ?>
  </div>
</div>

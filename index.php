<?php
require_once __DIR__ . '/includes/functions.php';

$featured = db()->query(
    "SELECT p.*, c.name AS category_name FROM products p
     LEFT JOIN categories c ON c.id = p.category_id
     WHERE p.active = 1 AND p.featured = 1 ORDER BY p.created_at DESC LIMIT 8"
)->fetchAll();
$categories = db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<!-- HERO: drop your video at media/hero.mp4 and it plays automatically -->
<section class="hero">
  <video class="hero-video" autoplay muted loop playsinline poster="/media/hero-poster.jpg" id="heroVideo">
    <source src="/media/hero.mp4" type="video/mp4">
  </video>
  <div class="hero-fallback" id="heroFallback"></div>
  <div class="hero-overlay"></div>
  <div class="hero-content container">
    <p class="hero-kicker">The future of companionship is here</p>
    <h1>Meet Your New<br><span class="glow">Best Friend</span></h1>
    <p class="hero-sub">Lifelike robotic pets with real personality. No feeding, no fur, no vet bills — just pure companionship, powered by AI.</p>
    <div class="hero-cta">
      <a href="/shop.php" class="btn btn-primary btn-lg">Shop All Pets</a>
      <a href="#featured" class="btn btn-ghost btn-lg">See Featured</a>
    </div>
  </div>
  <div class="hero-scroll">▼</div>
</section>

<section class="trust-bar">
  <div class="container trust-grid">
    <div><strong>Free Shipping</strong><span>On every order, worldwide</span></div>
    <div><strong>30-Day Returns</strong><span>Love it or send it back</span></div>
    <div><strong>1-Year Warranty</strong><span>Every companion covered</span></div>
    <div><strong>Secure Checkout</strong><span>Encrypted end to end</span></div>
  </div>
</section>

<section class="section container" id="featured">
  <div class="section-head">
    <h2>Featured Companions</h2>
    <a href="/shop.php" class="link-arrow">View all →</a>
  </div>
  <div class="product-grid">
    <?php foreach ($featured as $p) include __DIR__ . '/includes/product-card.php'; ?>
  </div>
</section>

<section class="section container">
  <div class="section-head"><h2>Browse by Category</h2></div>
  <div class="category-grid">
    <?php foreach ($categories as $c): ?>
      <a href="/shop.php?category=<?= h($c['slug']) ?>" class="category-card"><span><?= h($c['name']) ?></span></a>
    <?php endforeach; ?>
  </div>
</section>

<section class="cta-band">
  <div class="container">
    <h2>Ready for a pet that never sheds?</h2>
    <a href="/shop.php" class="btn btn-primary btn-lg">Find Your Companion</a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

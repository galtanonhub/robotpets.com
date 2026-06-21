<?php
require_once __DIR__ . '/includes/functions.php';

$featured   = db()->query(
    "SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p
     LEFT JOIN categories c ON c.id = p.category_id
     WHERE p.active = 1 AND p.featured = 1 ORDER BY RAND() LIMIT 8"
)->fetchAll();

$categories = db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();

$hero = $featured[0] ?? null;

$cat_icons = [
    'robot-dogs'          => '🐕',
    'robot-cats'          => '🐈',
    'robot-birds-exotics' => '🦜',
    'accessories-parts'   => '🔧',
];

$title       = 'RobotPets — Companions of the Future';
$description = 'Discover lifelike robotic companions at RobotPets — robot dogs, cats, birds and more. No vet bills, no allergies, free shipping on every order.';
$json_ld     = json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'Organization',
    'name'        => 'RobotPets',
    'url'         => SITE_URL,
    'description' => $description,
], JSON_UNESCAPED_SLASHES);

include __DIR__ . '/includes/header.php';
?>

<section class="hero-grid-wrap">

  <div class="hero-grid">

    <!-- Main panel -->
    <div class="hero-main">
      <?php if ($hero): ?>
        <p class="hero-tag"><?= h($hero['category_name'] ?? 'Featured') ?></p>
        <h1>Meet <span class="glow"><?= h($hero['name']) ?></span></h1>
        <p class="hero-main-desc"><?= h(mb_substr($hero['description'] ?? '', 0, 150)) ?></p>
        <div class="hero-main-btns">
          <a href="/product.php?slug=<?= h($hero['slug']) ?>" class="btn btn-primary btn-lg">Shop Now — <?= money($hero['price']) ?></a>
          <a href="/shop.php" class="btn btn-ghost btn-lg">See All Pets →</a>
        </div>
      <?php else: ?>
        <p class="hero-tag">Welcome to RobotPets</p>
        <h1>Companions of<br><span class="glow">the Future</span></h1>
        <p class="hero-main-desc">Lifelike robotic pets with real personality. No vet bills, no allergies, no guilt when you travel — just pure companionship, powered by AI.</p>
        <div class="hero-main-btns">
          <a href="/shop.php" class="btn btn-primary btn-lg">Shop All Pets</a>
          <a href="/find-my-pet.php" class="btn btn-ghost btn-lg">Find My Pet →</a>
        </div>
      <?php endif; ?>
    </div>

    <!-- Side tiles -->
    <div class="hero-side">
      <?php for ($i = 1; $i <= 2; $i++): ?>
        <?php if (!empty($featured[$i])): $t = $featured[$i]; ?>
        <a href="/product.php?slug=<?= h($t['slug']) ?>" class="hero-tile">
          <div class="hero-tile-img">
            <?php if (!empty($t['image'])): ?>
              <img src="<?= h($t['image']) ?>" alt="<?= h($t['name']) ?>">
            <?php else: ?>
              <?= $cat_icons[$t['category_slug'] ?? ''] ?? '🤖' ?>
            <?php endif; ?>
          </div>
          <div class="hero-tile-body">
            <?php if (!empty($t['sale_price'])): ?>
              <span class="hero-tile-badge" style="background:var(--danger);color:#fff;">Sale</span>
            <?php else: ?>
              <span class="hero-tile-badge">Featured</span>
            <?php endif; ?>
            <div class="hero-tile-name"><?= h($t['name']) ?></div>
            <div class="hero-tile-sub"><?= h(mb_substr($t['description'] ?? '', 0, 65)) ?><?= mb_strlen($t['description'] ?? '') > 65 ? '…' : '' ?></div>
            <div class="hero-tile-price"><?= money($t['sale_price'] ?: $t['price']) ?></div>
          </div>
          <span class="hero-tile-arrow">›</span>
        </a>
        <?php else: ?>
        <a href="/shop.php" class="hero-tile">
          <div class="hero-tile-img">🛍️</div>
          <div class="hero-tile-body">
            <div class="hero-tile-name">Browse the Shop</div>
            <div class="hero-tile-sub">Explore all robotic companions</div>
          </div>
          <span class="hero-tile-arrow">›</span>
        </a>
        <?php endif; ?>
      <?php endfor; ?>

      <a href="/for-gifts.php" class="hero-tile hero-tile-gift">
        <div class="hero-tile-gift-icon">🎁</div>
        <div class="hero-tile-body">
          <div class="hero-tile-name" style="color:var(--accent);">Gift Guide</div>
          <div class="hero-tile-sub">Not sure? We'll help you find the perfect companion.</div>
        </div>
        <span class="hero-tile-arrow">›</span>
      </a>
    </div>

  </div>


</section>

<!-- Category strip -->
<div class="cat-strip">
  <?php foreach ($categories as $c): ?>
  <a href="/shop.php?category=<?= h($c['slug']) ?>" class="cat-strip-tile">
    <span class="cat-strip-icon"><?= $cat_icons[$c['slug']] ?? '🤖' ?></span>
    <span class="cat-strip-name"><?= h($c['name']) ?></span>
  </a>
  <?php endforeach; ?>
</div>

<!-- Featured products -->
<section class="section container" id="featured">
  <div class="section-head">
    <h2>Featured Companions</h2>
    <a href="/shop.php" class="link-arrow">View all →</a>
  </div>
  <div class="product-grid">
    <?php foreach ($featured as $p) include __DIR__ . '/includes/product-card.php'; ?>
  </div>
</section>

<section class="cta-band">
  <div class="container">
    <h2>Not sure where to start?</h2>
    <p style="color:var(--text-dim);margin:.75rem 0 1.8rem;">Answer 4 quick questions and we'll match you with the right companion.</p>
    <a href="/find-my-pet.php" class="btn btn-primary btn-lg">Take the Pet Finder Quiz →</a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

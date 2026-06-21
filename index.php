<?php
require_once __DIR__ . '/includes/functions.php';

$featured   = db()->query(
    "SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p
     LEFT JOIN categories c ON c.id = p.category_id
     WHERE p.active = 1 AND p.featured = 1 ORDER BY RAND() LIMIT 8"
)->fetchAll();

$categories = db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();

// Products grouped by category for tab switcher (exclude accessories)
$byCategory = [];
foreach ($categories as $c) {
    if ($c['slug'] === 'accessories-parts') continue;
    $stmt = db()->prepare(
        "SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p
         LEFT JOIN categories c ON c.id = p.category_id
         WHERE p.active = 1 AND p.category_id = ? ORDER BY p.created_at DESC LIMIT 8"
    );
    $stmt->execute([$c['id']]);
    $prods = $stmt->fetchAll();
    if ($prods) $byCategory[] = ['cat' => $c, 'products' => $prods];
}

// Use pinned hero product if set, otherwise fall back to first featured
$heroStmt = db()->query(
    "SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p
     LEFT JOIN categories c ON c.id = p.category_id
     WHERE p.active = 1 AND p.is_hero = 1 LIMIT 1"
);
$hero = $heroStmt->fetch() ?: ($featured[0] ?? null);

// Side tiles: 2 newest active products, excluding accessories and the hero
$heroId = $hero ? (int)$hero['id'] : 0;
$sideStmt = db()->prepare(
    "SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p
     LEFT JOIN categories c ON c.id = p.category_id
     WHERE p.active = 1 AND c.slug != 'accessories-parts' AND p.id != ?
     ORDER BY p.created_at DESC LIMIT 2"
);
$sideStmt->execute([$heroId]);
$sideTiles = $sideStmt->fetchAll();

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
      <?php for ($i = 0; $i <= 1; $i++): ?>
        <?php if (!empty($sideTiles[$i])): $t = $sideTiles[$i]; ?>
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

<!-- Shop by Category tabs -->
<?php if ($byCategory): ?>
<section class="section container shop-tabs-section">
  <div class="section-head">
    <h2>Shop by Category</h2>
    <a href="/shop.php" class="link-arrow">View all →</a>
  </div>
  <div class="tab-bar">
    <?php foreach ($byCategory as $i => $group): ?>
      <button class="tab-btn <?= $i === 0 ? 'active' : '' ?>" data-tab="<?= h($group['cat']['slug']) ?>">
        <?= $cat_icons[$group['cat']['slug']] ?? '🤖' ?> <?= h($group['cat']['name']) ?>
      </button>
    <?php endforeach; ?>
  </div>
  <div class="tab-panels">
    <?php foreach ($byCategory as $i => $group): ?>
      <div class="tab-panel <?= $i === 0 ? 'active' : '' ?>" id="tab-<?= h($group['cat']['slug']) ?>">
        <div class="tab-product-row">
          <?php foreach ($group['products'] as $p) include __DIR__ . '/includes/product-card.php'; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<script>
(function(){
  var btns   = document.querySelectorAll('.tab-btn');
  var panels = document.querySelectorAll('.tab-panel');
  btns.forEach(function(btn){
    btn.addEventListener('click', function(){
      btns.forEach(function(b){ b.classList.remove('active'); });
      panels.forEach(function(p){ p.classList.remove('active'); });
      btn.classList.add('active');
      document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
    });
  });
})();
</script>
<?php endif; ?>

<!-- Who is this for -->
<section class="section container audience-strip">
  <div class="section-head audience-head">
    <h2>Who Are Robotic Pets For?</h2>
  </div>
  <div class="audience-grid">
    <a href="/for-seniors.php" class="audience-card">
      <span class="audience-icon">🧓</span>
      <h3>Seniors &amp; Caregivers</h3>
      <p>Calm, comforting companions that ease loneliness and anxiety — no care required.</p>
      <span class="audience-link">Learn more →</span>
    </a>
    <a href="/for-kids.php" class="audience-card">
      <span class="audience-icon">👧</span>
      <h3>Kids &amp; Families</h3>
      <p>Safe, durable, and endlessly fun — perfect for teaching empathy and responsibility.</p>
      <span class="audience-link">Learn more →</span>
    </a>
    <a href="/for-allergies.php" class="audience-card">
      <span class="audience-icon">🌿</span>
      <h3>Allergy Sufferers</h3>
      <p>All the joy of a real pet with zero fur, dander, or sneezing.</p>
      <span class="audience-link">Learn more →</span>
    </a>
    <a href="/for-gifts.php" class="audience-card">
      <span class="audience-icon">🎁</span>
      <h3>Gift Buyers</h3>
      <p>A truly unique gift they'll never forget. We'll help you find the perfect match.</p>
      <span class="audience-link">Learn more →</span>
    </a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

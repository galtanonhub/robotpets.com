<?php
require_once __DIR__ . '/includes/functions.php';

$slug = trim($_GET['slug'] ?? '');
if (!$slug) { header('Location: /learn.php'); exit; }

$stmt = db()->prepare("SELECT * FROM posts WHERE slug = ? AND type = 'guide' AND active = 1");
$stmt->execute([$slug]);
$post = $stmt->fetch();
if (!$post) { include __DIR__ . '/404.php'; exit; }

$title       = $post['title'];
$description = $post['excerpt'] ? mb_substr(strip_tags($post['excerpt']), 0, 155) : mb_substr(strip_tags($post['body'] ?? ''), 0, 155);
$og_image    = $post['image'] ?: null;
$canonical   = SITE_URL . '/guide.php?slug=' . urlencode($post['slug']);
$_pub        = $post['published_at'] ?: $post['created_at'];
$_img        = !empty($post['image']) ? (strncmp($post['image'], 'http', 4) === 0 ? $post['image'] : SITE_URL . $post['image']) : null;
$_ld = [
    '@context'         => 'https://schema.org',
    '@type'            => 'Article',
    'headline'         => $post['title'],
    'description'      => strip_tags($post['excerpt'] ?: mb_substr(strip_tags($post['body'] ?? ''), 0, 200)),
    'datePublished'    => date('c', strtotime($_pub)),
    'dateModified'     => date('c', strtotime($_pub)),
    'author'           => ['@type' => 'Organization', 'name' => 'RobotPets'],
    'publisher'        => ['@type' => 'Organization', 'name' => 'RobotPets', 'url' => SITE_URL],
    'mainEntityOfPage' => $canonical,
];
if ($_img) $_ld['image'] = $_img;
$json_ld = json_encode($_ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
include __DIR__ . '/includes/header.php';
?>

<article class="post-page">
  <div class="post-page-hero">
    <?php if ($post['image']): ?>
      <img src="<?= h($post['image']) ?>" alt="<?= h($post['title']) ?>" class="post-page-img">
    <?php endif; ?>
    <div class="container">
      <a href="/learn.php" class="back-link">← Guides</a>
      <span class="post-type-tag post-type-guide">Guide</span>
      <h1><?= h($post['title']) ?></h1>
      <?php if ($post['excerpt']): ?><p class="post-page-excerpt"><?= h($post['excerpt']) ?></p><?php endif; ?>
    </div>
  </div>
  <div class="container post-page-body">
    <?= $post['body'] ?>
  </div>
</article>

<section class="cta-band">
  <div class="container">
    <h2>Ready to find your companion?</h2>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:1.4rem;">
      <a href="/shop.php" class="btn btn-primary btn-lg">Shop All Pets</a>
      <a href="/learn.php" class="btn btn-ghost btn-lg">More Guides</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

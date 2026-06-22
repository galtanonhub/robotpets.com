<?php
require_once __DIR__ . '/includes/functions.php';

$slug = trim($_GET['slug'] ?? '');
if (!$slug) { header('Location: /blog.php'); exit; }

$stmt = db()->prepare("SELECT * FROM posts WHERE slug = ? AND type = 'post' AND active = 1");
$stmt->execute([$slug]);
$post = $stmt->fetch();
if (!$post) { include __DIR__ . '/404.php'; exit; }

$title       = $post['title'];
$description = $post['excerpt'] ? mb_substr(strip_tags($post['excerpt']), 0, 155) : mb_substr(strip_tags($post['body'] ?? ''), 0, 155);
$og_image    = $post['image'] ?: null;
$canonical   = SITE_URL . '/blog-post.php?slug=' . urlencode($post['slug']);
$_pub        = $post['published_at'] ?: $post['created_at'];
$_img        = !empty($post['image']) ? (strncmp($post['image'], 'http', 4) === 0 ? $post['image'] : SITE_URL . $post['image']) : null;
$_ld = [
    '@context'         => 'https://schema.org',
    '@type'            => 'BlogPosting',
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
      <a href="/blog.php" class="back-link">← Blog</a>
      <span class="post-type-tag">Blog</span>
      <h1><?= h($post['title']) ?></h1>
      <?php if ($post['excerpt']): ?><p class="post-page-excerpt"><?= h($post['excerpt']) ?></p><?php endif; ?>
      <span class="post-date"><?= $post['published_at'] ? date('F j, Y', strtotime($post['published_at'])) : date('F j, Y', strtotime($post['created_at'])) ?></span>
    </div>
  </div>
  <div class="container post-page-body">
    <?= $post['body'] ?>
  </div>
</article>

<section class="cta-band">
  <div class="container">
    <h2>Ready to find your companion?</h2>
    <a href="/shop.php" class="btn btn-primary btn-lg" style="margin-top:1.4rem;">Shop All Pets</a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

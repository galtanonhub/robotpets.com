<?php
require_once __DIR__ . '/includes/functions.php';

$slug = trim($_GET['slug'] ?? '');
if (!$slug) { header('Location: /learn.php'); exit; }

$stmt = db()->prepare("SELECT * FROM posts WHERE slug = ? AND type = 'guide' AND active = 1");
$stmt->execute([$slug]);
$post = $stmt->fetch();
if (!$post) { include __DIR__ . '/404.php'; exit; }

$title = $post['title'];
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

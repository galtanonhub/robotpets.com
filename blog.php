<?php
require_once __DIR__ . '/includes/functions.php';

$posts = db()->query(
    "SELECT * FROM posts WHERE type = 'post' AND active = 1
     ORDER BY COALESCE(published_at, created_at) DESC"
)->fetchAll();

$title = 'Blog';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="hero-kicker">News &amp; Stories</p>
    <h1>The RobotPets Blog</h1>
    <p>Care tips, product news, owner stories, and everything happening in the world of AI companions.</p>
  </div>
</section>

<section class="section container">
  <?php if (!$posts): ?>
    <div class="empty-state">
      <p>No posts yet — check back soon.</p>
    </div>
  <?php else: ?>
    <div class="post-grid">
      <?php foreach ($posts as $p): ?>
        <a href="/blog-post.php?slug=<?= h($p['slug']) ?>" class="post-card">
          <?php if ($p['image']): ?>
            <div class="post-card-image"><img src="<?= h($p['image']) ?>" alt="<?= h($p['title']) ?>" loading="lazy"></div>
          <?php else: ?>
            <div class="post-card-image post-card-placeholder">📰</div>
          <?php endif; ?>
          <div class="post-card-body">
            <span class="post-type-tag">Blog</span>
            <h2><?= h($p['title']) ?></h2>
            <?php if ($p['excerpt']): ?><p><?= h($p['excerpt']) ?></p><?php endif; ?>
            <span class="post-date"><?= $p['published_at'] ? date('F j, Y', strtotime($p['published_at'])) : date('F j, Y', strtotime($p['created_at'])) ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

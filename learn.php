<?php
require_once __DIR__ . '/includes/functions.php';

$guides = db()->query(
    "SELECT * FROM posts WHERE type = 'guide' AND active = 1
     ORDER BY COALESCE(published_at, created_at) DESC"
)->fetchAll();

$title = 'Learn';
$description = 'Robotic pet guides — setup tutorials, buying guides, comparisons, and care tips for your AI companion.';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="hero-kicker">Guides &amp; Resources</p>
    <h1>Learn About Robotic Pets</h1>
    <p>Everything you need to know — from choosing your first companion to getting the most out of advanced AI features.</p>
  </div>
</section>

<section class="section container">
  <?php if (!$guides): ?>
    <div class="empty-state">
      <p>Guides coming soon — check back shortly.</p>
    </div>
  <?php else: ?>
    <div class="post-grid">
      <?php foreach ($guides as $p): ?>
        <a href="/guide.php?slug=<?= h($p['slug']) ?>" class="post-card">
          <?php if ($p['image']): ?>
            <div class="post-card-image"><img src="<?= h($p['image']) ?>" alt="<?= h($p['title']) ?>" loading="lazy"></div>
          <?php else: ?>
            <div class="post-card-image post-card-placeholder">📖</div>
          <?php endif; ?>
          <div class="post-card-body">
            <span class="post-type-tag post-type-guide">Guide</span>
            <h2><?= h($p['title']) ?></h2>
            <?php if ($p['excerpt']): ?><p><?= h($p['excerpt']) ?></p><?php endif; ?>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<section class="cta-band">
  <div class="container">
    <h2>Have a question not covered here?</h2>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:1.6rem;">
      <a href="/faq.php" class="btn btn-primary btn-lg">Read the FAQ</a>
      <a href="/contact.php" class="btn btn-ghost btn-lg">Ask Us Directly</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

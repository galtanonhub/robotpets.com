<?php
require_once __DIR__ . '/functions.php';
$_seo_title  = isset($title) ? h($title) . ' | RobotPets' : 'RobotPets — Companions of the Future';
$_seo_desc   = h($description ?? 'RobotPets.com — lifelike robotic companions. Robot dogs, cats, birds and more. Free shipping on every order.');
$_seo_url    = h(($canonical ?? SITE_URL . strtok($_SERVER['REQUEST_URI'], '?')));
$_seo_image  = h($og_image ?? SITE_URL . '/media/og-default.jpg');
$_seo_type   = $og_type ?? 'website';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $_seo_title ?></title>
  <meta name="description" content="<?= $_seo_desc ?>">
  <link rel="canonical" href="<?= $_seo_url ?>">
  <meta property="og:type" content="<?= $_seo_type ?>">
  <meta property="og:title" content="<?= $_seo_title ?>">
  <meta property="og:description" content="<?= $_seo_desc ?>">
  <meta property="og:url" content="<?= $_seo_url ?>">
  <meta property="og:image" content="<?= $_seo_image ?>">
  <meta property="og:site_name" content="RobotPets">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= $_seo_title ?>">
  <meta name="twitter:description" content="<?= $_seo_desc ?>">
  <meta name="twitter:image" content="<?= $_seo_image ?>">
  <?php if (!empty($json_ld)): ?>
  <script type="application/ld+json"><?= $json_ld ?></script>
  <?php endif; ?>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🤖</text></svg>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/style.css?v=5">
</head>
<body>
<header class="site-header" id="siteHeader">
  <nav class="nav container">
    <a href="/" class="logo">ROBOT<span>PETS</span></a>
    <div class="nav-links">
      <a href="/">Home</a>
      <div class="nav-dropdown">
        <a href="/shop.php" class="nav-drop-trigger">Shop ▾</a>
        <div class="nav-drop-menu">
          <a href="/shop.php">All Products</a>
          <a href="/shop.php?category=robot-dogs">Robot Dogs</a>
          <a href="/shop.php?category=robot-cats">Robot Cats</a>
          <a href="/shop.php?category=robot-birds-exotics">Birds &amp; Exotics</a>
          <a href="/shop.php?category=accessories-parts">Accessories</a>
        </div>
      </div>
      <div class="nav-dropdown">
        <a href="/about.php" class="nav-drop-trigger">Who It's For ▾</a>
        <div class="nav-drop-menu">
          <a href="/for-seniors.php">Seniors &amp; Caregivers</a>
          <a href="/for-kids.php">Kids &amp; Families</a>
          <a href="/for-allergies.php">Allergy Sufferers</a>
          <a href="/for-gifts.php">Gift Buyers</a>
        </div>
      </div>
      <div class="nav-dropdown">
        <a href="/learn.php" class="nav-drop-trigger">Learn ▾</a>
        <div class="nav-drop-menu">
          <a href="/learn.php">Guides</a>
          <a href="/blog.php">Blog &amp; News</a>
        </div>
      </div>
      <div class="nav-dropdown">
        <a href="/gallery.php" class="nav-drop-trigger">Community ▾</a>
        <div class="nav-drop-menu">
          <a href="/gallery.php">Owner Gallery</a>
          <a href="/find-my-pet.php">Pet Finder Quiz</a>
        </div>
      </div>
      <a href="/about.php">About</a>
      <a href="/faq.php">FAQ</a>
    </div>
    <div class="nav-actions">
      <form class="nav-search" action="/shop.php" method="get">
        <input type="search" name="q" placeholder="Search pets..." aria-label="Search products">
      </form>
      <a href="/cart.php" class="cart-link" aria-label="Shopping cart">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <span class="cart-count" id="cartCount"><?= cart_count() ?></span>
      </a>
      <button class="nav-hamburger" id="navHamburger" aria-label="Open menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </nav>
</header>

<div class="mobile-overlay" id="mobileOverlay"></div>
<nav class="mobile-drawer" id="mobileDrawer" aria-label="Mobile navigation">
  <div class="mobile-drawer-head">
    <a href="/" class="logo">ROBOT<span>PETS</span></a>
    <button class="mobile-drawer-close" id="drawerClose" aria-label="Close menu">&times;</button>
  </div>
  <div class="mobile-drawer-body">
    <a href="/" class="mobile-nav-link">Home</a>

    <p class="mobile-nav-section">Shop</p>
    <a href="/shop.php" class="mobile-nav-sub">All Products</a>
    <a href="/shop.php?category=robot-dogs" class="mobile-nav-sub">Robot Dogs</a>
    <a href="/shop.php?category=robot-cats" class="mobile-nav-sub">Robot Cats</a>
    <a href="/shop.php?category=robot-birds-exotics" class="mobile-nav-sub">Birds &amp; Exotics</a>
    <a href="/shop.php?category=accessories-parts" class="mobile-nav-sub">Accessories</a>

    <p class="mobile-nav-section">Who It's For</p>
    <a href="/for-seniors.php" class="mobile-nav-sub">Seniors &amp; Caregivers</a>
    <a href="/for-kids.php" class="mobile-nav-sub">Kids &amp; Families</a>
    <a href="/for-allergies.php" class="mobile-nav-sub">Allergy Sufferers</a>
    <a href="/for-gifts.php" class="mobile-nav-sub">Gift Buyers</a>

    <p class="mobile-nav-section">Learn</p>
    <a href="/learn.php" class="mobile-nav-sub">Guides</a>
    <a href="/blog.php" class="mobile-nav-sub">Blog &amp; News</a>

    <p class="mobile-nav-section">Community</p>
    <a href="/gallery.php" class="mobile-nav-sub">Owner Gallery</a>
    <a href="/find-my-pet.php" class="mobile-nav-sub">Pet Finder Quiz</a>

    <a href="/about.php" class="mobile-nav-link">About</a>
    <a href="/faq.php" class="mobile-nav-link">FAQ</a>
  </div>
</nav>

<script>
(function(){
  var ham     = document.getElementById('navHamburger');
  var overlay = document.getElementById('mobileOverlay');
  var drawer  = document.getElementById('mobileDrawer');
  var close   = document.getElementById('drawerClose');
  function open(){ drawer.classList.add('open'); overlay.classList.add('open'); document.body.style.overflow='hidden'; }
  function shut(){ drawer.classList.remove('open'); overlay.classList.remove('open'); document.body.style.overflow=''; }
  ham.addEventListener('click', open);
  close.addEventListener('click', shut);
  overlay.addEventListener('click', shut);
})();
</script>

<main>

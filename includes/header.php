<?php
require_once __DIR__ . '/functions.php';
$_seo_title  = isset($title) ? h($title) . ' | RobotPets' : 'RobotPets — Companions of the Future';
$_seo_desc   = h($description ?? 'RobotPets.com — lifelike robotic companions. Robot dogs, cats, birds and more. No feeding, no fur, no vet bills.');
$_seo_url    = h(($canonical ?? SITE_URL . strtok($_SERVER['REQUEST_URI'], '?')));
$_seo_image  = h($og_image ?? SITE_URL . '/media/og-default.png');
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
  <script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@graph'   => [
      [
        '@type'       => 'Organization',
        '@id'         => SITE_URL . '/#organization',
        'name'        => 'RobotPets',
        'url'         => SITE_URL . '/',
        'description' => 'Lifelike robotic and AI companion pets — robot cats, dogs, birds and more.',
      ],
      [
        '@type'           => 'WebSite',
        '@id'             => SITE_URL . '/#website',
        'name'            => 'RobotPets',
        'url'             => SITE_URL . '/',
        'publisher'       => ['@id' => SITE_URL . '/#organization'],
        'potentialAction' => [
          '@type'       => 'SearchAction',
          'target'      => ['@type' => 'EntryPoint', 'urlTemplate' => SITE_URL . '/shop.php?q={search_term_string}'],
          'query-input' => 'required name=search_term_string',
        ],
      ],
    ],
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🤖</text></svg>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/style.css?v=11">
</head>
<body>
<header class="site-header" id="siteHeader">
  <nav class="nav container">
    <a href="/" class="logo">ROBOT<span>PETS</span></a>
    <div class="nav-links">
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
        <a href="/about.php" class="nav-drop-trigger">About ▾</a>
        <div class="nav-drop-menu">
          <a href="/for-seniors.php">Seniors &amp; Caregivers</a>
          <a href="/for-kids.php">Kids &amp; Families</a>
          <a href="/for-allergies.php">Allergy Sufferers</a>
          <a href="/for-gifts.php">Gift Buyers</a>
          <a href="/find-my-pet.php">Pet Finder Quiz</a>
        </div>
      </div>
      <div class="nav-dropdown">
        <a href="/learn.php" class="nav-drop-trigger">Learn ▾</a>
        <div class="nav-drop-menu">
          <a href="/learn.php">Guides</a>
          <a href="/blog.php">Blog &amp; News</a>
        </div>
      </div>
      <a href="/faq.php">FAQ</a>
    </div>
    <div class="nav-actions">
      <form class="nav-search" action="/shop.php" method="get">
        <input type="search" name="q" placeholder="Search pets..." aria-label="Search products">
      </form>
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

    <p class="mobile-nav-section">Shop</p>
    <a href="/shop.php" class="mobile-nav-sub">All Products</a>
    <a href="/shop.php?category=robot-dogs" class="mobile-nav-sub">Robot Dogs</a>
    <a href="/shop.php?category=robot-cats" class="mobile-nav-sub">Robot Cats</a>
    <a href="/shop.php?category=robot-birds-exotics" class="mobile-nav-sub">Birds &amp; Exotics</a>
    <a href="/shop.php?category=accessories-parts" class="mobile-nav-sub">Accessories</a>

    <p class="mobile-nav-section">About</p>
    <a href="/about.php" class="mobile-nav-sub">Our Mission</a>
    <a href="/for-seniors.php" class="mobile-nav-sub">Seniors &amp; Caregivers</a>
    <a href="/for-kids.php" class="mobile-nav-sub">Kids &amp; Families</a>
    <a href="/for-allergies.php" class="mobile-nav-sub">Allergy Sufferers</a>
    <a href="/for-gifts.php" class="mobile-nav-sub">Gift Buyers</a>
    <a href="/find-my-pet.php" class="mobile-nav-sub">Pet Finder Quiz</a>

    <p class="mobile-nav-section">Learn</p>
    <a href="/learn.php" class="mobile-nav-sub">Guides</a>
    <a href="/blog.php" class="mobile-nav-sub">Blog &amp; News</a>

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

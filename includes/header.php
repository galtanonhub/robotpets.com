<?php require_once __DIR__ . '/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? h($title) . ' | RobotPets' : 'RobotPets — Companions of the Future' ?></title>
  <meta name="description" content="RobotPets.com — lifelike robotic companions. Robot dogs, cats, birds and more. Free shipping on every order.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header class="site-header" id="siteHeader">
  <nav class="nav container">
    <a href="/" class="logo">ROBOT<span>PETS</span></a>
    <div class="nav-links">
      <a href="/">Home</a>
      <a href="/shop.php">Shop</a>
      <a href="/shop.php?category=robot-dogs">Dogs</a>
      <a href="/shop.php?category=robot-cats">Cats</a>
    </div>
    <div class="nav-actions">
      <form class="nav-search" action="/shop.php" method="get">
        <input type="search" name="q" placeholder="Search pets..." aria-label="Search products">
      </form>
      <a href="/cart.php" class="cart-link" aria-label="Shopping cart">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <span class="cart-count" id="cartCount"><?= cart_count() ?></span>
      </a>
    </div>
  </nav>
</header>
<main>

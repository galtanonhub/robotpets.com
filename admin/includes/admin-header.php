<?php
require_once __DIR__ . '/../../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? h($title) : 'Admin' ?> | RobotPets Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/admin.css">
</head>
<body class="admin-body">
<header class="admin-header">
  <a href="/admin/" class="admin-logo">🤖 RobotPets <span>Admin</span></a>
  <nav>
    <a href="/admin/">Dashboard</a>
    <a href="/admin/products.php">Products</a>
    <a href="/admin/product-audit.php">Audit</a>
    <a href="/admin/categories.php">Categories</a>
    <a href="/admin/orders.php">Orders</a>
    <a href="/admin/posts.php">Posts &amp; Guides</a>
    <a href="/admin/reviews.php">Reviews</a>
    <a href="/" target="_blank">View Site ↗</a>
  </nav>
  <form action="/admin/logout.php" method="post"><button type="submit" class="btn-logout">Log out</button></form>
</header>
<main class="admin-main">

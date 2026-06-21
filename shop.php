<?php
require_once __DIR__ . '/includes/functions.php';

$activeCategory = trim($_GET['category'] ?? '');
$searchQuery = trim($_GET['q'] ?? '');

$sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p
        LEFT JOIN categories c ON c.id = p.category_id WHERE p.active = 1";
$params = [];
if ($activeCategory !== '') {
    $sql .= ' AND c.slug = ?';
    $params[] = $activeCategory;
}
if ($searchQuery !== '') {
    $sql .= ' AND (p.name LIKE ? OR p.description LIKE ?)';
    $params[] = "%$searchQuery%";
    $params[] = "%$searchQuery%";
}
$sql .= ' ORDER BY p.featured DESC, p.created_at DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
$categories = db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();

$title = 'Shop';
$description = 'Browse our full collection of robotic pets — robot dogs, cats, birds, exotic companions and accessories. Free shipping on every order.';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <h1>Shop All Companions</h1>
    <?php if ($searchQuery !== ''): ?><p>Search results for "<?= h($searchQuery) ?>"</p><?php endif; ?>
  </div>
</section>

<section class="section container">
  <div class="shop-layout">
    <aside class="shop-filters">
      <h3>Categories</h3>
      <a href="/shop.php" class="filter-link <?= $activeCategory === '' ? 'active' : '' ?>">All Products</a>
      <?php foreach ($categories as $c): ?>
        <a href="/shop.php?category=<?= h($c['slug']) ?>" class="filter-link <?= $activeCategory === $c['slug'] ? 'active' : '' ?>"><?= h($c['name']) ?></a>
      <?php endforeach; ?>
    </aside>
    <div class="shop-results">
      <?php if (!$products): ?>
        <p class="empty-state">No products found. Try a different search or category.</p>
      <?php else: ?>
        <div class="product-grid">
          <?php foreach ($products as $p) include __DIR__ . '/includes/product-card.php'; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

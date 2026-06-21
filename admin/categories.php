<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    if ($action === 'delete') {
        $stmt = db()->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([(int)$_POST['id']]);
    } else {
        $name = trim($_POST['name'] ?? '');
        if ($name !== '') {
            $stmt = db()->prepare('INSERT IGNORE INTO categories (name, slug) VALUES (?, ?)');
            $stmt->execute([$name, slugify($name)]);
        }
    }
    header('Location: /admin/categories.php');
    exit;
}

$categories = db()->query(
    'SELECT c.*, COUNT(p.id) AS product_count FROM categories c
     LEFT JOIN products p ON p.category_id = c.id GROUP BY c.id ORDER BY c.name'
)->fetchAll();

$title = 'Categories';
include __DIR__ . '/includes/admin-header.php';
?>

<div class="page-head"><h1>Categories</h1></div>

<div class="panel">
  <form method="post" class="inline-form">
    <input type="hidden" name="action" value="add">
    <input type="text" name="name" placeholder="New category name" required>
    <button type="submit" class="btn-primary">Add Category</button>
  </form>
  <table>
    <thead><tr><th>Name</th><th>Slug</th><th>Products</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($categories as $c): ?>
        <tr>
          <td><strong><?= h($c['name']) ?></strong></td>
          <td><?= h($c['slug']) ?></td>
          <td><?= (int)$c['product_count'] ?></td>
          <td>
            <form method="post" onsubmit="return confirm('Delete category? Products in it will become uncategorized.')">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
              <button type="submit" class="btn-sm btn-danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>

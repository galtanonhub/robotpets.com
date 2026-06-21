<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo = db();
$product = null;
$error = null;
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if (!$product) {
        header('Location: /admin/products.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'sku' => trim($_POST['sku'] ?? '') ?: null,
        'description' => trim($_POST['description'] ?? ''),
        'price' => (float)($_POST['price'] ?? 0),
        'compare_at_price' => $_POST['compare_at_price'] !== '' ? (float)$_POST['compare_at_price'] : null,
        'affiliate_url' => trim($_POST['affiliate_url'] ?? '') ?: null,
        'supplier_url' => trim($_POST['supplier_url'] ?? '') ?: null,
        'supplier_price' => $_POST['supplier_price'] !== '' ? (float)$_POST['supplier_price'] : null,
        'category_id' => $_POST['category_id'] !== '' ? (int)$_POST['category_id'] : null,
        'stock' => (int)($_POST['stock'] ?? 0),
        'featured' => isset($_POST['featured']) ? 1 : 0,
        'active' => isset($_POST['active']) ? 1 : 0,
        'is_hero' => isset($_POST['is_hero']) ? 1 : 0,
    ];

    if ($data['name'] === '') {
        $error = 'Product name is required.';
    } else {
        // Image — file upload takes priority, then URL field
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                $error = 'Only image files are allowed (jpg, png, gif, webp).';
            } else {
                if (!is_dir(UPLOAD_DIR)) {
                    mkdir(UPLOAD_DIR, 0777, true);
                }
                $filename = time() . '-' . random_int(100000, 999999) . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . '/' . $filename);
                $data['image'] = UPLOAD_URL . '/' . $filename;
            }
        } elseif (!empty($_POST['image_url'])) {
            $data['image'] = trim($_POST['image_url']);
        }

        if (!$error) {
            // Only one product can be hero at a time
            if ($data['is_hero']) {
                $pdo->exec('UPDATE products SET is_hero = 0');
            }
            if ($product) {
                $sets = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
                $stmt = $pdo->prepare("UPDATE products SET $sets WHERE id = ?");
                $stmt->execute([...array_values($data), $product['id']]);
            } else {
                $slug = slugify($data['name']);
                $stmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE slug LIKE ?');
                $stmt->execute(["$slug%"]);
                $dupes = (int)$stmt->fetchColumn();
                if ($dupes > 0) {
                    $slug .= '-' . ($dupes + 1);
                }
                $data['slug'] = $slug;
                $cols = implode(', ', array_keys($data));
                $marks = implode(', ', array_fill(0, count($data), '?'));
                $stmt = $pdo->prepare("INSERT INTO products ($cols) VALUES ($marks)");
                $stmt->execute(array_values($data));
            }
            header('Location: /admin/products.php');
            exit;
        }
    }
    // Repopulate form with submitted values on error
    $product = array_merge($product ?? [], $data, ['id' => $id]);
}

$categories = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$featuredCount = (int)$pdo->query('SELECT COUNT(*) FROM products WHERE featured = 1')->fetchColumn();

$title = $product && $id ? 'Edit Product' : 'New Product';
include __DIR__ . '/includes/admin-header.php';
?>

<div class="page-head">
  <h1><?= $id ? 'Edit: ' . h($product['name']) : 'Add New Product' ?></h1>
  <a href="/admin/products.php" class="btn-sm">← Back to products</a>
</div>

<?php if ($error): ?><p class="form-error"><?= h($error) ?></p><?php endif; ?>

<form method="post" enctype="multipart/form-data" class="panel product-form">
  <div class="form-grid">
    <div>
      <label>Product Name *<input type="text" name="name" value="<?= h($product['name'] ?? '') ?>" required></label>
      <label>SKU<input type="text" name="sku" value="<?= h($product['sku'] ?? '') ?>"></label>
      <label>Description<textarea name="description" rows="6"><?= h($product['description'] ?? '') ?></textarea></label>
      <div class="form-row">
        <label>Price ($) *<input type="number" name="price" step="0.01" min="0" value="<?= h((string)($product['price'] ?? '')) ?>" required></label>
        <label>Compare-at Price ($)<input type="number" name="compare_at_price" step="0.01" min="0" value="<?= h((string)($product['compare_at_price'] ?? '')) ?>"></label>
      </div>
      <div class="form-row">
        <label>Supplier Cost ($)<input type="number" name="supplier_price" step="0.01" min="0" value="<?= h((string)($product['supplier_price'] ?? '')) ?>"></label>
        <label>Stock *<input type="number" name="stock" min="0" value="<?= (int)($product['stock'] ?? 0) ?>" required></label>
      </div>
      <label>Affiliate URL <small style="color:var(--text-dim);font-weight:400;">(your full affiliate link — becomes /go/slug on the site)</small><input type="url" name="affiliate_url" value="<?= h($product['affiliate_url'] ?? '') ?>" placeholder="https://www.amazon.com/dp/...?tag=yourtag"></label>
      <label>Supplier URL (internal reference only)<input type="url" name="supplier_url" value="<?= h($product['supplier_url'] ?? '') ?>" placeholder="https://supplier.com/product/..."></label>
    </div>
    <div>
      <label>Category
        <select name="category_id">
          <option value="">— None —</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= isset($product['category_id']) && (int)$product['category_id'] === (int)$c['id'] ? 'selected' : '' ?>><?= h($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Product Image<input type="file" name="image" accept="image/*"></label>
      <label>Image URL <small style="color:var(--text-dim);font-weight:400;">(paste a URL to use instead of uploading)</small><input type="url" name="image_url" value="<?= h($product['image'] ?? '') ?>" placeholder="https://..."></label>
      <?php if (!empty($product['image'])): ?>
        <div class="current-image"><img src="<?= h($product['image']) ?>" alt="Current image"><span>Current image</span></div>
      <?php endif; ?>
      <label class="check"><input type="checkbox" name="is_hero" <?= !empty($product['is_hero']) ? 'checked' : '' ?>> Hero product <small style="color:var(--text-dim);font-weight:400;">(homepage left panel — only one at a time)</small></label>
      <?php
        $isCurrentlyFeatured = !empty($product['featured']);
        $slotsUsed = $isCurrentlyFeatured ? $featuredCount : $featuredCount;
        $slotsFull = !$isCurrentlyFeatured && $featuredCount >= 8;
      ?>
      <label class="check">
        <input type="checkbox" name="featured" <?= $isCurrentlyFeatured ? 'checked' : '' ?> <?= $slotsFull ? 'disabled' : '' ?>>
        Featured Companions
        <small style="color:<?= $featuredCount >= 8 ? '#dc2626' : 'var(--text-dim)' ?>;font-weight:400;">
          (<?= $featuredCount ?>/8 slots used<?= $slotsFull ? ' — remove one first' : '' ?>)
        </small>
      </label>
      <label class="check"><input type="checkbox" name="active" <?= !isset($product['active']) || $product['active'] ? 'checked' : '' ?>> Active (visible in store)</label>
    </div>
  </div>
  <button type="submit" class="btn-primary"><?= $id ? 'Save Changes' : 'Create Product' ?></button>
</form>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>

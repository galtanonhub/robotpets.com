<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo = db();
$post = null;
$error = null;
$id = (int)($_GET['id'] ?? 0);
$defaultType = in_array($_GET['type'] ?? '', ['post', 'guide']) ? $_GET['type'] : 'post';

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
    $stmt->execute([$id]);
    $post = $stmt->fetch();
    if (!$post) { header('Location: /admin/posts.php'); exit; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'type'         => in_array($_POST['type'] ?? '', ['post','guide']) ? $_POST['type'] : 'post',
        'title'        => trim($_POST['title'] ?? ''),
        'slug'         => trim($_POST['slug'] ?? ''),
        'excerpt'      => trim($_POST['excerpt'] ?? '') ?: null,
        'body'         => trim($_POST['body'] ?? '') ?: null,
        'published_at' => trim($_POST['published_at'] ?? '') ?: null,
        'active'       => isset($_POST['active']) ? 1 : 0,
    ];

    if ($data['title'] === '') { $error = 'Title is required.'; }
    if (!$data['slug']) {
        $data['slug'] = slugify($data['title']);
    } else {
        $data['slug'] = slugify($data['slug']);
    }

    if (!$error) {
        // Image upload
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg','jpeg','png','gif','webp'], true)) {
                $error = 'Only image files are allowed (jpg, png, gif, webp).';
            } else {
                if (!is_dir(UPLOAD_DIR)) { mkdir(UPLOAD_DIR, 0777, true); }
                $filename = time() . '-' . random_int(100000, 999999) . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . '/' . $filename);
                $data['image'] = UPLOAD_URL . '/' . $filename;
            }
        } elseif ($post && !empty($post['image'])) {
            $data['image'] = $post['image'];
        } else {
            $data['image'] = null;
        }
    }

    if (!$error) {
        try {
            if ($post) {
                $sets = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
                $stmt = $pdo->prepare("UPDATE posts SET $sets WHERE id = ?");
                $stmt->execute([...array_values($data), $post['id']]);
            } else {
                $cols = implode(', ', array_keys($data));
                $marks = implode(', ', array_fill(0, count($data), '?'));
                $stmt = $pdo->prepare("INSERT INTO posts ($cols) VALUES ($marks)");
                $stmt->execute(array_values($data));
            }
            header('Location: /admin/posts.php');
            exit;
        } catch (Exception $e) {
            $error = 'Save failed: ' . $e->getMessage();
        }
    }
    $post = array_merge($post ?? [], $data, ['id' => $id]);
}

$title = $id ? 'Edit Post' : 'New Post';
include __DIR__ . '/includes/admin-header.php';
?>

<div class="page-head">
  <h1><?= $id ? 'Edit: ' . h($post['title']) : 'New ' . ucfirst($post['type'] ?? $defaultType) ?></h1>
  <a href="/admin/posts.php" class="btn-sm">← Back to posts</a>
</div>

<?php if ($error): ?><p class="form-error"><?= h($error) ?></p><?php endif; ?>

<form method="post" enctype="multipart/form-data" class="panel product-form">
  <div class="form-grid">
    <div>
      <label>Type
        <select name="type">
          <option value="post" <?= ($post['type'] ?? $defaultType) === 'post' ? 'selected' : '' ?>>Blog Post</option>
          <option value="guide" <?= ($post['type'] ?? $defaultType) === 'guide' ? 'selected' : '' ?>>Guide</option>
        </select>
      </label>
      <label>Title *<input type="text" name="title" id="postTitle" value="<?= h($post['title'] ?? '') ?>" required></label>
      <label>Slug (URL key — auto-generated, editable)
        <input type="text" name="slug" id="postSlug" value="<?= h($post['slug'] ?? '') ?>" placeholder="auto-generated-from-title">
      </label>
      <label>Excerpt (short description shown in listings)
        <textarea name="excerpt" rows="3"><?= h($post['excerpt'] ?? '') ?></textarea>
      </label>
      <label>Body (HTML allowed)
        <textarea name="body" rows="20" style="font-family:monospace;font-size:.88rem;"><?= h($post['body'] ?? '') ?></textarea>
      </label>
    </div>
    <div>
      <label>Featured Image<input type="file" name="image" accept="image/*"></label>
      <?php if (!empty($post['image'])): ?>
        <div class="current-image"><img src="<?= h($post['image']) ?>" alt="Current image"><span>Current image</span></div>
      <?php endif; ?>
      <label>Publish Date &amp; Time<input type="datetime-local" name="published_at" value="<?= h($post['published_at'] ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : '') ?>"></label>
      <label class="check"><input type="checkbox" name="active" <?= !isset($post['active']) || $post['active'] ? 'checked' : '' ?>> Active (visible on site)</label>
    </div>
  </div>
  <button type="submit" class="btn-primary"><?= $id ? 'Save Changes' : 'Create' ?></button>
</form>

<script>
// Auto-fill slug from title (only when slug is empty)
document.getElementById('postTitle').addEventListener('input', function() {
  var slugField = document.getElementById('postSlug');
  if (!slugField.dataset.edited) {
    slugField.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
  }
});
document.getElementById('postSlug').addEventListener('input', function() {
  this.dataset.edited = '1';
});
</script>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>

<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $stmt = db()->prepare('DELETE FROM posts WHERE id = ?');
    $stmt->execute([(int)$_POST['id']]);
    header('Location: /admin/posts.php');
    exit;
}

$posts = db()->query('SELECT * FROM posts ORDER BY created_at DESC')->fetchAll();

$title = 'Posts & Guides';
include __DIR__ . '/includes/admin-header.php';
?>

<div class="page-head">
  <h1>Posts &amp; Guides</h1>
  <div style="display:flex;gap:.5rem;">
    <a href="/admin/post-form.php?type=post" class="btn-primary">+ New Blog Post</a>
    <a href="/admin/post-form.php?type=guide" class="btn-primary">+ New Guide</a>
  </div>
</div>

<div class="panel">
  <?php if (!$posts): ?>
    <p class="muted">No posts or guides yet. Create your first one above.</p>
  <?php else: ?>
    <table>
      <thead><tr><th>Type</th><th>Title</th><th>Slug</th><th>Published</th><th>Status</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($posts as $p): ?>
          <tr>
            <td><span class="tag"><?= h($p['type']) ?></span></td>
            <td><strong><?= h($p['title']) ?></strong></td>
            <td style="color:var(--muted);font-size:.85rem;"><?= h($p['slug']) ?></td>
            <td><?= $p['published_at'] ? date('n/j/Y', strtotime($p['published_at'])) : '<span style="color:var(--muted)">Draft</span>' ?></td>
            <td><span class="status <?= $p['active'] ? 'status-delivered' : 'status-cancelled' ?>"><?= $p['active'] ? 'active' : 'hidden' ?></span></td>
            <td class="actions-cell">
              <a href="/admin/post-form.php?id=<?= (int)$p['id'] ?>" class="btn-sm">Edit</a>
              <a href="<?= $p['type'] === 'post' ? '/blog-post.php' : '/guide.php' ?>?slug=<?= h($p['slug']) ?>" target="_blank" class="btn-sm">View</a>
              <form method="post" onsubmit="return confirm('Delete &quot;<?= h(addslashes($p['title'])) ?>&quot;?')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                <button type="submit" class="btn-sm btn-danger">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>

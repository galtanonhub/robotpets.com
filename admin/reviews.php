<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo = db();

// Approve / reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if ($id && $action === 'approve') {
        $pdo->prepare('UPDATE reviews SET approved = 1 WHERE id = ?')->execute([$id]);
    } elseif ($id && $action === 'reject') {
        $pdo->prepare('DELETE FROM reviews WHERE id = ?')->execute([$id]);
    }
    header('Location: /admin/reviews.php');
    exit;
}

$filter = $_GET['filter'] ?? 'pending';
$where  = $filter === 'approved' ? 'approved = 1' : 'approved = 0';

$reviews = $pdo->query(
    "SELECT r.*, p.name AS product_name, p.slug AS product_slug
     FROM reviews r
     LEFT JOIN products p ON p.id = r.product_id
     WHERE r.$where ORDER BY r.created_at DESC"
)->fetchAll();

$pending  = (int)$pdo->query('SELECT COUNT(*) FROM reviews WHERE approved = 0')->fetchColumn();
$approved = (int)$pdo->query('SELECT COUNT(*) FROM reviews WHERE approved = 1')->fetchColumn();

$title = 'Reviews';
include __DIR__ . '/includes/admin-header.php';
?>

<div class="page-head">
  <h1>Reviews</h1>
  <div style="display:flex;gap:.5rem;">
    <a href="/admin/reviews.php?filter=pending" class="btn-sm <?= $filter === 'pending' ? 'btn-primary' : '' ?>">Pending (<?= $pending ?>)</a>
    <a href="/admin/reviews.php?filter=approved" class="btn-sm <?= $filter === 'approved' ? 'btn-primary' : '' ?>">Approved (<?= $approved ?>)</a>
  </div>
</div>

<div class="panel">
  <?php if (!$reviews): ?>
    <p class="muted">No <?= $filter ?> reviews.</p>
  <?php else: ?>
    <table>
      <thead><tr><th>Product</th><th>Reviewer</th><th>Rating</th><th>Review</th><th>Date</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($reviews as $r): ?>
          <tr>
            <td><a href="/product.php?slug=<?= h($r['product_slug']) ?>" target="_blank"><?= h($r['product_name'] ?? '—') ?></a></td>
            <td>
              <strong><?= h($r['name']) ?></strong><br>
              <small style="color:var(--muted)"><?= h($r['email']) ?></small>
            </td>
            <td><?= str_repeat('★', (int)$r['rating']) ?><span style="color:var(--muted)"><?= str_repeat('☆', 5 - (int)$r['rating']) ?></span></td>
            <td style="max-width:320px;color:var(--muted);font-size:.9rem;"><?= h(mb_substr($r['body'] ?? '', 0, 160)) ?><?= mb_strlen($r['body'] ?? '') > 160 ? '…' : '' ?></td>
            <td style="white-space:nowrap;"><?= date('n/j/Y', strtotime($r['created_at'])) ?></td>
            <td class="actions-cell">
              <?php if (!$r['approved']): ?>
                <form method="post" style="display:inline;">
                  <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                  <input type="hidden" name="action" value="approve">
                  <button type="submit" class="btn-sm btn-primary">Approve</button>
                </form>
              <?php endif; ?>
              <form method="post" style="display:inline;" onsubmit="return confirm('Delete this review?')">
                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                <input type="hidden" name="action" value="reject">
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

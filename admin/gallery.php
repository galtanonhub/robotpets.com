<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if ($id && $action === 'approve') {
        $pdo->prepare('UPDATE gallery SET approved = 1 WHERE id = ?')->execute([$id]);
    } elseif ($id && $action === 'reject') {
        $pdo->prepare('DELETE FROM gallery WHERE id = ?')->execute([$id]);
    }
    header('Location: /admin/gallery.php');
    exit;
}

$filter  = $_GET['filter'] ?? 'pending';
$where   = $filter === 'approved' ? 'approved = 1' : 'approved = 0';
$entries = $pdo->query("SELECT * FROM gallery WHERE $where ORDER BY created_at DESC")->fetchAll();
$pending  = (int)$pdo->query('SELECT COUNT(*) FROM gallery WHERE approved = 0')->fetchColumn();
$approved = (int)$pdo->query('SELECT COUNT(*) FROM gallery WHERE approved = 1')->fetchColumn();

$title = 'Gallery';
include __DIR__ . '/includes/admin-header.php';
?>

<div class="page-head">
  <h1>Owner Gallery</h1>
  <div style="display:flex;gap:.5rem;">
    <a href="/admin/gallery.php?filter=pending" class="btn-sm <?= $filter === 'pending' ? 'btn-primary' : '' ?>">Pending (<?= $pending ?>)</a>
    <a href="/admin/gallery.php?filter=approved" class="btn-sm <?= $filter === 'approved' ? 'btn-primary' : '' ?>">Approved (<?= $approved ?>)</a>
  </div>
</div>

<div class="panel">
  <?php if (!$entries): ?>
    <p class="muted">No <?= $filter ?> gallery entries.</p>
  <?php else: ?>
    <table>
      <thead><tr><th>Photo</th><th>Pet Name</th><th>Owner</th><th>Story</th><th>Date</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($entries as $e): ?>
          <tr>
            <td class="thumb-cell"><img src="<?= h($e['image']) ?>" alt="" class="thumb"></td>
            <td><strong><?= h($e['pet_name']) ?></strong></td>
            <td><?= h($e['owner_name']) ?></td>
            <td style="max-width:260px;color:var(--muted);font-size:.88rem;"><?= h(mb_substr($e['story'] ?? '', 0, 120)) ?><?= mb_strlen($e['story'] ?? '') > 120 ? '…' : '' ?></td>
            <td style="white-space:nowrap;"><?= date('n/j/Y', strtotime($e['created_at'])) ?></td>
            <td class="actions-cell">
              <?php if (!$e['approved']): ?>
                <form method="post" style="display:inline;">
                  <input type="hidden" name="id" value="<?= (int)$e['id'] ?>">
                  <input type="hidden" name="action" value="approve">
                  <button type="submit" class="btn-sm btn-primary">Approve</button>
                </form>
              <?php endif; ?>
              <form method="post" style="display:inline;" onsubmit="return confirm('Delete this entry?')">
                <input type="hidden" name="id" value="<?= (int)$e['id'] ?>">
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

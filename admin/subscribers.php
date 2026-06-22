<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo = db();

// CSV export — ready to import into MailerLite/Mailchimp later
if (($_GET['export'] ?? '') === 'csv') {
    $rows = $pdo->query('SELECT email, source, created_at FROM subscribers ORDER BY created_at DESC')->fetchAll();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="subscribers.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['email', 'source', 'created_at']);
    foreach ($rows as $r) {
        fputcsv($out, [$r['email'], $r['source'], $r['created_at']]);
    }
    fclose($out);
    exit;
}

$subs  = $pdo->query('SELECT * FROM subscribers ORDER BY created_at DESC')->fetchAll();
$count = count($subs);

$title = 'Subscribers';
include __DIR__ . '/includes/admin-header.php';
?>

<div class="page-head">
  <h1>Subscribers<?= $count ? ' (' . $count . ')' : '' ?></h1>
  <?php if ($count): ?><a href="/admin/subscribers.php?export=csv" class="btn-sm">Download CSV</a><?php endif; ?>
</div>

<?php if (!$count): ?>
  <p class="muted">No subscribers yet. The signup form is in the site footer.</p>
<?php else: ?>
<div class="panel">
  <table>
    <thead><tr><th>Email</th><th>Source</th><th>Date</th></tr></thead>
    <tbody>
      <?php foreach ($subs as $s): ?>
        <tr>
          <td><?= h($s['email']) ?></td>
          <td><?= h($s['source']) ?></td>
          <td><?= date('n/j/Y g:i a', strtotime($s['created_at'])) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>

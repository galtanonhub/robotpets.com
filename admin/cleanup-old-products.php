<?php
/**
 * One-time cleanup: delete all products NOT in the Chongker import.
 * Run once, then delete this file.
 */
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$keep = [
    'matecat-pro-hyper-realistic-bionic-cat',
    'percy-interactive-cat',
    'percy-robot-ginger-cat-1-1-voice-purring-heartbeat-for-comfort',
    'breathing-calico-percy-2-0-heartbeat-pur-voice-robonic-cat',
    'percy-1-1-robotic-dog-border-collie-robotic-companion-dog',
    'percy-robotic-dog-companion-designed-for-comfort',
    'ragdoll-percy-interactive-cat-1-1',
    'breathing-red-panda',
    'breathing-calico-percy-2-0-heartbeat-pur-voice-robonic-cat-golden',
    'matecat10-interactive-cat',
    'bopping-hamster-charm-keychain-pendant',
    'charging-cable-for-percy-robot-cats-usb-c',
];

$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['confirm'] ?? '') === 'yes') {
    $marks = implode(',', array_fill(0, count($keep), '?'));
    $stmt  = $pdo->prepare("DELETE FROM products WHERE slug NOT IN ($marks)");
    $stmt->execute($keep);
    $deleted = $stmt->rowCount();
    echo "<p style='font-family:sans-serif;color:green'>✓ {$deleted} old product(s) deleted. <a href='/admin/products.php'>Go to Products</a> — you can now delete this file.</p>";
    exit;
}

// Preview what will be deleted
$marks = implode(',', array_fill(0, count($keep), '?'));
$stmt  = $pdo->prepare("SELECT id, name, slug FROM products WHERE slug NOT IN ($marks) ORDER BY name");
$stmt->execute($keep);
$old = $stmt->fetchAll();
?>
<!DOCTYPE html><html><head><title>Cleanup Old Products</title>
<style>body{font-family:sans-serif;max-width:600px;margin:2rem auto;padding:0 1rem}
table{border-collapse:collapse;width:100%}td,th{border:1px solid #ddd;padding:.4rem .7rem}
.del{color:#dc2626}.btn{background:#dc2626;color:#fff;border:none;padding:.5rem 1.2rem;cursor:pointer;font-size:1rem}
</style></head><body>
<h2>Delete Old Products</h2>
<?php if (!$old): ?>
  <p style="color:green">Nothing to delete — only the 12 Chongker products are present.</p>
<?php else: ?>
  <p>The following <strong><?= count($old) ?></strong> product(s) will be permanently deleted:</p>
  <table><tr><th>ID</th><th>Name</th><th>Slug</th></tr>
  <?php foreach ($old as $r): ?>
    <tr><td><?= (int)$r['id'] ?></td><td class="del"><?= htmlspecialchars($r['name']) ?></td><td><?= htmlspecialchars($r['slug']) ?></td></tr>
  <?php endforeach; ?>
  </table>
  <form method="post" style="margin-top:1.5rem">
    <input type="hidden" name="confirm" value="yes">
    <button type="submit" class="btn">Delete these <?= count($old) ?> product(s)</button>
  </form>
<?php endif; ?>
</body></html>

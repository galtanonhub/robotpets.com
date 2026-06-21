<?php
require_once __DIR__ . '/includes/functions.php';
http_response_code(404);
$title = 'Not Found';
include __DIR__ . '/includes/header.php';
?>

<section class="section container">
  <div class="empty-state">
    <h1>404</h1>
    <p>This page powered down or never existed.</p>
    <a href="/" class="btn btn-primary">Back Home</a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

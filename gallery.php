<?php
require_once __DIR__ . '/includes/functions.php';

// Handle submission
$sent  = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ownerName = trim($_POST['owner_name'] ?? '');
    $petName   = trim($_POST['pet_name'] ?? '');
    $story     = trim($_POST['story'] ?? '');
    $honeypot  = $_POST['website'] ?? '';

    if ($honeypot !== '') {
        $sent = true;
    } elseif (!$ownerName || !$petName) {
        $error = 'Please fill in your name and your pet\'s name.';
    } elseif (empty($_FILES['image']['name']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please upload a photo of your companion.';
    } else {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','gif','webp'], true)) {
            $error = 'Only image files are allowed (jpg, png, gif, webp).';
        } else {
            if (!is_dir(UPLOAD_DIR)) { mkdir(UPLOAD_DIR, 0777, true); }
            $filename = 'gallery-' . time() . '-' . random_int(100000, 999999) . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . '/' . $filename);
            $imagePath = UPLOAD_URL . '/' . $filename;

            $stmt = db()->prepare(
                'INSERT INTO gallery (owner_name, pet_name, story, image) VALUES (?,?,?,?)'
            );
            $stmt->execute([$ownerName, $petName, $story ?: null, $imagePath]);
            $sent = true;
        }
    }
}

// Load approved entries
$entries = db()->query(
    "SELECT * FROM gallery WHERE approved = 1 ORDER BY created_at DESC"
)->fetchAll();

$title = 'Owner Gallery';
$description = 'Meet real RobotPets owners and their companions. Submit your own photo to be featured in our community gallery.';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="hero-kicker">Community</p>
    <h1>Owner Gallery</h1>
    <p>Real companions, real owners. Share your robot pet with the world.</p>
  </div>
</section>

<?php if ($entries): ?>
<section class="section container">
  <div class="gallery-grid">
    <?php foreach ($entries as $e): ?>
      <div class="gallery-card">
        <div class="gallery-img-wrap">
          <img src="<?= h($e['image']) ?>" alt="<?= h($e['pet_name']) ?>" loading="lazy">
        </div>
        <div class="gallery-card-body">
          <h3><?= h($e['pet_name']) ?></h3>
          <p class="gallery-owner">Owner: <?= h($e['owner_name']) ?></p>
          <?php if ($e['story']): ?><p class="gallery-story"><?= h($e['story']) ?></p><?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php else: ?>
<section class="section container">
  <p style="color:var(--text-dim);text-align:center;">No entries yet — be the first to share your companion!</p>
</section>
<?php endif; ?>

<section class="section bg-soft">
  <div class="container gallery-submit-wrap">
    <h2>Share Your Companion</h2>
    <p>Got a RobotPet? We'd love to feature them here. Submit a photo and a little about your companion and we'll add them to the gallery.</p>

    <?php if ($sent): ?>
      <div class="review-thanks" style="margin-top:1.5rem;">✓ Thanks for submitting! Your entry will appear here once approved.</div>
    <?php else: ?>
      <?php if ($error): ?><p class="form-error" style="margin-top:1rem;"><?= h($error) ?></p><?php endif; ?>
      <form method="post" enctype="multipart/form-data" class="gallery-form checkout-form">
        <label style="display:none;"><input type="text" name="website" tabindex="-1" autocomplete="off"></label>
        <div class="form-row">
          <label>Your Name *<input type="text" name="owner_name" value="<?= h($_POST['owner_name'] ?? '') ?>" required></label>
          <label>Your Pet's Name *<input type="text" name="pet_name" value="<?= h($_POST['pet_name'] ?? '') ?>" required></label>
        </div>
        <label>Photo *<input type="file" name="image" accept="image/*" required></label>
        <label>Tell us about them (optional)<textarea name="story" rows="4" placeholder="How did you get them? What do they do that surprises you?"><?= h($_POST['story'] ?? '') ?></textarea></label>
        <button type="submit" class="btn btn-primary">Submit to Gallery</button>
        <p class="review-note">Photos are reviewed before appearing in the gallery.</p>
      </form>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

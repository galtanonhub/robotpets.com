<?php
require_once __DIR__ . '/includes/functions.php';

$sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $honeypot = $_POST['website'] ?? '';

    if ($honeypot !== '') {
        // Silent bot trap
        $sent = true;
    } elseif (!$name || !$email || !$message) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $subject = '[RobotPets Support] ' . ($category ?: 'General') . ' — ' . $name;
        $body    = "Name: $name\nEmail: $email\nCategory: $category\n\n$message";
        $headers = "From: noreply@robotpets.com\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";
        if (mail(ADMIN_EMAIL, $subject, $body, $headers)) {
            $sent = true;
        } else {
            $error = 'Message could not be sent. Please email us directly at support@robotpets.com.';
        }
    }
}

$title = 'Contact';
$description = 'Get in touch with the RobotPets team for order help, technical support, returns, or general questions.';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <h1>Get in Touch</h1>
    <p>Questions, feedback, or need help with your companion — we're here.</p>
  </div>
</section>

<section class="section container contact-wrap">

  <div class="contact-grid">
    <div class="contact-info">
      <h2>We're real people</h2>
      <p>Our support team are robotic pet owners themselves. We typically respond within <strong>24 hours</strong> on business days.</p>

      <div class="contact-channels">
        <div class="contact-channel">
          <span class="channel-icon">✉️</span>
          <div>
            <strong>Email</strong>
            <a href="mailto:support@robotpets.com">support@robotpets.com</a>
          </div>
        </div>
        <div class="contact-channel">
          <span class="channel-icon">❓</span>
          <div>
            <strong>Common questions</strong>
            <a href="/faq.php">Check our FAQ first</a>
          </div>
        </div>
        <div class="contact-channel">
          <span class="channel-icon">📦</span>
          <div>
            <strong>Returns &amp; warranty</strong>
            <span>Policies vary by vendor — check the seller's page for details</span>
          </div>
        </div>
      </div>
    </div>

    <div class="contact-form-panel">
      <?php if ($sent): ?>
        <div class="form-success">
          <div class="success-icon">✓</div>
          <h2>Message sent!</h2>
          <p>Thanks for reaching out. We'll get back to you within 24 hours.</p>
          <a href="/" class="btn btn-primary" style="margin-top:1.5rem;">Back to Home</a>
        </div>
      <?php else: ?>
        <?php if ($error): ?>
          <div class="form-error"><?= h($error) ?></div>
        <?php endif; ?>
        <form method="post" class="checkout-form">
          <!-- Honeypot -->
          <label style="display:none;"><input type="text" name="website" tabindex="-1" autocomplete="off"></label>

          <h3>Send Us a Message</h3>

          <label>Your Name *<input type="text" name="name" value="<?= h($_POST['name'] ?? '') ?>" required></label>
          <label>Email Address *<input type="email" name="email" value="<?= h($_POST['email'] ?? '') ?>" required></label>

          <label>What's this about?
            <select name="category" class="form-select">
              <option value="">— Select a topic —</option>
              <option value="Order Help" <?= (($_POST['category'] ?? '') === 'Order Help') ? 'selected' : '' ?>>Order Help</option>
              <option value="Technical Support" <?= (($_POST['category'] ?? '') === 'Technical Support') ? 'selected' : '' ?>>Technical Support</option>
              <option value="Returns & Warranty" <?= (($_POST['category'] ?? '') === 'Returns & Warranty') ? 'selected' : '' ?>>Returns &amp; Warranty</option>
              <option value="Product Question" <?= (($_POST['category'] ?? '') === 'Product Question') ? 'selected' : '' ?>>Product Question</option>
              <option value="Other" <?= (($_POST['category'] ?? '') === 'Other') ? 'selected' : '' ?>>Other</option>
            </select>
          </label>

          <label>Message *
            <textarea name="message" rows="6" required><?= h($_POST['message'] ?? '') ?></textarea>
          </label>

          <button type="submit" class="btn btn-primary btn-block">Send Message</button>
        </form>
      <?php endif; ?>
    </div>
  </div>

</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

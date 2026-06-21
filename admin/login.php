<?php
require_once __DIR__ . '/../includes/functions.php';

if (is_admin()) {
    header('Location: /admin/');
    exit;
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = db()->prepare('SELECT * FROM admin_users WHERE email = ?');
    $stmt->execute([trim($_POST['email'] ?? '')]);
    $user = $stmt->fetch();
    if ($user && password_verify($_POST['password'] ?? '', $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $user['id'];
        header('Location: /admin/');
        exit;
    }
    $error = 'Invalid email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | RobotPets</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/admin.css">
</head>
<body class="admin-body login-body">
  <form action="/admin/login.php" method="post" class="login-card">
    <h1>🤖 RobotPets Admin</h1>
    <?php if ($error): ?><p class="form-error"><?= h($error) ?></p><?php endif; ?>
    <label>Email<input type="email" name="email" required autofocus></label>
    <label>Password<input type="password" name="password" required></label>
    <button type="submit" class="btn-primary">Sign In</button>
  </form>
</body>
</html>

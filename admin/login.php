<?php
require_once __DIR__ . '/auth.php';

$error = '';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (admin_login($username, $password)) {
        header('Location: index.php');
        exit;
    }

    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="robots" content="noindex,nofollow">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | One Source</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body class="login-page">
  <main class="login-wrap">
    <form method="post" class="login-card">
      <?= csrf_field() ?>
      <h1>One Source Admin</h1>

      <?php if ($error): ?>
        <div class="form-message error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if (!db_available()): ?>
        <div class="form-message error">Database is not configured. Complete config.local.php and run admin/install.php first.</div>
      <?php endif; ?>

      <label>Username
        <input type="text" name="username" required autocomplete="username">
      </label>

      <label>Password
        <input type="password" name="password" required autocomplete="current-password">
      </label>

      <button type="submit">Login</button>
      <a class="back-link" href="../index.php">← Back to website</a>
    </form>
  </main>
</body>
</html>

<?php
require_once __DIR__ . '/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $ADMIN_USERNAME && $password === $ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
        exit;
    }

    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="robots" content="noindex,nofollow">
  <meta charset="UTF-8">
  <title>Admin Login | One Source</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-login">
  <form method="post" class="login-card">
    <h1>Gallery Admin</h1>
    <p>Manage Our Work gallery images.</p>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <label>Username<input type="text" name="username" required></label>
    <label>Password<input type="password" name="password" required></label>
    <button type="submit">Login</button>
  </form>
</body>
</html>
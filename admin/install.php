<?php
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/security.php';

secure_session_start();

$message = '';
$error = '';

try {
    $pdo = db();

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admin_users (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            email VARCHAR(255) NULL,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            last_login_at DATETIME NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admin_login_attempts (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NULL,
            ip_address VARCHAR(45) NULL,
            success TINYINT(1) NOT NULL DEFAULT 0,
            attempted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    $count = (int)$pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf();

        if ($count > 0) {
            $error = 'Admin user already exists. For safety, this installer will not create another initial user.';
        } else {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if ($username === '' || strlen($password) < 12) {
                $error = 'Enter a username and a password of at least 12 characters.';
            } elseif ($password !== $confirm) {
                $error = 'Passwords do not match.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO admin_users (username, password_hash, email) VALUES (?, ?, ?)');
                $stmt->execute([$username, $hash, $email ?: null]);
                $message = 'Admin user created. Delete or rename admin/install.php now.';
                $count = 1;
            }
        }
    }
} catch (Throwable $e) {
    $error = $e->getMessage();
    $count = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="robots" content="noindex,nofollow">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Install Admin Security | One Source</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
<main class="admin-wrap">
  <section class="admin-panel">
    <h1>MySQL Admin Security Installer</h1>

    <?php if ($message): ?><div class="form-message success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="form-message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <p>This installer creates the required MySQL tables and the first admin user.</p>

    <?php if (!$error || $count === 0): ?>
      <?php if ($count === 0): ?>
        <form method="post" class="admin-form">
          <?= csrf_field() ?>

          <label>Admin Username
            <input type="text" name="username" required>
          </label>

          <label>Admin Email
            <input type="email" name="email">
          </label>

          <label>Password
            <input type="password" name="password" required minlength="12">
          </label>

          <label>Confirm Password
            <input type="password" name="confirm_password" required minlength="12">
          </label>

          <button type="submit">Create Admin User</button>
        </form>
      <?php else: ?>
        <p><strong>Admin user already exists.</strong></p>
        <p>For safety, delete or rename <code>admin/install.php</code>.</p>
      <?php endif; ?>
    <?php endif; ?>
  </section>
</main>
</body>
</html>

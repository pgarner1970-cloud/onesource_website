<?php
require_once __DIR__ . '/auth.php';
require_super_admin();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'create_user') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $role = ($_POST['role'] ?? '') === 'super_admin' ? 'super_admin' : 'admin';
            $password = $_POST['password'] ?? '';

            if ($username === '' || strlen($password) < 12) {
                throw new RuntimeException('Enter a username and a password of at least 12 characters.');
            }

            $stmt = db()->prepare('INSERT INTO admin_users (username, email, role, password_hash, password_changed_at) VALUES (?, ?, ?, ?, NOW())');
            $stmt->execute([$username, $email ?: null, $role, password_hash($password, PASSWORD_DEFAULT)]);
            admin_audit('admin_user_created', 'Created admin user: ' . $username);
            $message = 'Admin user created.';
        }

        if ($action === 'reset_password') {
            $id = (int)($_POST['id'] ?? 0);
            $password = $_POST['password'] ?? '';
            if ($id <= 0 || strlen($password) < 12) {
                throw new RuntimeException('Password must be at least 12 characters.');
            }
            $stmt = db()->prepare('UPDATE admin_users SET password_hash=?, password_changed_at=NOW(), failed_login_count=0, locked_until=NULL WHERE id=?');
            $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
            admin_audit('admin_password_reset', 'Reset password for user ID: ' . $id);
            $message = 'Password updated.';
        }

        if ($action === 'toggle_active') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id === (int)$_SESSION['admin_user_id']) {
                throw new RuntimeException('You cannot disable your own account.');
            }
            $stmt = db()->prepare('UPDATE admin_users SET is_active = IF(is_active=1,0,1) WHERE id=?');
            $stmt->execute([$id]);
            admin_audit('admin_user_toggle_active', 'Toggled user ID: ' . $id);
            $message = 'User status updated.';
        }
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$users = db()->query('SELECT id, username, email, role, is_active, last_login_at, locked_until, created_at FROM admin_users ORDER BY username ASC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
<title>Admin Users</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include __DIR__ . '/includes/admin-header.php'; ?>
<main class="admin-wrap">
  <section class="admin-panel">
    <h2>Admin Users</h2>
    <p class="admin-note">Only super admins can manage admin accounts.</p>
    <?php if ($message): ?><div class="form-message success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="form-message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <h3>Create Admin User</h3>
    <form method="post" class="admin-form">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="create_user">
      <label>Username <input type="text" name="username" required></label>
      <label>Email <input type="email" name="email"></label>
      <label>Role
        <select name="role">
          <option value="admin">Admin</option>
          <option value="super_admin">Super Admin</option>
        </select>
      </label>
      <label>Password <input type="password" name="password" required minlength="12"></label>
      <button type="submit">Create User</button>
    </form>
  </section>

  <section class="admin-panel">
    <h3>Existing Users</h3>
    <div class="admin-list">
      <?php foreach ($users as $user): ?>
        <div class="admin-list-item">
          <div>
            <strong><?= htmlspecialchars($user['username']) ?></strong>
            <span><?= htmlspecialchars($user['role']) ?> • <?= $user['is_active'] ? 'Active' : 'Disabled' ?><?php if ($user['locked_until']): ?> • Locked until <?= htmlspecialchars($user['locked_until']) ?><?php endif; ?></span>
            <span>Last login: <?= htmlspecialchars($user['last_login_at'] ?? 'Never') ?></span>
          </div>
          <div class="admin-actions">
            <form method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="toggle_active">
              <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
              <button class="<?= $user['is_active'] ? 'hide' : 'show' ?>" type="submit"><?= $user['is_active'] ? 'Disable' : 'Enable' ?></button>
            </form>
            <form method="post" class="password-reset-form">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="reset_password">
              <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
              <input type="password" name="password" placeholder="New password" minlength="12" required>
              <button type="submit">Reset</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>
</body>
</html>

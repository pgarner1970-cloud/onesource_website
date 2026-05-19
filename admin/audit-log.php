<?php
require_once __DIR__ . '/auth.php';
require_super_admin();
$rows = db()->query('SELECT * FROM admin_audit_log ORDER BY created_at DESC LIMIT 200')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
<title>Audit Log</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include __DIR__ . '/includes/admin-header.php'; ?>
<main class="admin-wrap">
  <section class="admin-panel">
    <h2>Admin Audit Log</h2>
    <div class="admin-table-wrap">
      <table class="admin-cost-table">
        <thead><tr><th>Date</th><th>User</th><th>Action</th><th>IP</th><th>Details</th></tr></thead>
        <tbody>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['created_at']) ?></td>
              <td><?= htmlspecialchars($row['username'] ?? '') ?></td>
              <td><?= htmlspecialchars($row['action']) ?></td>
              <td><?= htmlspecialchars($row['ip_address'] ?? '') ?></td>
              <td><?= htmlspecialchars($row['details'] ?? '') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>
</body>
</html>

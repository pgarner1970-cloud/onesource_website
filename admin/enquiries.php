<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/../includes/content-store.php';

$statuses = ['new'=>'New','contacted'=>'Contacted','quoted'=>'Quoted','booked'=>'Booked','completed'=>'Completed','spam'=>'Spam','archived'=>'Archived'];
$message = '';
$error = '';

if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $rows = get_enquiries();
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="enquiries-' . date('Ymd-His') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID','Date','Name','Email','Phone','Service','Status','Message','Notes']);
    foreach ($rows as $row) {
        fputcsv($out, [$row['id'],$row['created_at'],$row['name'],$row['email'],$row['phone'],$row['service'],$row['status'],$row['message'],$row['admin_notes']]);
    }
    fclose($out);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        if (($_POST['action'] ?? '') === 'update') {
            $id = (int)($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? 'new';
            if (!isset($statuses[$status])) { $status = 'new'; }
            update_enquiry($id, $status, trim($_POST['admin_notes'] ?? ''));
            admin_audit('enquiry_updated', 'Updated enquiry ID: ' . $id);
            $message = 'Enquiry updated.';
        }
        if (($_POST['action'] ?? '') === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            delete_enquiry($id);
            admin_audit('enquiry_deleted', 'Deleted enquiry ID: ' . $id);
            $message = 'Enquiry deleted.';
        }
    } catch (Throwable $e) { $error = $e->getMessage(); }
}

$filter = $_GET['status'] ?? '';
if (!isset($statuses[$filter])) { $filter = ''; }
$enquiries = get_enquiries($filter);
$edit = !empty($_GET['view']) ? get_enquiry((int)$_GET['view']) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
<title>Enquiries | Site Admin</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include __DIR__ . '/includes/admin-header.php'; ?>
<main class="admin-wrap">
  <section class="admin-panel">
    <h2>Enquiries</h2>
    <p class="admin-note">Track website enquiries from the contact form.</p>
    <?php if ($message): ?><div class="form-message success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="form-message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <div class="filter-row">
      <a class="filter <?= $filter === '' ? 'active' : '' ?>" href="enquiries.php">All</a>
      <?php foreach ($statuses as $key => $label): ?>
        <a class="filter <?= $filter === $key ? 'active' : '' ?>" href="enquiries.php?status=<?= urlencode($key) ?>"><?= htmlspecialchars($label) ?></a>
      <?php endforeach; ?>
      <a class="filter" href="enquiries.php?export=csv">Export CSV</a>
    </div>
  </section>

  <?php if ($edit): ?>
  <section class="admin-panel">
    <h3>Enquiry #<?= (int)$edit['id'] ?></h3>
    <div class="enquiry-detail">
      <p><strong>Date:</strong> <?= htmlspecialchars($edit['created_at']) ?></p>
      <p><strong>Name:</strong> <?= htmlspecialchars($edit['name']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($edit['email'] ?? '') ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($edit['phone'] ?? '') ?></p>
      <p><strong>Service:</strong> <?= htmlspecialchars($edit['service'] ?? '') ?></p>
      <p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($edit['message'])) ?></p>
    </div>
    <form method="post" class="admin-form">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" value="<?= (int)$edit['id'] ?>">
      <label>Status
        <select name="status">
          <?php foreach ($statuses as $key => $label): ?>
            <option value="<?= htmlspecialchars($key) ?>" <?php if ($edit['status'] === $key) echo 'selected'; ?>><?= htmlspecialchars($label) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Admin Notes
        <textarea name="admin_notes" rows="5"><?= htmlspecialchars($edit['admin_notes'] ?? '') ?></textarea>
      </label>
      <button type="submit">Save Enquiry</button>
    </form>
  </section>
  <?php endif; ?>

  <section class="admin-panel">
    <h3>Current Enquiries</h3>
    <?php if (!$enquiries): ?>
      <p>No enquiries found.</p>
    <?php else: ?>
      <div class="admin-list">
        <?php foreach ($enquiries as $row): ?>
          <div class="admin-list-item enquiry-item status-<?= htmlspecialchars($row['status']) ?>">
            <div>
              <strong><?= htmlspecialchars($row['name']) ?></strong>
              <span><?= htmlspecialchars($row['created_at']) ?> • <?= htmlspecialchars($statuses[$row['status']] ?? $row['status']) ?> • <?= htmlspecialchars($row['service'] ?? '') ?></span>
              <span><?= htmlspecialchars($row['email'] ?? '') ?><?= $row['phone'] ? ' • ' . htmlspecialchars($row['phone']) : '' ?></span>
              <p><?= htmlspecialchars(mb_strimwidth($row['message'], 0, 160, '...')) ?></p>
            </div>
            <div class="admin-actions">
              <a class="edit" href="enquiries.php?view=<?= (int)$row['id'] ?>">View/Edit</a>
              <form method="post" onsubmit="return confirm('Delete this enquiry?');">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                <button class="delete" type="submit">Delete</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>
</body>
</html>

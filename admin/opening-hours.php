<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/../includes/content-store.php';

$days = [
    'monday' => 'Monday',
    'tuesday' => 'Tuesday',
    'wednesday' => 'Wednesday',
    'thursday' => 'Thursday',
    'friday' => 'Friday',
    'saturday' => 'Saturday',
    'sunday' => 'Sunday'
];

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    try {
        $data = ['notice' => trim($_POST['notice'] ?? '')];

        foreach ($days as $key => $label) {
            $data[$key] = [
                'status' => trim($_POST[$key . '_status'] ?? 'Closed'),
                'open' => trim($_POST[$key . '_open'] ?? ''),
                'close' => trim($_POST[$key . '_close'] ?? '')
            ];
        }

        save_opening_hours_data($data);
        admin_audit('opening_hours_updated', 'Opening hours updated');
        $message = 'Opening hours updated.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$hours = get_opening_hours_data();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
<title>Opening Hours | Site Admin</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include __DIR__ . '/includes/admin-header.php'; ?>

<main class="admin-wrap">
  <section class="admin-panel">
    <h2>Opening Hours</h2>
    <p class="admin-note">Update the opening-hours banner and bank holiday notice shown across the website.</p>

    <?php if ($message): ?><div class="form-message success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="form-message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post" class="admin-form">
      <?= csrf_field() ?>

      <?php foreach ($days as $key => $label): 
        $row = $hours[$key] ?? ['status' => 'Closed', 'open' => '', 'close' => ''];
      ?>
        <div class="hours-row">
          <strong><?= htmlspecialchars($label) ?></strong>

          <label>Status
            <select name="<?= htmlspecialchars($key) ?>_status">
              <?php foreach (['Open','Closed','By appointment'] as $status): ?>
                <option value="<?= htmlspecialchars($status) ?>" <?php if (($row['status'] ?? '') === $status) echo 'selected'; ?>><?= htmlspecialchars($status) ?></option>
              <?php endforeach; ?>
            </select>
          </label>

          <label>Open
            <input type="time" name="<?= htmlspecialchars($key) ?>_open" value="<?= htmlspecialchars($row['open'] ?? '') ?>">
          </label>

          <label>Close
            <input type="time" name="<?= htmlspecialchars($key) ?>_close" value="<?= htmlspecialchars($row['close'] ?? '') ?>">
          </label>
        </div>
      <?php endforeach; ?>

      <label>Notice
        <input type="text" name="notice" value="<?= htmlspecialchars($hours['notice'] ?? 'Closed bank holidays') ?>">
      </label>

      <button type="submit">Save Opening Hours</button>
    </form>
  </section>
</main>
</body>
</html>

<?php
require_once __DIR__ . '/auth.php';
require_login();

$file = __DIR__ . '/../data/trustpilot-settings.json';

$settings = [
    'enabled' => true,
    'business_unit_id' => '',
    'profile_url' => 'https://uk.trustpilot.com/review/onesourceairandenergyltd.co.uk',
    'heading' => 'Rated by our customers',
    'intro' => 'See what customers say about One Source Air & Energy Ltd.'
];

if (file_exists($file)) {
    $loaded = json_decode(file_get_contents($file), true);
    if (is_array($loaded)) {
        $settings = array_merge($settings, $loaded);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = [
        'enabled' => isset($_POST['enabled']),
        'business_unit_id' => trim($_POST['business_unit_id'] ?? ''),
        'profile_url' => trim($_POST['profile_url'] ?? ''),
        'heading' => trim($_POST['heading'] ?? ''),
        'intro' => trim($_POST['intro'] ?? '')
    ];

    file_put_contents($file, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    header('Location: trustpilot-settings.php?saved=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="robots" content="noindex,nofollow">
  <title>Trustpilot Settings | One Source Admin</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include __DIR__ . '/includes/admin-header.php'; ?>

<main class="admin-wrap">
  <section class="admin-panel">
    <h2>Trustpilot Settings</h2>
    <p class="admin-note">Add the Trustpilot Business Unit ID to activate the TrustBox widget. Leave disabled to hide the section.</p>

    <?php if (isset($_GET['saved'])): ?>
      <div class="form-message success">Trustpilot settings updated.</div>
    <?php endif; ?>

    <form method="post" class="admin-form">
      <label class="social-enable">
        <input type="checkbox" name="enabled" <?php if (!empty($settings['enabled'])) echo 'checked'; ?>>
        <strong>Show Trustpilot section on homepage</strong>
      </label>

      <label>Trustpilot Business Unit ID
        <input type="text" name="business_unit_id" placeholder="Example: 5fxxxxxxxxxxxxxxxxxxxxxx" value="<?= htmlspecialchars($settings['business_unit_id']) ?>">
      </label>

      <label>Trustpilot Profile URL
        <input type="url" name="profile_url" placeholder="https://uk.trustpilot.com/review/..." value="<?= htmlspecialchars($settings['profile_url']) ?>">
      </label>

      <label>Section Heading
        <input type="text" name="heading" value="<?= htmlspecialchars($settings['heading']) ?>">
      </label>

      <label>Intro Text
        <textarea name="intro" rows="3"><?= htmlspecialchars($settings['intro']) ?></textarea>
      </label>

      <button type="submit">Save Trustpilot Settings</button>
    </form>
  </section>
</main>
</body>
</html>

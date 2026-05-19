<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/content-store.php';
require_login();

$links = get_social_links_data();

foreach ($platforms as $key => $meta) {
    if (!isset($links[$key])) {
        $links[$key] = ['url' => '', 'enabled' => false];
    } elseif (!is_array($links[$key])) {
        $links[$key] = ['url' => (string)$links[$key], 'enabled' => trim((string)$links[$key]) !== ''];
    } else {
        $links[$key]['url'] = $links[$key]['url'] ?? '';
        $links[$key]['enabled'] = !empty($links[$key]['enabled']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($platforms as $key => $meta) {
        $url = trim($_POST[$key . '_url'] ?? '');
        $enabled = isset($_POST[$key . '_enabled']) && $url !== '';

        $links[$key] = [
            'url' => $url,
            'enabled' => $enabled
        ];
    }

    save_social_links_data($links, $platforms);
    header('Location: social-links.php?saved=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="robots" content="noindex,nofollow">
  <title>Social Media Links | One Source Admin</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include __DIR__ . '/includes/admin-header.php'; ?>

<main class="admin-wrap">
  <section class="admin-panel">
    <h2>Social Media Links</h2>
    <p class="admin-note">Only enabled platforms with a URL will show in the website footer.</p>

    <?php if (isset($_GET['saved'])): ?>
      <div class="form-message success">Social media links updated.</div>
    <?php endif; ?>

    <form method="post" class="admin-form social-form">
      <?php foreach ($platforms as $key => $meta): ?>
        <div class="social-admin-row">
          <label class="social-enable">
            <input type="checkbox" name="<?= htmlspecialchars($key) ?>_enabled" <?php if (!empty($links[$key]['enabled'])) echo 'checked'; ?>>
            <strong><?= htmlspecialchars($meta['label']) ?></strong>
          </label>

          <input type="url"
                 name="<?= htmlspecialchars($key) ?>_url"
                 placeholder="<?= htmlspecialchars($meta['placeholder']) ?>"
                 value="<?= htmlspecialchars($links[$key]['url'] ?? '') ?>">
        </div>
      <?php endforeach; ?>

      <button type="submit">Save Social Links</button>
    </form>
  </section>
</main>
</body>
</html>

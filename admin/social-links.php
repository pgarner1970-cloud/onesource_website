<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/../includes/content-store.php';

$platforms = [
    'facebook' => ['label' => 'Facebook'],
    'instagram' => ['label' => 'Instagram'],
    'whatsapp' => ['label' => 'WhatsApp'],
    'linkedin' => ['label' => 'LinkedIn'],
    'youtube' => ['label' => 'YouTube'],
    'tiktok' => ['label' => 'TikTok'],
    'x' => ['label' => 'X / Twitter'],
    'google_business' => ['label' => 'Google Business'],
    'trustpilot' => ['label' => 'Trustpilot'],
    'checkatrade' => ['label' => 'Checkatrade'],
    'mybuilder' => ['label' => 'MyBuilder'],
    'rated_people' => ['label' => 'Rated People']
];

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    try {
        $links = [];

        foreach ($platforms as $key => $meta) {
            $links[$key] = [
                'url' => trim($_POST[$key . '_url'] ?? ''),
                'enabled' => isset($_POST[$key . '_enabled'])
            ];
        }

        save_social_links_data($links, $platforms);
        admin_audit('social_links_updated', 'Social media links updated');
        $message = 'Social media links updated.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$links = get_social_links_data();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
<title>Social Media | Site Admin</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include __DIR__ . '/includes/admin-header.php'; ?>

<main class="admin-wrap">
  <section class="admin-panel">
    <h2>Social Media</h2>
    <p class="admin-note">Enable only the social icons that should appear in the footer.</p>

    <?php if ($message): ?><div class="form-message success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="form-message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post" class="admin-form">
      <?= csrf_field() ?>

      <?php foreach ($platforms as $key => $meta): 
        $item = $links[$key] ?? ['url' => '', 'enabled' => false];
      ?>
        <div class="social-row">
          <label class="social-enable">
            <input type="checkbox" name="<?= htmlspecialchars($key) ?>_enabled" <?php if (!empty($item['enabled'])) echo 'checked'; ?>>
            <strong><?= htmlspecialchars($meta['label']) ?></strong>
          </label>

          <input type="url" name="<?= htmlspecialchars($key) ?>_url" placeholder="https://..." value="<?= htmlspecialchars($item['url'] ?? '') ?>">
        </div>
      <?php endforeach; ?>

      <button type="submit">Save Social Links</button>
    </form>
  </section>
</main>
</body>
</html>

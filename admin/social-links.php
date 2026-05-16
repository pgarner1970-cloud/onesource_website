<?php
require_once __DIR__ . '/auth.php';
require_login();

$file = __DIR__ . '/../data/social-links.json';

$links = [
    'facebook' => '',
    'instagram' => '',
    'whatsapp' => ''
];

if (file_exists($file)) {
    $loaded = json_decode(file_get_contents($file), true);
    if (is_array($loaded)) {
        $links = array_merge($links, $loaded);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $links = [
        'facebook' => trim($_POST['facebook'] ?? ''),
        'instagram' => trim($_POST['instagram'] ?? ''),
        'whatsapp' => trim($_POST['whatsapp'] ?? '')
    ];

    file_put_contents($file, json_encode($links, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

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
    <p class="admin-note">Only icons with a saved link will appear in the website footer. Leave a field blank to hide that icon.</p>

    <?php if (isset($_GET['saved'])): ?>
      <div class="form-message success">Social media links updated.</div>
    <?php endif; ?>

    <form method="post" class="admin-form">
      <label>Facebook URL
        <input type="url" name="facebook" placeholder="https://www.facebook.com/..." value="<?= htmlspecialchars($links['facebook']) ?>">
      </label>

      <label>Instagram URL
        <input type="url" name="instagram" placeholder="https://www.instagram.com/..." value="<?= htmlspecialchars($links['instagram']) ?>">
      </label>

      <label>WhatsApp Link
        <input type="url" name="whatsapp" placeholder="https://wa.me/447502216131" value="<?= htmlspecialchars($links['whatsapp']) ?>">
      </label>

      <button type="submit">Save Social Links</button>
    </form>
  </section>
</main>
</body>
</html>

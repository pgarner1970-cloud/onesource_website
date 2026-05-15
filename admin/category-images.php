<?php
require_once __DIR__ . '/auth.php';
require_login();

$CATEGORY_IMAGES_JSON = __DIR__ . '/../data/category-images.json';
$SERVICE_IMAGE_UPLOAD_DIR = __DIR__ . '/../uploads/service-images/';

$categories = [
    'air-conditioning' => 'Air Conditioning',
    'solar-pv' => 'Solar PV',
    'battery-storage' => 'Battery Storage',
    'ev-chargers' => 'EV Chargers',
    'electrical' => 'Electrical Services',
    'gas-services' => 'Gas Services'
];

$images = [];
if (file_exists($CATEGORY_IMAGES_JSON)) {
    $images = json_decode(file_get_contents($CATEGORY_IMAGES_JSON), true);
    if (!is_array($images)) {
        $images = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="robots" content="noindex,nofollow">
  <title>Service Images | One Source Admin</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <header class="admin-header">
    <h1>Gallery Admin</h1>
    <nav class="admin-top-links">
      <a href="index.php">Gallery</a>
      <a href="opening-hours.php">Opening Hours</a>
      <a href="category-images.php">Service Images</a>
      <a href="../index.php">Back to Website</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

  <main class="admin-wrap">
    <section class="admin-panel">
      <h2>Default images for service pages</h2>
      <p class="admin-note">These images are used on the Services page and each individual service page. Upload a clean image for each category.</p>

      <div class="service-image-grid">
        <?php foreach ($categories as $key => $label): ?>
          <article class="service-image-card">
            <img src="../<?= htmlspecialchars($images[$key] ?? '') ?>" alt="">
            <h3><?= htmlspecialchars($label) ?></h3>
            <form action="category-image-save.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="category" value="<?= htmlspecialchars($key) ?>">
              <label>Upload new default image
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required>
              </label>
              <button type="submit">Update Image</button>
            </form>

            <form action="category-image-default.php" method="post" class="default-image-form">
              <input type="hidden" name="category" value="<?= htmlspecialchars($key) ?>">
              <button type="submit" class="default-image-button">Use Default Image</button>
            </form>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
</body>
</html>

<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/../includes/content-store.php';

$services = [
    'air-conditioning' => 'Air Conditioning',
    'solar-pv' => 'Solar PV',
    'battery-storage' => 'Battery Storage',
    'ev-chargers' => 'EV Chargers',
    'electrical-services' => 'Electrical Services',
    'gas-services' => 'Gas Services',
    'oil-installations' => 'Oil Installations'
];

$message = '';
$error = '';

$uploadDir = __DIR__ . '/../uploads/service-images/';
$uploadUrl = 'uploads/service-images/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

function service_image_filename($serviceKey, $ext) {
    return preg_replace('/[^a-z0-9-]/', '', strtolower($serviceKey)) . '-' . date('Ymd-His') . '.' . strtolower($ext);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    try {
        $action = $_POST['action'] ?? '';
        $serviceKey = trim($_POST['service_key'] ?? '');

        if (!isset($services[$serviceKey])) {
            throw new RuntimeException('Invalid service selected.');
        }

        if ($action === 'upload_image') {
            if (empty($_FILES['image']['tmp_name'])) {
                throw new RuntimeException('Please choose an image.');
            }

            $tmp = $_FILES['image']['tmp_name'];
            $info = getimagesize($tmp);

            if (!$info) {
                throw new RuntimeException('Uploaded file is not a valid image.');
            }

            $allowed = [
                IMAGETYPE_JPEG => 'jpg',
                IMAGETYPE_PNG => 'png',
                IMAGETYPE_WEBP => 'webp'
            ];

            if (!isset($allowed[$info[2]])) {
                throw new RuntimeException('Only JPG, PNG and WebP images are allowed.');
            }

            $filename = service_image_filename($serviceKey, $allowed[$info[2]]);
            $destination = $uploadDir . $filename;

            if (!move_uploaded_file($tmp, $destination)) {
                throw new RuntimeException('Could not save uploaded image.');
            }

            save_service_image($serviceKey, $uploadUrl . $filename);
            admin_audit('service_image_updated', 'Updated service image: ' . $serviceKey);
            $message = 'Service image updated.';
        }

        if ($action === 'use_default') {
            reset_service_image($serviceKey);
            admin_audit('service_image_reset', 'Reset service image: ' . $serviceKey);
            $message = 'Service image reset to default.';
        }
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$currentImages = get_service_images_data();
$defaultImages = get_service_image_defaults_data();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
<title>Service Images | Site Admin</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include __DIR__ . '/includes/admin-header.php'; ?>

<main class="admin-wrap">
  <section class="admin-panel">
    <h2>Default Images for Service Pages</h2>
    <p class="admin-note">These images are used on the Services page and each individual service page.</p>

    <?php if ($message): ?><div class="form-message success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="form-message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="service-default-grid">
      <?php foreach ($services as $key => $label): 
        $image = $currentImages[$key] ?? ($defaultImages[$key] ?? '');
      ?>
        <article class="service-default-card">
          <div class="service-default-preview">
            <?php if ($image): ?>
              <img src="../<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($label) ?>">
            <?php else: ?>
              <div class="empty-preview">No image set</div>
            <?php endif; ?>
          </div>

          <div class="service-default-body">
            <h3><?= htmlspecialchars($label) ?></h3>

            <form method="post" enctype="multipart/form-data" class="service-image-form">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="upload_image">
              <input type="hidden" name="service_key" value="<?= htmlspecialchars($key) ?>">

              <label>Upload new default image</label>
              <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required>
              <button type="submit" class="service-admin-button">Update Image</button>
            </form>

            <form method="post" class="service-image-form">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="use_default">
              <input type="hidden" name="service_key" value="<?= htmlspecialchars($key) ?>">
              <button type="submit" class="service-admin-button service-admin-button-dark">Use Default Image</button>
            </form>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
</main>
</body>
</html>

<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/../includes/content-store.php';
require_once __DIR__ . '/../includes/articles.php';

$categories = [
    'air-conditioning' => 'Air Conditioning',
    'solar-pv' => 'Solar PV',
    'battery-storage' => 'Battery Storage',
    'ev-chargers' => 'EV Chargers',
    'electrical' => 'Electrical',
    'gas-services' => 'Gas Services',
    'oil-installations' => 'Oil Installations',
    'other' => 'Other'
];

function category_label($categories, $key) {
    return $categories[$key] ?? $key;
}

$message = '';
$error = '';

$uploadDir = __DIR__ . '/../uploads/projects/';
$uploadUrl = 'uploads/projects/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$projects = get_projects_data();
$editProject = null;
if (!empty($_GET['edit'])) {
    foreach ($projects as $project) {
        if ((string)($project['id'] ?? '') === (string)$_GET['edit']) {
            $editProject = $project;
            break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    try {
        $action = $_POST['action'] ?? '';

        if ($action === 'upload_project') {
            $title = trim($_POST['title'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $alt = trim($_POST['alt_text'] ?? '');
            $show = isset($_POST['show_in_gallery']);

            if ($title === '') {
                throw new RuntimeException('Project title is required.');
            }

            if (!isset($categories[$category])) {
                throw new RuntimeException('Invalid category.');
            }

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

            $filename = seo_filename($title, $location, $allowed[$info[2]]);
            $destination = $uploadDir . $filename;

            if (!move_uploaded_file($tmp, $destination)) {
                throw new RuntimeException('Could not save uploaded image.');
            }

            $imagePath = $uploadUrl . $filename;

            save_project_data([
                'title' => $title,
                'slug' => article_slug($title . ' ' . $location),
                'category' => $category,
                'location' => $location,
                'description' => $description,
                'alt' => $alt ?: $title . ($location ? ' in ' . $location : ''),
                'image' => $imagePath,
                'featured' => $show
            ]);

            admin_audit('project_uploaded', 'Uploaded project: ' . $title);
            header('Location: gallery.php');
            exit;
        }

        if ($action === 'edit_project') {
            $title = trim($_POST['title'] ?? '');
            if ($title === '') {
                throw new RuntimeException('Project title is required.');
            }

            $project = [
                'id' => $_POST['id'] ?? '',
                'title' => $title,
                'slug' => article_slug($title . ' ' . ($_POST['location'] ?? '')),
                'category' => trim($_POST['category'] ?? ''),
                'location' => trim($_POST['location'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'alt' => trim($_POST['alt_text'] ?? ''),
                'image' => trim($_POST['image'] ?? ''),
                'featured' => isset($_POST['show_in_gallery'])
            ];

            save_project_data($project);
            admin_audit('project_updated', 'Updated project ID: ' . $project['id']);
            header('Location: gallery.php');
            exit;
        }
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
<title><?= $editProject ? 'Edit Project Image' : 'Add Project Image' ?> | Site Admin</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include __DIR__ . '/includes/admin-header.php'; ?>

<main class="admin-wrap">
  <section class="admin-panel">
    <div class="admin-heading-actions">
      <div>
        <h2><?= $editProject ? 'Edit Project Image' : 'Add Project Image' ?></h2>
        <p class="admin-note">Add or edit gallery images shown on the public Our Work page.</p>
      </div>
      <a class="admin-secondary-action" href="gallery.php">Back to Gallery</a>
    </div>

    <?php if ($message): ?><div class="form-message success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="form-message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="admin-form gallery-edit-form">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="<?= $editProject ? 'edit_project' : 'upload_project' ?>">
      <?php if ($editProject): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($editProject['id']) ?>">
        <input type="hidden" name="image" value="<?= htmlspecialchars($editProject['image']) ?>">
      <?php endif; ?>

      <label>Title
        <input type="text" name="title" placeholder="Example: Solar PV installation" required value="<?= htmlspecialchars($editProject['title'] ?? '') ?>">
      </label>

      <label>Category
        <select name="category">
          <?php foreach ($categories as $key => $label): ?>
            <option value="<?= htmlspecialchars($key) ?>" <?php if (($editProject['category'] ?? '') === $key) echo 'selected'; ?>><?= htmlspecialchars($label) ?></option>
          <?php endforeach; ?>
        </select>
      </label>

      <label>Location
        <input type="text" name="location" placeholder="Example: Kidderminster" value="<?= htmlspecialchars($editProject['location'] ?? '') ?>">
      </label>

      <label>Description
        <textarea name="description" rows="5" placeholder="Short description/location/project note"><?= htmlspecialchars($editProject['description'] ?? '') ?></textarea>
      </label>

      <label>SEO Alt Text
        <input type="text" name="alt_text" placeholder="Example: Domestic air conditioning installation in Kidderminster" value="<?= htmlspecialchars($editProject['alt'] ?? '') ?>">
      </label>

      <?php if ($editProject): ?>
        <label>Current image
          <img src="../<?= htmlspecialchars($editProject['image']) ?>" alt="" class="gallery-current-image">
        </label>
      <?php else: ?>
        <label>Image
          <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required>
        </label>
      <?php endif; ?>

      <label class="social-enable">
        <input type="checkbox" name="show_in_gallery" <?php if (!$editProject || !empty($editProject['featured'])) echo 'checked'; ?>>
        <strong>Show in gallery</strong>
      </label>

      <button type="submit"><?= $editProject ? 'Save Changes' : 'Upload Project' ?></button>
    </form>
  </section>
</main>
</body>
</html>

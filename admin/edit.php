<?php
require_once __DIR__ . '/auth.php';
require_login();

$id = $_GET['id'] ?? '';

$categories = [
    'air-conditioning' => 'Air Conditioning',
    'solar-pv' => 'Solar PV',
    'battery-storage' => 'Battery Storage',
    'ev-chargers' => 'EV Chargers',
    'electrical' => 'Electrical',
    'gas-services' => 'Gas Services',
    'other' => 'Other'
];

$projects = [];
if (file_exists($PROJECTS_JSON)) {
    $projects = json_decode(file_get_contents($PROJECTS_JSON), true);
    if (!is_array($projects)) {
        $projects = [];
    }
}

$project = null;
foreach ($projects as $item) {
    if (($item['id'] ?? '') === $id) {
        $project = $item;
        break;
    }
}

if (!$project) {
    die('Gallery item not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="robots" content="noindex,nofollow">
  <title>Edit Gallery Item | One Source</title>
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
      <form action="update.php" method="post" class="admin-form">
        <input type="hidden" name="id" value="<?= htmlspecialchars($project['id'] ?? '') ?>">

        <img class="edit-preview" src="../<?= htmlspecialchars($project['image'] ?? '') ?>" alt="">

        <label>Title
          <input type="text" name="title" required value="<?= htmlspecialchars($project['title'] ?? '') ?>">
        </label>

        <label>Category
          <select name="category" required>
            <?php foreach ($categories as $key => $label): ?>
              <option value="<?= htmlspecialchars($key) ?>" <?= (($project['category'] ?? '') === $key) ? 'selected' : '' ?>>
                <?= htmlspecialchars($label) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </label>

        <label>Description
          <textarea name="description"><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
        </label>

        <label class="check">
          <input type="checkbox" name="featured" value="1" <?= (isset($project['featured']) ? (bool)$project['featured'] : true) ? 'checked' : '' ?>>
          Show in gallery
        </label>

        <button type="submit">Save Changes</button>
      </form>
    </section>
  </main>
</body>
</html>

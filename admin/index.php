<?php
require_once __DIR__ . '/auth.php';
require_login();

$projects = [];
if (file_exists($PROJECTS_JSON)) {
    $projects = json_decode(file_get_contents($PROJECTS_JSON), true);
    if (!is_array($projects)) {
        $projects = [];
    }
}

$categories = [
    'air-conditioning' => 'Air Conditioning',
    'solar-pv' => 'Solar PV',
    'battery-storage' => 'Battery Storage',
    'ev-chargers' => 'EV Chargers',
    'electrical' => 'Electrical',
    'gas-services' => 'Gas Services',
    'other' => 'Other'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="robots" content="noindex,nofollow">
  <meta charset="UTF-8">
  <title>Gallery Admin | One Source</title>
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
      <h2>Add Project Image</h2>
      <form action="save.php" method="post" enctype="multipart/form-data" class="admin-form">
        <label>Title<input type="text" name="title" required placeholder="Example: Solar PV installation"></label>
        <label>Category
          <select name="category" required>
            <?php foreach ($categories as $key => $label): ?>
              <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label>Description<textarea name="description" placeholder="Short description/location/project note"></textarea></label>
        <label>Image<input type="file" name="image" accept="image/jpeg,image/png,image/webp" required></label>
        <label class="check"><input type="checkbox" name="featured" value="1" checked> Show in gallery</label>
        <button type="submit">Upload Project</button>
      </form>
    </section>

    <section class="admin-panel">
      <h2>Current Gallery Items</h2>
      <div class="admin-filter-row">
        <button type="button" class="admin-filter active" data-filter="all">All</button>
        <?php foreach ($categories as $key => $label): ?>
          <button type="button" class="admin-filter" data-filter="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></button>
        <?php endforeach; ?>
      </div>

      <div class="project-list admin-project-grid">
        <?php foreach ($projects as $project): ?>
          <article class="project-row" data-category="<?= htmlspecialchars($project['category'] ?? 'other') ?>">
            <img loading="lazy" src="../<?= htmlspecialchars($project['image'] ?? '') ?>" alt="">
            <div>
              <strong><?= htmlspecialchars($project['title'] ?? '') ?></strong>
              <span><?= htmlspecialchars($categories[$project['category'] ?? 'other'] ?? 'Other') ?> • <?= (isset($project['featured']) ? (bool)$project['featured'] : true) ? 'Visible' : 'Hidden' ?></span>
              <p><?= htmlspecialchars($project['description'] ?? '') ?></p>
            </div>
            <div class="admin-actions">
              <a class="edit" href="edit.php?id=<?= urlencode($project['id'] ?? '') ?>">Edit</a>
              <form action="toggle.php" method="post">
                <input type="hidden" name="id" value="<?= htmlspecialchars($project['id'] ?? '') ?>">
                <?php $isVisible = isset($project['featured']) ? (bool)$project['featured'] : true; ?>
                <button class="<?= $isVisible ? 'hide' : 'show' ?>" type="submit">
                  <?= $isVisible ? 'Hide' : 'Show' ?>
                </button>
              </form>
              <form action="delete.php" method="post" onsubmit="return confirm('Permanently delete this gallery item?');">
                <input type="hidden" name="id" value="<?= htmlspecialchars($project['id'] ?? '') ?>">
                <button class="delete" type="submit">Delete</button>
              </form>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

<script>
document.querySelectorAll('.admin-filter').forEach((button) => {
  button.addEventListener('click', () => {
    document.querySelectorAll('.admin-filter').forEach((item) => item.classList.remove('active'));
    button.classList.add('active');

    const filter = button.dataset.filter;

    document.querySelectorAll('.project-row').forEach((row) => {
      const show = filter === 'all' || row.dataset.category === filter;
      row.style.display = show ? '' : 'none';
    });
  });
});
</script>

</body>
</html>
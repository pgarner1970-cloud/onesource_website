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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    try {
        $action = $_POST['action'] ?? '';

        if ($action === 'toggle_project') {
            $id = (int)($_POST['id'] ?? 0);
            toggle_project_gallery_status($id);
            admin_audit('project_toggle', 'Toggled project ID: ' . $id);
            $message = 'Project visibility updated.';
        }

        if ($action === 'delete_project') {
            $id = (int)($_POST['id'] ?? 0);
            delete_project_data($id);
            admin_audit('project_deleted', 'Deleted project ID: ' . $id);
            $message = 'Project deleted.';
        }
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$projects = get_projects_data();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
<title>Gallery | Site Admin</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include __DIR__ . '/includes/admin-header.php'; ?>

<main class="admin-wrap">
  <section class="admin-panel gallery-admin-heading">
    <div class="admin-heading-actions">
      <div>
        <h2>Gallery / Our Work</h2>
        <p class="admin-note">Manage project images shown on the public Our Work page.</p>
      </div>
      <a class="admin-primary-action" href="gallery-edit.php">Add Project Image</a>
    </div>

    <?php if ($message): ?><div class="form-message success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="form-message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  </section>

  <section class="admin-panel">
    <h2>Current Gallery Items</h2>

    <div class="filter-row">
      <button class="filter active" type="button" data-filter="all">All</button>
      <?php foreach ($categories as $key => $label): ?>
        <button class="filter" type="button" data-filter="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></button>
      <?php endforeach; ?>
    </div>

    <?php if (!$projects): ?>
      <p>No gallery projects found.</p>
    <?php else: ?>
      <div class="admin-gallery-grid">
        <?php foreach ($projects as $project): ?>
          <article class="admin-gallery-card" data-category="<?= htmlspecialchars($project['category'] ?? '') ?>">
            <img src="../<?= htmlspecialchars($project['image'] ?? '') ?>" alt="<?= htmlspecialchars($project['alt'] ?? $project['title'] ?? '') ?>">
            <h3><?= htmlspecialchars($project['title'] ?? '') ?></h3>
            <p><strong><?= htmlspecialchars(category_label($categories, $project['category'] ?? '')) ?></strong> • <?= !empty($project['featured']) ? 'Visible' : 'Hidden' ?></p>
            <?php if (!empty($project['location'])): ?><p><?= htmlspecialchars($project['location']) ?></p><?php endif; ?>
            <p><?= htmlspecialchars($project['description'] ?? '') ?></p>

            <div class="admin-card-actions uniform-actions">
              <a class="edit" href="gallery-edit.php?edit=<?= urlencode($project['id']) ?>">Edit</a>
              <form method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="toggle_project">
                <input type="hidden" name="id" value="<?= htmlspecialchars($project['id']) ?>">
                <button class="<?= !empty($project['featured']) ? 'hide' : 'show' ?>" type="submit"><?= !empty($project['featured']) ? 'Hide' : 'Show' ?></button>
              </form>
              <form method="post" onsubmit="return confirm('Delete this project?');">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete_project">
                <input type="hidden" name="id" value="<?= htmlspecialchars($project['id']) ?>">
                <button class="delete" type="submit">Delete</button>
              </form>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>

<script>
document.querySelectorAll('.filter').forEach(function(btn) {
  btn.addEventListener('click', function() {
    document.querySelectorAll('.filter').forEach(function(b) { b.classList.remove('active'); });
    btn.classList.add('active');
    const filter = btn.dataset.filter;
    document.querySelectorAll('.admin-gallery-card').forEach(function(card) {
      card.style.display = (filter === 'all' || card.dataset.category === filter) ? '' : 'none';
    });
  });
});
</script>
</body>
</html>

<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/../includes/articles.php';

$draftFile = __DIR__ . '/../data/article-drafts.json';
$articleFile = __DIR__ . '/../data/articles.json';

$drafts = read_json_array($draftFile, []);
$articles = read_json_array($articleFile, []);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_draft') {
        $id = $_POST['id'] ?: uniqid('draft_', true);
        $title = trim($_POST['title'] ?? '');
        $slug = article_slug($_POST['slug'] ?: $title);
        $draft = [
            'id' => $id,
            'title' => $title,
            'slug' => $slug,
            'excerpt' => trim($_POST['excerpt'] ?? ''),
            'body' => trim($_POST['body'] ?? ''),
            'meta_title' => trim($_POST['meta_title'] ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
            'related_service' => trim($_POST['related_service'] ?? ''),
            'created_at' => $_POST['created_at'] ?: date('c'),
            'updated_at' => date('c')
        ];

        $found = false;
        foreach ($drafts as &$item) {
            if (($item['id'] ?? '') === $id) {
                $item = $draft;
                $found = true;
                break;
            }
        }
        unset($item);
        if (!$found) {
            $drafts[] = $draft;
        }

        write_json_array($draftFile, $drafts);
        header('Location: advice-admin.php?saved=1');
        exit;
    }

    if ($action === 'approve') {
        $id = $_POST['id'] ?? '';
        foreach ($drafts as $index => $draft) {
            if (($draft['id'] ?? '') === $id) {
                $draft['published'] = true;
                $draft['published_at'] = date('c');
                $draft['updated_at'] = date('c');
                $articles[] = $draft;
                unset($drafts[$index]);
                break;
            }
        }
        write_json_array($draftFile, array_values($drafts));
        write_json_array($articleFile, array_values($articles));
        header('Location: advice-admin.php?approved=1');
        exit;
    }

    if ($action === 'delete_draft') {
        $id = $_POST['id'] ?? '';
        $drafts = array_values(array_filter($drafts, fn($d) => ($d['id'] ?? '') !== $id));
        write_json_array($draftFile, $drafts);
        header('Location: advice-admin.php?deleted=1');
        exit;
    }

    if ($action === 'unpublish') {
        $id = $_POST['id'] ?? '';
        foreach ($articles as $index => $article) {
            if (($article['id'] ?? '') === $id) {
                $article['published'] = false;
                $drafts[] = $article;
                unset($articles[$index]);
                break;
            }
        }
        write_json_array($articleFile, array_values($articles));
        write_json_array($draftFile, array_values($drafts));
        header('Location: advice-admin.php?unpublished=1');
        exit;
    }
}

$edit = null;
if (!empty($_GET['edit'])) {
    foreach ($drafts as $draft) {
        if (($draft['id'] ?? '') === $_GET['edit']) {
            $edit = $draft;
            break;
        }
    }
}

$services = ['Air Conditioning','Solar PV','Battery Storage','EV Chargers','Electrical Services','Gas Services','Oil Installations'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
<title>Advice & Insights Admin</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include __DIR__ . '/includes/admin-header.php'; ?>

<main class="admin-wrap">
  <section class="admin-panel">
    <h2>Advice & Insights</h2>
    <p class="admin-note">AI-generated articles should remain as drafts until reviewed and approved.</p>

    <?php if(isset($_GET['saved'])): ?><div class="form-message success">Draft saved.</div><?php endif; ?>
    <?php if(isset($_GET['approved'])): ?><div class="form-message success">Draft approved and published.</div><?php endif; ?>

    <h3><?= $edit ? 'Edit Draft' : 'Create Draft Manually' ?></h3>
    <form method="post" class="admin-form">
      <input type="hidden" name="action" value="save_draft">
      <input type="hidden" name="id" value="<?= htmlspecialchars($edit['id'] ?? '') ?>">
      <input type="hidden" name="created_at" value="<?= htmlspecialchars($edit['created_at'] ?? '') ?>">

      <label>Title
        <input type="text" name="title" required value="<?= htmlspecialchars($edit['title'] ?? '') ?>">
      </label>

      <label>Slug
        <input type="text" name="slug" value="<?= htmlspecialchars($edit['slug'] ?? '') ?>">
      </label>

      <label>Related Service
        <select name="related_service">
          <?php foreach($services as $service): ?>
            <option value="<?= htmlspecialchars($service) ?>" <?php if(($edit['related_service'] ?? '') === $service) echo 'selected'; ?>><?= htmlspecialchars($service) ?></option>
          <?php endforeach; ?>
        </select>
      </label>

      <label>Excerpt
        <textarea name="excerpt" rows="3"><?= htmlspecialchars($edit['excerpt'] ?? '') ?></textarea>
      </label>

      <label>Article Body HTML
        <textarea name="body" rows="14"><?= htmlspecialchars($edit['body'] ?? '') ?></textarea>
      </label>

      <label>Meta Title
        <input type="text" name="meta_title" value="<?= htmlspecialchars($edit['meta_title'] ?? '') ?>">
      </label>

      <label>Meta Description
        <textarea name="meta_description" rows="2"><?= htmlspecialchars($edit['meta_description'] ?? '') ?></textarea>
      </label>

      <button type="submit">Save Draft</button>
    </form>
  </section>

  <section class="admin-panel">
    <h3>Drafts Awaiting Approval</h3>
    <?php if(!$drafts): ?>
      <p>No drafts waiting for approval.</p>
    <?php else: ?>
      <div class="admin-list">
        <?php foreach($drafts as $draft): ?>
          <div class="admin-list-item">
            <div>
              <strong><?= htmlspecialchars($draft['title'] ?? 'Untitled') ?></strong>
              <span><?= htmlspecialchars($draft['related_service'] ?? '') ?></span>
            </div>
            <div class="admin-actions">
              <a class="edit" href="advice-admin.php?edit=<?= urlencode($draft['id']) ?>">Edit</a>
              <form method="post"><input type="hidden" name="action" value="approve"><input type="hidden" name="id" value="<?= htmlspecialchars($draft['id']) ?>"><button class="show">Approve</button></form>
              <form method="post"><input type="hidden" name="action" value="delete_draft"><input type="hidden" name="id" value="<?= htmlspecialchars($draft['id']) ?>"><button class="delete">Delete</button></form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <section class="admin-panel">
    <h3>Published Articles</h3>
    <?php if(!$articles): ?>
      <p>No published articles yet.</p>
    <?php else: ?>
      <div class="admin-list">
        <?php foreach($articles as $article): ?>
          <div class="admin-list-item">
            <div>
              <strong><?= htmlspecialchars($article['title'] ?? 'Untitled') ?></strong>
              <span><?= htmlspecialchars($article['published_at'] ?? '') ?></span>
            </div>
            <div class="admin-actions">
              <a class="edit" target="_blank" href="../article.php?slug=<?= urlencode($article['slug']) ?>">View</a>
              <form method="post"><input type="hidden" name="action" value="unpublish"><input type="hidden" name="id" value="<?= htmlspecialchars($article['id']) ?>"><button class="hide">Unpublish</button></form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>
</body>
</html>

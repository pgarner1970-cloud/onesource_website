<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/../includes/articles.php';
require_once __DIR__ . '/../includes/content-store.php';

$services = ['Air Conditioning','Solar PV','Battery Storage','EV Chargers','Electrical Services','Gas Services','Oil Installations'];

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'save_settings') {
            save_article_settings_data([
                'enabled' => isset($_POST['enabled']),
                'notify_email' => trim($_POST['notify_email'] ?? ''),
                'drafts_per_week' => (int)($_POST['drafts_per_week'] ?? 1),
                'openai_api_key' => trim($_POST['openai_api_key'] ?? ''),
                'model' => trim($_POST['model'] ?? 'gpt-4.1-mini'),
                'topic_focus' => trim($_POST['topic_focus'] ?? '')
            ]);
            admin_audit('article_settings_updated', 'Advice & Insights settings updated');
            $message = 'Settings saved.';
        }

        if ($action === 'save_draft') {
            $title = trim($_POST['title'] ?? '');
            if ($title === '') {
                throw new RuntimeException('Title is required.');
            }

            $id = trim($_POST['id'] ?? '') ?: uniqid('draft_', true);
            save_article_draft_data([
                'id' => $id,
                'title' => $title,
                'slug' => article_slug($_POST['slug'] ?: $title),
                'excerpt' => trim($_POST['excerpt'] ?? ''),
                'body' => trim($_POST['body'] ?? ''),
                'meta_title' => trim($_POST['meta_title'] ?? ''),
                'meta_description' => trim($_POST['meta_description'] ?? ''),
                'related_service' => trim($_POST['related_service'] ?? ''),
                'source' => 'Manual draft',
                'topic' => trim($_POST['topic'] ?? ''),
                'created_at' => $_POST['created_at'] ?: date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            admin_audit('article_draft_saved', 'Saved draft: ' . $title);
            $message = 'Draft saved.';
        }

        if ($action === 'approve') {
            publish_article_draft_data(trim($_POST['id'] ?? ''));
            admin_audit('article_draft_published', 'Published draft ID: ' . ($_POST['id'] ?? ''));
            $message = 'Draft published.';
        }

        if ($action === 'delete_draft') {
            delete_article_draft_data(trim($_POST['id'] ?? ''));
            admin_audit('article_draft_deleted', 'Deleted draft ID: ' . ($_POST['id'] ?? ''));
            $message = 'Draft deleted.';
        }

        if ($action === 'unpublish') {
            unpublish_article_data(trim($_POST['id'] ?? ''));
            admin_audit('article_unpublished', 'Unpublished article ID: ' . ($_POST['id'] ?? ''));
            $message = 'Article unpublished.';
        }
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$settings = get_article_settings_data();
$drafts = get_article_drafts_data();
$articles = get_articles_data(false);

$edit = null;
if (!empty($_GET['edit'])) {
    foreach ($drafts as $draft) {
        if (($draft['id'] ?? '') === $_GET['edit']) {
            $edit = $draft;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
<title>Advice &amp; Insights | Site Admin</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include __DIR__ . '/includes/admin-header.php'; ?>

<main class="admin-wrap">
  <section class="admin-panel">
    <h2>Advice &amp; Insights</h2>
    <p class="admin-note">Create, review and publish article drafts. AI generation can be enabled later with an OpenAI API key.</p>

    <?php if ($message): ?><div class="form-message success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="form-message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  </section>

  <section class="admin-panel">
    <h3>AI Settings</h3>
    <form method="post" class="admin-form">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save_settings">

      <label class="social-enable">
        <input type="checkbox" name="enabled" <?php if (!empty($settings['enabled'])) echo 'checked'; ?>>
        <strong>Enable AI draft generation</strong>
      </label>

      <label>OpenAI API Key
        <input type="password" name="openai_api_key" value="<?= htmlspecialchars($settings['openai_api_key'] ?? '') ?>" placeholder="sk-proj-...">
      </label>

      <label>Model
        <input type="text" name="model" value="<?= htmlspecialchars($settings['model'] ?? 'gpt-4.1-mini') ?>">
      </label>

      <label>Notification Email
        <input type="email" name="notify_email" value="<?= htmlspecialchars($settings['notify_email'] ?? '') ?>">
      </label>

      <label>Drafts Per Week
        <input type="number" min="1" max="7" name="drafts_per_week" value="<?= htmlspecialchars((string)($settings['drafts_per_week'] ?? 1)) ?>">
      </label>

      <label>Topic Focus
        <textarea name="topic_focus" rows="3"><?= htmlspecialchars($settings['topic_focus'] ?? '') ?></textarea>
      </label>

      <button type="submit">Save Settings</button>
    </form>
  </section>

  <section class="admin-panel">
    <h3><?= $edit ? 'Edit Draft' : 'Create Manual Draft' ?></h3>
    <form method="post" class="admin-form">
      <?= csrf_field() ?>
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
          <?php foreach ($services as $service): ?>
            <option value="<?= htmlspecialchars($service) ?>" <?php if (($edit['related_service'] ?? '') === $service) echo 'selected'; ?>><?= htmlspecialchars($service) ?></option>
          <?php endforeach; ?>
        </select>
      </label>

      <label>Excerpt
        <textarea name="excerpt" rows="3"><?= htmlspecialchars($edit['excerpt'] ?? '') ?></textarea>
      </label>

      <label>Article Body HTML
        <textarea name="body" rows="12"><?= htmlspecialchars($edit['body'] ?? '') ?></textarea>
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
    <?php if (!$drafts): ?>
      <p>No drafts waiting for approval.</p>
    <?php else: ?>
      <div class="admin-list">
        <?php foreach ($drafts as $draft): ?>
          <div class="admin-list-item">
            <div>
              <strong><?= htmlspecialchars($draft['title'] ?? 'Untitled') ?></strong>
              <span><?= htmlspecialchars(($draft['related_service'] ?? '') . ' • ' . ($draft['source'] ?? 'Draft')) ?></span>
            </div>
            <div class="admin-actions">
              <a class="edit" href="advice-admin.php?edit=<?= urlencode($draft['id']) ?>">Edit</a>
              <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="approve"><input type="hidden" name="id" value="<?= htmlspecialchars($draft['id']) ?>"><button class="show" type="submit">Approve</button></form>
              <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="delete_draft"><input type="hidden" name="id" value="<?= htmlspecialchars($draft['id']) ?>"><button class="delete" type="submit">Delete</button></form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <section class="admin-panel">
    <h3>Published Articles</h3>
    <?php if (!$articles): ?>
      <p>No published articles yet.</p>
    <?php else: ?>
      <div class="admin-list">
        <?php foreach ($articles as $article): ?>
          <div class="admin-list-item">
            <div>
              <strong><?= htmlspecialchars($article['title'] ?? 'Untitled') ?></strong>
              <span><?= htmlspecialchars($article['published_at'] ?? '') ?></span>
            </div>
            <div class="admin-actions">
              <a class="edit" target="_blank" href="../article.php?slug=<?= urlencode($article['slug']) ?>">View</a>
              <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="unpublish"><input type="hidden" name="id" value="<?= htmlspecialchars($article['id']) ?>"><button class="hide" type="submit">Unpublish</button></form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>
</body>
</html>

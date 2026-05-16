<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/../includes/articles.php';
require_once __DIR__ . '/generate-article-drafts.php';

$draftFile = __DIR__ . '/../data/article-drafts.json';
$articleFile = __DIR__ . '/../data/articles.json';
$settingsFile = __DIR__ . '/../data/article-settings.json';

$drafts = read_json_array($draftFile, []);
$articles = read_json_array($articleFile, []);
$settings = read_json_array($settingsFile, [
    'enabled' => true,
    'notify_email' => '',
    'drafts_per_week' => 1,
    'openai_api_key' => '',
    'model' => 'gpt-4.1-mini',
    'topic_focus' => '',
    'require_approval' => true
]);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_settings') {
        $settings = [
            'enabled' => isset($_POST['enabled']),
            'notify_email' => trim($_POST['notify_email'] ?? ''),
            'drafts_per_week' => max(1, min(7, (int)($_POST['drafts_per_week'] ?? 1))),
            'openai_api_key' => trim($_POST['openai_api_key'] ?? ''),
            'model' => trim($_POST['model'] ?? 'gpt-4.1-mini'),
            'topic_focus' => trim($_POST['topic_focus'] ?? ''),
            'require_approval' => true
        ];

        file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        header('Location: advice-admin.php?settings=1');
        exit;
    }

    if ($action === 'generate_ai') {
        $topic = trim($_POST['custom_topic'] ?? '');
        if ($topic === '') {
            $topic = trim($_POST['preset_topic'] ?? '');
        }

        $result = generate_ai_article_draft($topic);
        $message = $result['message'];
        $drafts = read_json_array($draftFile, []);
    }

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
            'updated_at' => date('c'),
            'source' => 'Manual draft'
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
$presetTopics = [
    'How often should air conditioning be serviced in the UK?',
    'What homeowners should know before installing an EV charger',
    'Is battery storage worth it with Solar PV?',
    'What does OFTEC registration mean for domestic oil heating?',
    'Commercial electrical maintenance checklist for small businesses',
    'Preparing your heating system before winter',
    'What to consider before replacing an oil boiler',
    'Domestic, commercial and industrial air conditioning explained'
];
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
    <p class="admin-note">Create useful article drafts manually or generate AI-assisted drafts for review. AI drafts are never published automatically.</p>

    <?php if($message): ?><div class="form-message <?= str_starts_with($message, 'Draft generated') ? 'success' : 'error' ?>"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if(isset($_GET['saved'])): ?><div class="form-message success">Draft saved.</div><?php endif; ?>
    <?php if(isset($_GET['approved'])): ?><div class="form-message success">Draft approved and published.</div><?php endif; ?>
    <?php if(isset($_GET['settings'])): ?><div class="form-message success">AI article settings saved.</div><?php endif; ?>
  </section>

  <section class="admin-panel">
    <h3>AI Draft Generator</h3>
    <p class="admin-note">Choose a suggested topic or enter your own. The AI will create a draft only. You must review, edit and approve before it appears on the website.</p>

    <form method="post" class="admin-form ai-generate-form">
      <input type="hidden" name="action" value="generate_ai">

      <label>Suggested Topic
        <select name="preset_topic">
          <option value="">Choose a suggested topic</option>
          <?php foreach($presetTopics as $topic): ?>
            <option value="<?= htmlspecialchars($topic) ?>"><?= htmlspecialchars($topic) ?></option>
          <?php endforeach; ?>
        </select>
      </label>

      <label>Or enter a custom topic
        <input type="text" name="custom_topic" placeholder="Example: Is air conditioning suitable for garden rooms?">
      </label>

      <button type="submit">Generate AI Draft</button>
    </form>

    <div class="ai-explainer">
      <h4>How this facility works</h4>
      <p>The website can generate article drafts using the OpenAI API. Drafts are saved into the admin area for review. Nothing is published until approved.</p>

      <table class="admin-cost-table">
        <thead>
          <tr>
            <th>Usage</th>
            <th>Typical volume</th>
            <th>Estimated cost</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Light</td>
            <td>1 article per week</td>
            <td>Usually under £1–£3/month</td>
          </tr>
          <tr>
            <td>Recommended</td>
            <td>1–2 articles per week</td>
            <td>Approx. £1–£5/month</td>
          </tr>
          <tr>
            <td>Heavy</td>
            <td>Daily articles / lots of regenerations</td>
            <td>Could be £10–£30+/month</td>
          </tr>
        </tbody>
      </table>

      <p class="admin-note">Costs are estimates only and depend on OpenAI pricing, article length and how often drafts are regenerated.</p>
    </div>
  </section>

  <section class="admin-panel">
    <h3>AI Settings</h3>
    <form method="post" class="admin-form">
      <input type="hidden" name="action" value="save_settings">

      <label class="social-enable">
        <input type="checkbox" name="enabled" <?php if(!empty($settings['enabled'])) echo 'checked'; ?>>
        <strong>Enable AI draft generation</strong>
      </label>

      <label>OpenAI API Key
        <input type="password" name="openai_api_key" placeholder="sk-proj-..." value="<?= htmlspecialchars($settings['openai_api_key'] ?? '') ?>">
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

      <button type="submit">Save AI Settings</button>
    </form>
  </section>

  <section class="admin-panel">
    <h3><?= $edit ? 'Edit Draft' : 'Create Manual Draft' ?></h3>
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

      <button type="submit">Save Manual Draft</button>
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
              <span><?= htmlspecialchars(($draft['related_service'] ?? '') . ' • ' . ($draft['source'] ?? 'Draft')) ?></span>
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

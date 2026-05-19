<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/../includes/content-store.php';

$message = '';

function import_json_projects() {
    $projects = json_file_array('data/projects.json', []);
    $count = 0;
    foreach ($projects as $project) {
        save_project_data($project);
        $count++;
    }
    return $count;
}

function import_json_service_images() {
    $current = json_file_array('data/category-images.json', []);
    $defaults = json_file_array('data/category-images-defaults.json', []);
    $count = 0;

    foreach (array_unique(array_merge(array_keys($current), array_keys($defaults))) as $key) {
        $image = $current[$key] ?? ($defaults[$key] ?? '');
        $default = $defaults[$key] ?? $image;
        $stmt = db()->prepare('INSERT INTO service_images (service_key, image_path, default_image_path) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE image_path=VALUES(image_path), default_image_path=VALUES(default_image_path)');
        $stmt->execute([$key, $image, $default]);
        $count++;
    }

    return $count;
}

function import_json_articles() {
    $count = 0;
    foreach (json_file_array('data/article-drafts.json', []) as $draft) {
        save_article_draft_data($draft);
        $count++;
    }
    foreach (json_file_array('data/articles.json', []) as $article) {
        $stmt = db()->prepare('INSERT INTO articles (id, title, slug, excerpt, body, meta_title, meta_description, related_service, source, topic, published, published_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE title=VALUES(title), slug=VALUES(slug), excerpt=VALUES(excerpt), body=VALUES(body), meta_title=VALUES(meta_title), meta_description=VALUES(meta_description), related_service=VALUES(related_service), source=VALUES(source), topic=VALUES(topic), published=VALUES(published), published_at=VALUES(published_at), updated_at=VALUES(updated_at)');
        $id = $article['id'] ?? uniqid('article_', true);
        $stmt->execute([
            $id,
            $article['title'] ?? '',
            $article['slug'] ?? article_slug($article['title'] ?? $id),
            $article['excerpt'] ?? '',
            $article['body'] ?? '',
            $article['meta_title'] ?? '',
            $article['meta_description'] ?? '',
            $article['related_service'] ?? '',
            $article['source'] ?? '',
            $article['topic'] ?? '',
            !empty($article['published']) ? 1 : 1,
            $article['published_at'] ?? date('Y-m-d H:i:s'),
            $article['created_at'] ?? date('Y-m-d H:i:s'),
            $article['updated_at'] ?? date('Y-m-d H:i:s')
        ]);
        $count++;
    }
    return $count;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    try {
        $actions = [];
        $hours = get_opening_hours_data();
        save_opening_hours_data($hours);
        $actions[] = 'Opening hours imported';

        $platforms = [
            'facebook' => ['label' => 'Facebook'],
            'instagram' => ['label' => 'Instagram'],
            'whatsapp' => ['label' => 'WhatsApp'],
            'linkedin' => ['label' => 'LinkedIn'],
            'youtube' => ['label' => 'YouTube'],
            'tiktok' => ['label' => 'TikTok'],
            'x' => ['label' => 'X / Twitter'],
            'google_business' => ['label' => 'Google Business'],
            'trustpilot' => ['label' => 'Trustpilot'],
            'checkatrade' => ['label' => 'Checkatrade'],
            'mybuilder' => ['label' => 'MyBuilder'],
            'rated_people' => ['label' => 'Rated People']
        ];
        save_social_links_data(json_file_array('data/social-links.json', []), $platforms);
        $actions[] = 'Social links imported';

        save_trustpilot_settings_data(get_trustpilot_settings_data());
        $actions[] = 'Trustpilot settings imported';

        save_article_settings_data(get_article_settings_data());
        $actions[] = 'Article AI settings imported';

        $actions[] = import_json_service_images() . ' service image records imported';
        $actions[] = import_json_projects() . ' projects imported';
        $actions[] = import_json_articles() . ' article records imported';

        $message = implode('<br>', array_map('htmlspecialchars', $actions));
    } catch (Throwable $e) {
        $message = 'Migration failed: ' . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
<title>Migrate JSON to MySQL</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include __DIR__ . '/includes/admin-header.php'; ?>
<main class="admin-wrap">
  <section class="admin-panel">
    <h2>Migrate JSON Data to MySQL</h2>
    <p class="admin-note">Run this once after applying the phase 2 SQL. It imports current JSON data into MySQL. Existing MySQL records are updated where possible.</p>
    <?php if ($message): ?><div class="form-message success"><?= $message ?></div><?php endif; ?>
    <form method="post">
      <?= csrf_field() ?>
      <button type="submit">Run Migration</button>
    </form>
  </section>
</main>
</body>
</html>

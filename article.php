<?php include 'opening-hours.php'; ?>
<?php require_once __DIR__ . '/includes/articles.php'; ?>
<?php
$slug = $_GET['slug'] ?? '';
$articles = read_json_array(__DIR__ . '/data/articles.json', []);
$article = null;

foreach ($articles as $item) {
    if (!empty($item['published']) && ($item['slug'] ?? '') === $slug) {
        $article = $item;
        break;
    }
}

if (!$article) {
    http_response_code(404);
}
$title = $article['meta_title'] ?? $article['title'] ?? 'Article not found';
$description = $article['meta_description'] ?? $article['excerpt'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title) ?></title>
<meta name="description" content="<?= htmlspecialchars($description) ?>">
<link rel="stylesheet" href="assets/css/styles.css">
<link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
<?php include __DIR__ . '/includes/header.php'; ?>

<main>
<?php if (!$article): ?>
<section class="page-hero">
  <div class="wrap">
    <h1>Article not found</h1>
    <p>The article you are looking for could not be found.</p>
  </div>
</section>
<?php else: ?>
<section class="page-hero article-hero">
  <div class="wrap">
    <span class="article-tag"><?= htmlspecialchars($article['related_service'] ?? 'Advice') ?></span>
    <h1><?= htmlspecialchars($article['title']) ?></h1>
    <p><?= htmlspecialchars($article['excerpt'] ?? '') ?></p>
  </div>
</section>

<section class="section">
  <div class="wrap article-layout">
    <article class="article-body">
      <?= $article['body'] ?? '' ?>
    </article>

    <aside class="article-sidebar">
      <div class="sidebar-card">
        <h3>Need advice?</h3>
        <p>Speak to One Source Air & Energy Ltd about your property or project.</p>
        <a class="btn btn-primary" href="contact.php">Get a Quote →</a>
      </div>
    </aside>
  </div>
</section>
<?php endif; ?>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="assets/js/site.js"></script>
</body>
</html>

<?php include 'opening-hours.php'; ?>
<?php require_once __DIR__ . '/includes/articles.php'; ?>
<?php
$articles = read_json_array(__DIR__ . '/data/articles.json', []);
$articles = array_values(array_filter($articles, function($a) {
    return !empty($a['published']) && !empty($a['slug']);
}));
usort($articles, function($a, $b) {
    return strcmp($b['published_at'] ?? '', $a['published_at'] ?? '');
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Advice & Insights | One Source Air & Energy Ltd</title>
<meta name="description" content="Helpful advice and insights about air conditioning, electrical services, gas, oil heating, solar PV, battery storage and EV chargers.">
<link rel="stylesheet" href="assets/css/styles.css">
<link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
<?php include __DIR__ . '/includes/header.php'; ?>

<main>
<section class="page-hero">
  <div class="wrap">
    <h1>Advice & Insights</h1>
    <p>Helpful, practical advice about energy, electrical and heating services for homes and businesses.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <?php if (!$articles): ?>
      <div class="empty-state">
        <h2>Articles coming soon</h2>
        <p>We’re preparing helpful advice and guidance. Please check back shortly.</p>
      </div>
    <?php else: ?>
      <div class="article-grid">
        <?php foreach ($articles as $article): ?>
          <article class="article-card">
            <a href="article.php?slug=<?= urlencode($article['slug']) ?>">
              <span class="article-tag"><?= htmlspecialchars($article['related_service'] ?? 'Advice') ?></span>
              <h2><?= htmlspecialchars($article['title']) ?></h2>
              <p><?= htmlspecialchars($article['excerpt'] ?? article_excerpt($article['body'] ?? '')) ?></p>
              <strong>Read article →</strong>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="assets/js/site.js"></script>
</body>
</html>

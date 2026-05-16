<?php
// Cron-ready AI draft generator.
// Run manually/cron only after adding an OpenAI API key in data/article-settings.json.
// This script creates draft articles only. It never publishes.

require_once __DIR__ . '/../includes/articles.php';

$settingsFile = __DIR__ . '/../data/article-settings.json';
$draftFile = __DIR__ . '/../data/article-drafts.json';

$settings = read_json_array($settingsFile, []);
$drafts = read_json_array($draftFile, []);

if (empty($settings['enabled'])) {
    exit("Article generation disabled.\n");
}

if (empty($settings['openai_api_key'])) {
    // Safe fallback: create no draft unless API key is configured.
    exit("OpenAI API key not configured. No draft generated.\n");
}

$topics = [
    'How often should air conditioning be serviced in the UK?',
    'What homeowners should know before installing an EV charger',
    'Is battery storage worth it with Solar PV?',
    'What does OFTEC registration mean for oil heating work?',
    'Commercial electrical maintenance checklist for small businesses',
    'Preparing your heating system before winter'
];

$topic = $topics[array_rand($topics)];

$payload = [
    'model' => 'gpt-4.1-mini',
    'messages' => [
        ['role' => 'system', 'content' => 'You write helpful, UK-focused advice articles for a professional energy, electrical, heating and air conditioning services website. Never invent accreditations, reviews, grants or legal requirements. Keep claims cautious and practical. Output JSON only.'],
        ['role' => 'user', 'content' => 'Create one draft article for this topic: ' . $topic . '. Return JSON with title, slug, excerpt, body_html, meta_title, meta_description, related_service.']
    ],
    'temperature' => 0.6
];

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $settings['openai_api_key']
    ],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_TIMEOUT => 45
]);

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if (!$response) {
    exit("OpenAI request failed: " . $err . "\n");
}

$data = json_decode($response, true);
$content = $data['choices'][0]['message']['content'] ?? '';
$article = json_decode($content, true);

if (!is_array($article) || empty($article['title'])) {
    exit("Could not parse AI response.\n");
}

$drafts[] = [
    'id' => uniqid('draft_', true),
    'title' => $article['title'],
    'slug' => article_slug($article['slug'] ?? $article['title']),
    'excerpt' => $article['excerpt'] ?? article_excerpt($article['body_html'] ?? ''),
    'body' => $article['body_html'] ?? '',
    'meta_title' => $article['meta_title'] ?? $article['title'],
    'meta_description' => $article['meta_description'] ?? '',
    'related_service' => $article['related_service'] ?? 'Advice',
    'created_at' => date('c'),
    'updated_at' => date('c')
];

write_json_array($draftFile, $drafts);

if (!empty($settings['notify_email'])) {
    @mail(
        $settings['notify_email'],
        'New Advice & Insights draft ready for review',
        "A new draft article has been generated and is waiting in the website admin.\n\nTitle: " . $article['title'],
        "From: One Source Website <no-reply@onesourceairandenergyltd.co.uk>"
    );
}

echo "Draft generated: " . $article['title'] . "\n";
?>
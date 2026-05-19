<?php
// AI draft generator. Creates drafts only. Never publishes automatically.

require_once __DIR__ . '/../includes/articles.php';
require_once __DIR__ . '/../includes/content-store.php';

function generate_ai_article_draft($topic = '') {
    $settingsFile = __DIR__ . '/../data/article-settings.json';
    $draftFile = __DIR__ . '/../data/article-drafts.json';

    $settings = get_article_settings_data();
    $drafts = read_json_array($draftFile, []);

    if (empty($settings['enabled'])) {
        return ['success' => false, 'message' => 'Article generation is disabled.'];
    }

    if (empty($settings['openai_api_key'])) {
        return ['success' => false, 'message' => 'OpenAI API key is not configured. Add it in data/article-settings.json or the admin settings before generating AI drafts.'];
    }

    $topics = [
        'How often should air conditioning be serviced in the UK?',
        'What homeowners should know before installing an EV charger',
        'Is battery storage worth it with Solar PV?',
        'What does OFTEC registration mean for oil heating work?',
        'Commercial electrical maintenance checklist for small businesses',
        'Preparing your heating system before winter',
        'What to consider before replacing an oil boiler',
        'Domestic, commercial and industrial air conditioning explained'
    ];

    if ($topic === '') {
        $topic = $topics[array_rand($topics)];
    }

    $model = $settings['model'] ?? 'gpt-4.1-mini';

    $payload = [
        'model' => $model,
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You write helpful, UK-focused advice articles for a professional energy, electrical, heating and air conditioning services website. Never invent accreditations, reviews, grants, guarantees, prices, regulations or legal requirements. Keep claims cautious and practical. Output JSON only.'
            ],
            [
                'role' => 'user',
                'content' => 'Create one draft article for this topic: ' . $topic . '. The business is One Source Air & Energy Ltd. Return JSON with title, slug, excerpt, body_html, meta_title, meta_description, related_service. Body should be useful, practical HTML using h2, h3, p and ul tags. Keep it around 800-1200 words.'
            ]
        ],
        'temperature' => 0.55
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
        CURLOPT_TIMEOUT => 60
    ]);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);

    if (!$response) {
        return ['success' => false, 'message' => 'OpenAI request failed: ' . $err];
    }

    if ($status < 200 || $status >= 300) {
        return ['success' => false, 'message' => 'OpenAI returned HTTP ' . $status . '. Response: ' . substr($response, 0, 500)];
    }

    $data = json_decode($response, true);
    $content = $data['choices'][0]['message']['content'] ?? '';
    $article = json_decode($content, true);

    if (!is_array($article) || empty($article['title'])) {
        return ['success' => false, 'message' => 'Could not parse AI response into an article draft.'];
    }

    $draft = [
        'id' => uniqid('draft_', true),
        'title' => $article['title'],
        'slug' => article_slug($article['slug'] ?? $article['title']),
        'excerpt' => $article['excerpt'] ?? article_excerpt($article['body_html'] ?? ''),
        'body' => $article['body_html'] ?? '',
        'meta_title' => $article['meta_title'] ?? $article['title'],
        'meta_description' => $article['meta_description'] ?? '',
        'related_service' => $article['related_service'] ?? 'Advice',
        'created_at' => date('c'),
        'updated_at' => date('c'),
        'source' => 'AI draft',
        'topic' => $topic
    ];

    $drafts[] = $draft;
    write_json_array($draftFile, $drafts);

    if (!empty($settings['notify_email'])) {
        @mail(
            $settings['notify_email'],
            'New Advice & Insights draft ready for review',
            "A new draft article has been generated and is waiting in the website admin.\n\nTitle: " . $draft['title'] . "\nTopic: " . $topic,
            "From: One Source Website <no-reply@onesourceairandenergyltd.co.uk>"
        );
    }

    return ['success' => true, 'message' => 'Draft generated: ' . $draft['title']];
}

if (php_sapi_name() === 'cli') {
    $topic = $argv[1] ?? '';
    $result = generate_ai_article_draft($topic);
    echo $result['message'] . "\n";
}
?>
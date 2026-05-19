<?php
require_once __DIR__ . '/database.php';

function json_file_array($relativePath, $fallback = []) {
    $file = __DIR__ . '/../' . ltrim($relativePath, '/');
    if (!file_exists($file)) {
        return $fallback;
    }

    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : $fallback;
}

function setting_get($key, $fallback = '') {
    try {
        $stmt = db()->prepare('SELECT setting_value FROM site_settings WHERE setting_key = ? LIMIT 1');
        $stmt->execute([$key]);
        $value = $stmt->fetchColumn();
        return $value === false ? $fallback : $value;
    } catch (Throwable $e) {
        return $fallback;
    }
}

function setting_set($key, $value) {
    $stmt = db()->prepare('INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
    $stmt->execute([$key, $value]);
}

function get_opening_hours_data() {
    $fallback = json_file_array('data/opening-hours.json', [
        'monday' => ['status' => 'Open', 'open' => '08:00', 'close' => '17:00'],
        'tuesday' => ['status' => 'Open', 'open' => '08:00', 'close' => '17:00'],
        'wednesday' => ['status' => 'Open', 'open' => '08:00', 'close' => '17:00'],
        'thursday' => ['status' => 'Open', 'open' => '08:00', 'close' => '17:00'],
        'friday' => ['status' => 'Open', 'open' => '08:00', 'close' => '17:00'],
        'saturday' => ['status' => 'By appointment', 'open' => '', 'close' => ''],
        'sunday' => ['status' => 'Closed', 'open' => '', 'close' => ''],
        'notice' => 'Closed bank holidays'
    ]);

    try {
        $stmt = db()->query('SELECT * FROM opening_hours ORDER BY sort_order ASC');
        $rows = $stmt->fetchAll();

        if (!$rows) {
            return $fallback;
        }

        $data = ['notice' => setting_get('opening_hours_notice', $fallback['notice'] ?? '')];

        foreach ($rows as $row) {
            $data[$row['day_key']] = [
                'status' => $row['status'],
                'open' => $row['open_time'] ?? '',
                'close' => $row['close_time'] ?? ''
            ];
        }

        return $data;
    } catch (Throwable $e) {
        return $fallback;
    }
}

function save_opening_hours_data($data) {
    $order = ['monday'=>1,'tuesday'=>2,'wednesday'=>3,'thursday'=>4,'friday'=>5,'saturday'=>6,'sunday'=>7];

    foreach ($order as $day => $sort) {
        $item = $data[$day] ?? [];
        $status = trim($item['status'] ?? 'Closed');
        $open = trim($item['open'] ?? '');
        $close = trim($item['close'] ?? '');

        $stmt = db()->prepare('INSERT INTO opening_hours (day_key, status, open_time, close_time, sort_order) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE status=VALUES(status), open_time=VALUES(open_time), close_time=VALUES(close_time), sort_order=VALUES(sort_order)');
        $stmt->execute([$day, $status, $open ?: null, $close ?: null, $sort]);
    }

    setting_set('opening_hours_notice', trim($data['notice'] ?? ''));
}

function get_social_links_data() {
    $fallback = json_file_array('data/social-links.json', []);

    try {
        $stmt = db()->query('SELECT * FROM social_links ORDER BY sort_order ASC, platform_key ASC');
        $rows = $stmt->fetchAll();

        if (!$rows) {
            return $fallback;
        }

        $data = [];
        foreach ($rows as $row) {
            $data[$row['platform_key']] = [
                'url' => $row['url'] ?? '',
                'enabled' => (bool)$row['enabled']
            ];
        }

        return $data;
    } catch (Throwable $e) {
        return $fallback;
    }
}

function save_social_links_data($links, $platforms) {
    $sort = 1;

    foreach ($platforms as $key => $meta) {
        $item = $links[$key] ?? [];
        $url = is_array($item) ? trim($item['url'] ?? '') : trim((string)$item);
        $enabled = is_array($item) ? !empty($item['enabled']) : ($url !== '');

        $stmt = db()->prepare('INSERT INTO social_links (platform_key, label, url, enabled, sort_order) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE label=VALUES(label), url=VALUES(url), enabled=VALUES(enabled), sort_order=VALUES(sort_order)');
        $stmt->execute([$key, $meta['label'] ?? ucfirst($key), $url, $enabled ? 1 : 0, $sort]);
        $sort++;
    }
}

function get_trustpilot_settings_data() {
    $fallback = json_file_array('data/trustpilot-settings.json', [
        'enabled' => true,
        'business_unit_id' => '',
        'profile_url' => 'https://uk.trustpilot.com/review/onesourceairandenergyltd.co.uk',
        'heading' => 'Rated by our customers',
        'intro' => 'See what customers say about One Source Air & Energy Ltd.'
    ]);

    return [
        'enabled' => setting_get('trustpilot_enabled', !empty($fallback['enabled']) ? '1' : '0') === '1',
        'business_unit_id' => setting_get('trustpilot_business_unit_id', $fallback['business_unit_id'] ?? ''),
        'profile_url' => setting_get('trustpilot_profile_url', $fallback['profile_url'] ?? ''),
        'heading' => setting_get('trustpilot_heading', $fallback['heading'] ?? ''),
        'intro' => setting_get('trustpilot_intro', $fallback['intro'] ?? '')
    ];
}

function save_trustpilot_settings_data($settings) {
    setting_set('trustpilot_enabled', !empty($settings['enabled']) ? '1' : '0');
    setting_set('trustpilot_business_unit_id', trim($settings['business_unit_id'] ?? ''));
    setting_set('trustpilot_profile_url', trim($settings['profile_url'] ?? ''));
    setting_set('trustpilot_heading', trim($settings['heading'] ?? ''));
    setting_set('trustpilot_intro', trim($settings['intro'] ?? ''));
}

function get_article_settings_data() {
    $fallback = json_file_array('data/article-settings.json', []);

    return [
        'enabled' => setting_get('article_ai_enabled', !empty($fallback['enabled']) ? '1' : '0') === '1',
        'notify_email' => setting_get('article_notify_email', $fallback['notify_email'] ?? ''),
        'drafts_per_week' => (int)setting_get('article_drafts_per_week', $fallback['drafts_per_week'] ?? 1),
        'openai_api_key' => setting_get('article_openai_api_key', $fallback['openai_api_key'] ?? ''),
        'model' => setting_get('article_model', $fallback['model'] ?? 'gpt-4.1-mini'),
        'topic_focus' => setting_get('article_topic_focus', $fallback['topic_focus'] ?? ''),
        'require_approval' => true
    ];
}

function save_article_settings_data($settings) {
    setting_set('article_ai_enabled', !empty($settings['enabled']) ? '1' : '0');
    setting_set('article_notify_email', trim($settings['notify_email'] ?? ''));
    setting_set('article_drafts_per_week', (string)max(1, min(7, (int)($settings['drafts_per_week'] ?? 1))));
    setting_set('article_openai_api_key', trim($settings['openai_api_key'] ?? ''));
    setting_set('article_model', trim($settings['model'] ?? 'gpt-4.1-mini'));
    setting_set('article_topic_focus', trim($settings['topic_focus'] ?? ''));
}

function get_service_images_data() {
    $fallback = json_file_array('data/category-images.json', []);

    try {
        $stmt = db()->query('SELECT service_key, image_path FROM service_images');
        $rows = $stmt->fetchAll();

        if (!$rows) {
            return $fallback;
        }

        $data = [];
        foreach ($rows as $row) {
            $data[$row['service_key']] = $row['image_path'];
        }

        return $data;
    } catch (Throwable $e) {
        return $fallback;
    }
}

function get_service_image_defaults_data() {
    $fallback = json_file_array('data/category-images-defaults.json', []);

    try {
        $stmt = db()->query('SELECT service_key, default_image_path FROM service_images');
        $rows = $stmt->fetchAll();

        if (!$rows) {
            return $fallback;
        }

        $data = [];
        foreach ($rows as $row) {
            $data[$row['service_key']] = $row['default_image_path'];
        }

        return $data;
    } catch (Throwable $e) {
        return $fallback;
    }
}

function save_service_image($serviceKey, $imagePath) {
    $stmt = db()->prepare('INSERT INTO service_images (service_key, image_path) VALUES (?, ?) ON DUPLICATE KEY UPDATE image_path=VALUES(image_path)');
    $stmt->execute([$serviceKey, $imagePath]);
}

function reset_service_image($serviceKey) {
    $stmt = db()->prepare('UPDATE service_images SET image_path = default_image_path WHERE service_key = ?');
    $stmt->execute([$serviceKey]);
}

function get_projects_data() {
    $fallback = json_file_array('data/projects.json', []);

    try {
        $stmt = db()->query('SELECT * FROM projects ORDER BY sort_order ASC, id DESC');
        $rows = $stmt->fetchAll();

        if (!$rows) {
            return $fallback;
        }

        return array_map(function($row) {
            return [
                'id' => (string)$row['id'],
                'title' => $row['title'],
                'slug' => $row['slug'],
                'category' => $row['category'],
                'location' => $row['location'],
                'description' => $row['description'],
                'alt' => $row['alt_text'],
                'image' => $row['image_path'],
                'featured' => (bool)$row['featured']
            ];
        }, $rows);
    } catch (Throwable $e) {
        return $fallback;
    }
}

function save_project_data($project) {
    $id = $project['id'] ?? null;
    $title = trim($project['title'] ?? 'Project');
    $slug = trim($project['slug'] ?? '');
    $category = trim($project['category'] ?? '');
    $location = trim($project['location'] ?? '');
    $description = trim($project['description'] ?? '');
    $alt = trim($project['alt'] ?? ($project['alt_text'] ?? ''));
    $image = trim($project['image'] ?? ($project['image_path'] ?? ''));
    $featured = !isset($project['featured']) || $project['featured'];

    if ($id && ctype_digit((string)$id)) {
        $stmt = db()->prepare('UPDATE projects SET title=?, slug=?, category=?, location=?, description=?, alt_text=?, image_path=?, featured=? WHERE id=?');
        $stmt->execute([$title, $slug, $category, $location, $description, $alt, $image, $featured ? 1 : 0, (int)$id]);
        return (int)$id;
    }

    $stmt = db()->prepare('INSERT INTO projects (title, slug, category, location, description, alt_text, image_path, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$title, $slug, $category, $location, $description, $alt, $image, $featured ? 1 : 0]);
    return (int)db()->lastInsertId();
}

function delete_project_data($id) {
    $stmt = db()->prepare('DELETE FROM projects WHERE id = ?');
    $stmt->execute([(int)$id]);
}

function get_articles_data($publishedOnly = true) {
    $fallback = json_file_array('data/articles.json', []);

    try {
        $sql = 'SELECT * FROM articles';
        if ($publishedOnly) {
            $sql .= ' WHERE published = 1';
        }
        $sql .= ' ORDER BY published_at DESC, created_at DESC';
        $rows = db()->query($sql)->fetchAll();

        if (!$rows) {
            return $fallback;
        }

        return array_map(function($row) {
            return [
                'id' => $row['id'],
                'title' => $row['title'],
                'slug' => $row['slug'],
                'excerpt' => $row['excerpt'],
                'body' => $row['body'],
                'meta_title' => $row['meta_title'],
                'meta_description' => $row['meta_description'],
                'related_service' => $row['related_service'],
                'source' => $row['source'],
                'topic' => $row['topic'],
                'published' => (bool)$row['published'],
                'published_at' => $row['published_at'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at']
            ];
        }, $rows);
    } catch (Throwable $e) {
        return $fallback;
    }
}

function get_article_drafts_data() {
    $fallback = json_file_array('data/article-drafts.json', []);

    try {
        $rows = db()->query('SELECT * FROM article_drafts ORDER BY created_at DESC')->fetchAll();

        if (!$rows) {
            return $fallback;
        }

        return array_map(function($row) {
            return [
                'id' => $row['id'],
                'title' => $row['title'],
                'slug' => $row['slug'],
                'excerpt' => $row['excerpt'],
                'body' => $row['body'],
                'meta_title' => $row['meta_title'],
                'meta_description' => $row['meta_description'],
                'related_service' => $row['related_service'],
                'source' => $row['source'],
                'topic' => $row['topic'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at']
            ];
        }, $rows);
    } catch (Throwable $e) {
        return $fallback;
    }
}

function save_article_draft_data($draft) {
    $id = $draft['id'] ?? uniqid('draft_', true);

    $stmt = db()->prepare('INSERT INTO article_drafts (id, title, slug, excerpt, body, meta_title, meta_description, related_service, source, topic, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE title=VALUES(title), slug=VALUES(slug), excerpt=VALUES(excerpt), body=VALUES(body), meta_title=VALUES(meta_title), meta_description=VALUES(meta_description), related_service=VALUES(related_service), source=VALUES(source), topic=VALUES(topic), updated_at=VALUES(updated_at)');
    $stmt->execute([
        $id,
        $draft['title'] ?? '',
        $draft['slug'] ?? '',
        $draft['excerpt'] ?? '',
        $draft['body'] ?? '',
        $draft['meta_title'] ?? '',
        $draft['meta_description'] ?? '',
        $draft['related_service'] ?? '',
        $draft['source'] ?? '',
        $draft['topic'] ?? '',
        $draft['created_at'] ?? date('Y-m-d H:i:s'),
        $draft['updated_at'] ?? date('Y-m-d H:i:s')
    ]);

    return $id;
}

function delete_article_draft_data($id) {
    $stmt = db()->prepare('DELETE FROM article_drafts WHERE id = ?');
    $stmt->execute([$id]);
}

function publish_article_draft_data($id) {
    $drafts = get_article_drafts_data();
    foreach ($drafts as $draft) {
        if (($draft['id'] ?? '') === $id) {
            $stmt = db()->prepare('INSERT INTO articles (id, title, slug, excerpt, body, meta_title, meta_description, related_service, source, topic, published, published_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), ?, NOW()) ON DUPLICATE KEY UPDATE title=VALUES(title), slug=VALUES(slug), excerpt=VALUES(excerpt), body=VALUES(body), meta_title=VALUES(meta_title), meta_description=VALUES(meta_description), related_service=VALUES(related_service), source=VALUES(source), topic=VALUES(topic), published=1, published_at=VALUES(published_at), updated_at=NOW()');
            $stmt->execute([
                $draft['id'],
                $draft['title'],
                $draft['slug'],
                $draft['excerpt'],
                $draft['body'],
                $draft['meta_title'],
                $draft['meta_description'],
                $draft['related_service'],
                $draft['source'],
                $draft['topic'],
                $draft['created_at'] ?? date('Y-m-d H:i:s')
            ]);
            delete_article_draft_data($id);
            return true;
        }
    }
    return false;
}

function unpublish_article_data($id) {
    $articles = get_articles_data(false);
    foreach ($articles as $article) {
        if (($article['id'] ?? '') === $id) {
            $article['source'] = $article['source'] ?: 'Unpublished article';
            save_article_draft_data($article);
            $stmt = db()->prepare('DELETE FROM articles WHERE id = ?');
            $stmt->execute([$id]);
            return true;
        }
    }
    return false;
}

function get_admin_projects_data() {
    return get_projects_data();
}

function toggle_project_gallery_status($id) {
    try {
        $stmt = db()->prepare('UPDATE projects SET featured = IF(featured=1,0,1) WHERE id = ?');
        $stmt->execute([(int)$id]);
    } catch (Throwable $e) {}
}

function seo_filename($title, $location = '', $ext = 'jpg') {
    $base = strtolower(trim($title . ' ' . $location));
    $base = preg_replace('/[^a-z0-9]+/', '-', $base);
    $base = trim($base, '-');
    if ($base === '') {
        $base = 'project-image';
    }
    return $base . '-' . date('Ymd-His') . '.' . strtolower($ext);
}

?>
<?php
function read_json_array($file, $fallback = []) {
    if (!file_exists($file)) {
        return $fallback;
    }
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : $fallback;
}

function write_json_array($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
}

function article_slug($title) {
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug ?: 'article-' . time();
}

function article_excerpt($body, $length = 180) {
    $text = trim(strip_tags($body));
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}
?>
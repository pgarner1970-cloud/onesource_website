<?php
require_once __DIR__ . '/database.php';

function secure_session_start() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();

    $config = site_config();
    $timeout = (int)($config['session_timeout_seconds'] ?? 3600);

    if (!empty($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', $params['secure'], $params['httponly']);
        }
        session_destroy();
        session_start();
    }

    $_SESSION['last_activity'] = time();
}

function csrf_token() {
    secure_session_start();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function verify_csrf() {
    secure_session_start();

    $sent = $_POST['csrf_token'] ?? '';
    $valid = !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $sent);

    if (!$valid) {
        http_response_code(403);
        exit('Security check failed. Please go back and try again.');
    }
}

function clean_text($value) {
    return trim((string)$value);
}

function safe_html($html) {
    $html = (string)$html;

    // Remove dangerous complete tag blocks.
    $html = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html);
    $html = preg_replace('#<iframe\b[^>]*>.*?</iframe>#is', '', $html);
    $html = preg_replace('#<object\b[^>]*>.*?</object>#is', '', $html);
    $html = preg_replace('#<embed\b[^>]*>.*?</embed>#is', '', $html);
    $html = preg_replace('#<style\b[^>]*>.*?</style>#is', '', $html);

    // Remove dangerous standalone tags.
    $html = preg_replace('#</?(script|iframe|object|embed|style|link|meta|form|input|button)[^>]*>#is', '', $html);

    // Remove inline JavaScript event handlers.
    $html = preg_replace('/\son[a-z]+\s*=\s*"[^"]*"/i', '', $html);
    $html = preg_replace("/\son[a-z]+\s*=\s*'[^']*'/i", '', $html);

    // Remove javascript: links.
    $html = preg_replace('/href\s*=\s*"javascript:[^"]*"/i', 'href="#"', $html);
    $html = preg_replace("/href\s*=\s*'javascript:[^']*'/i", "href='#'", $html);

    // Allow only simple article formatting tags.
    return strip_tags($html, '<p><br><h2><h3><h4><ul><ol><li><strong><b><em><i><a><blockquote>');
}
?>
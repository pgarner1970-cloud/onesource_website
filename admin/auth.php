<?php
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/security.php';

secure_session_start();

function admin_user_by_username($username) {
    $stmt = db()->prepare('SELECT * FROM admin_users WHERE username = ? AND is_active = 1 LIMIT 1');
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function admin_user_by_id($id) {
    $stmt = db()->prepare('SELECT * FROM admin_users WHERE id = ? AND is_active = 1 LIMIT 1');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function admin_login($username, $password) {
    $username = trim($username);

    try {
        $user = admin_user_by_username($username);
    } catch (Throwable $e) {
        return false;
    }

    if (!$user || empty($user['password_hash'])) {
        return false;
    }

    if (!password_verify($password, $user['password_hash'])) {
        return false;
    }

    session_regenerate_id(true);

    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user_id'] = (int)$user['id'];
    $_SESSION['admin_username'] = $user['username'];
    $_SESSION['last_activity'] = time();

    $stmt = db()->prepare('UPDATE admin_users SET last_login_at = NOW() WHERE id = ?');
    $stmt->execute([(int)$user['id']]);

    return true;
}

function admin_logout() {
    secure_session_start();
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', $params['secure'], $params['httponly']);
    }

    session_destroy();
}

function is_logged_in() {
    if (empty($_SESSION['admin_logged_in']) || empty($_SESSION['admin_user_id'])) {
        return false;
    }

    try {
        return (bool)admin_user_by_id((int)$_SESSION['admin_user_id']);
    } catch (Throwable $e) {
        return false;
    }
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
?>
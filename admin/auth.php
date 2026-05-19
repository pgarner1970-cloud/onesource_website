<?php
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/security.php';

secure_session_start();

function admin_client_ip() {
    return $_SERVER['REMOTE_ADDR'] ?? '';
}

function admin_user_agent() {
    return substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 1000);
}

function admin_audit($action, $details = '', $userId = null, $username = null) {
    try {
        $userId = $userId ?? ($_SESSION['admin_user_id'] ?? null);
        $username = $username ?? ($_SESSION['admin_username'] ?? null);
        $stmt = db()->prepare('INSERT INTO admin_audit_log (admin_user_id, username, action, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$userId ?: null, $username ?: null, $action, $details, admin_client_ip(), admin_user_agent()]);
    } catch (Throwable $e) {}
}

function record_login_attempt($username, $success) {
    try {
        $stmt = db()->prepare('INSERT INTO admin_login_attempts (username, ip_address, success) VALUES (?, ?, ?)');
        $stmt->execute([$username, admin_client_ip(), $success ? 1 : 0]);
    } catch (Throwable $e) {}
}

function login_is_rate_limited($username) {
    try {
        $stmt = db()->prepare("SELECT COUNT(*) FROM admin_login_attempts WHERE success = 0 AND attempted_at > (NOW() - INTERVAL 15 MINUTE) AND (username = ? OR ip_address = ?)");
        $stmt->execute([$username, admin_client_ip()]);
        return ((int)$stmt->fetchColumn()) >= 5;
    } catch (Throwable $e) {
        return false;
    }
}

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

    if (login_is_rate_limited($username)) {
        record_login_attempt($username, false);
        admin_audit('login_rate_limited', 'Rate limit triggered for username: ' . $username, null, $username);
        return false;
    }

    try {
        $user = admin_user_by_username($username);
    } catch (Throwable $e) {
        record_login_attempt($username, false);
        return false;
    }

    if (!$user || empty($user['password_hash'])) {
        record_login_attempt($username, false);
        admin_audit('login_failed', 'Unknown username or missing hash: ' . $username, null, $username);
        return false;
    }

    if (!empty($user['locked_until']) && strtotime($user['locked_until']) > time()) {
        record_login_attempt($username, false);
        admin_audit('login_locked', 'Locked account attempted login', (int)$user['id'], $username);
        return false;
    }

    if (!password_verify($password, $user['password_hash'])) {
        record_login_attempt($username, false);
        $failed = ((int)($user['failed_login_count'] ?? 0)) + 1;
        $lockedUntil = null;
        if ($failed >= 5) {
            $lockedUntil = date('Y-m-d H:i:s', time() + 15 * 60);
            $failed = 0;
        }
        $stmt = db()->prepare('UPDATE admin_users SET failed_login_count = ?, locked_until = ? WHERE id = ?');
        $stmt->execute([$failed, $lockedUntil, (int)$user['id']]);
        admin_audit('login_failed', 'Invalid password', (int)$user['id'], $username);
        return false;
    }

    record_login_attempt($username, true);
    session_regenerate_id(true);
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user_id'] = (int)$user['id'];
    $_SESSION['admin_username'] = $user['username'];
    $_SESSION['admin_role'] = $user['role'] ?? 'admin';
    $_SESSION['last_activity'] = time();

    $stmt = db()->prepare('UPDATE admin_users SET last_login_at = NOW(), failed_login_count = 0, locked_until = NULL WHERE id = ?');
    $stmt->execute([(int)$user['id']]);
    admin_audit('login_success', 'Admin logged in', (int)$user['id'], $user['username']);
    return true;
}

function admin_logout() {
    secure_session_start();
    admin_audit('logout', 'Admin logged out');
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

function require_super_admin() {
    require_login();
    if (($_SESSION['admin_role'] ?? '') !== 'super_admin') {
        http_response_code(403);
        exit('Access denied.');
    }
}
?>
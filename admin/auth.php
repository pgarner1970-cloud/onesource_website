<?php
header('X-Robots-Tag: noindex, nofollow', true);
session_start();
require_once __DIR__ . '/config.php';

function is_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
?>
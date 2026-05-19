<?php
require_once __DIR__ . '/includes/content-store.php';
header('Content-Type: application/json; charset=utf-8');
echo json_encode(get_projects_data(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
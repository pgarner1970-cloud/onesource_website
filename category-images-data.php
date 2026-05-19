<?php
require_once __DIR__ . '/includes/content-store.php';
header('Content-Type: application/json; charset=utf-8');
echo json_encode(array_merge(get_service_image_defaults_data(), get_service_images_data()), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
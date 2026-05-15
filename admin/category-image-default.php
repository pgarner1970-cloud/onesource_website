<?php
require_once __DIR__ . '/auth.php';
require_login();

$CATEGORY_IMAGES_JSON = __DIR__ . '/../data/category-images.json';
$DEFAULT_IMAGES_JSON = __DIR__ . '/../data/category-images-defaults.json';

$category = $_POST['category'] ?? '';

$defaults = [];
if (file_exists($DEFAULT_IMAGES_JSON)) {
    $defaults = json_decode(file_get_contents($DEFAULT_IMAGES_JSON), true);
    if (!is_array($defaults)) {
        $defaults = [];
    }
}

if (!$category || !isset($defaults[$category])) {
    die('Invalid category.');
}

$images = [];
if (file_exists($CATEGORY_IMAGES_JSON)) {
    $images = json_decode(file_get_contents($CATEGORY_IMAGES_JSON), true);
    if (!is_array($images)) {
        $images = [];
    }
}

$images[$category] = $defaults[$category];

file_put_contents($CATEGORY_IMAGES_JSON, json_encode($images, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

header('Location: category-images.php');
exit;
?>
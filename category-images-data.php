<?php
header('Content-Type: application/json; charset=utf-8');

$defaultsFile = __DIR__ . '/data/category-images-defaults.json';
$currentFile = __DIR__ . '/data/category-images.json';

$defaults = [];
$current = [];

if (file_exists($defaultsFile)) {
    $decoded = json_decode(file_get_contents($defaultsFile), true);
    if (is_array($decoded)) {
        $defaults = $decoded;
    }
}

if (file_exists($currentFile)) {
    $decoded = json_decode(file_get_contents($currentFile), true);
    if (is_array($decoded)) {
        $current = $decoded;
    }
}

echo json_encode(array_merge($defaults, $current), JSON_UNESCAPED_SLASHES);

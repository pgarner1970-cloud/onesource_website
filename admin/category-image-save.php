<?php
require_once __DIR__ . '/auth.php';
require_login();

$CATEGORY_IMAGES_JSON = __DIR__ . '/../data/category-images.json';
$SERVICE_IMAGE_UPLOAD_DIR = __DIR__ . '/../uploads/service-images/';

$validCategories = [
    'air-conditioning',
    'solar-pv',
    'battery-storage',
    'ev-chargers',
    'electrical',
    'gas-services'
];

$category = $_POST['category'] ?? '';

if (!in_array($category, $validCategories, true)) {
    die('Invalid category.');
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    die('Upload failed.');
}

$allowed = [
    'image/jpeg' => 'jpg',
    'image/pjpeg' => 'jpg',
    'image/jpg' => 'jpg',
    'image/png' => 'png',
    'image/x-png' => 'png',
    'image/webp' => 'webp'
];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES['image']['tmp_name']);
finfo_close($finfo);

$extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

if (!isset($allowed[$mime])) {
    if (in_array($extension, ['jpg', 'jpeg'])) {
        $mime = 'image/jpeg';
    } elseif ($extension === 'png') {
        $mime = 'image/png';
    } elseif ($extension === 'webp') {
        $mime = 'image/webp';
    }
}

if (!isset($allowed[$mime])) {
    die('Only JPG, PNG and WEBP images are allowed.');
}

if (!is_dir($SERVICE_IMAGE_UPLOAD_DIR)) {
    mkdir($SERVICE_IMAGE_UPLOAD_DIR, 0755, true);
}

$filename = $category . '-' . time() . '.' . $allowed[$mime];
$target = $SERVICE_IMAGE_UPLOAD_DIR . $filename;

if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
    die('Could not save image.');
}

$images = [];
if (file_exists($CATEGORY_IMAGES_JSON)) {
    $images = json_decode(file_get_contents($CATEGORY_IMAGES_JSON), true);
    if (!is_array($images)) {
        $images = [];
    }
}

$images[$category] = 'uploads/service-images/' . $filename;

file_put_contents($CATEGORY_IMAGES_JSON, json_encode($images, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

header('Location: category-images.php');
exit;
?>

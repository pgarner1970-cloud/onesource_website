<?php
require_once __DIR__ . '/auth.php';
require_login();

function slugify($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

function create_image_from_file($path, $mime) {
    if ($mime === 'image/jpeg') return imagecreatefromjpeg($path);
    if ($mime === 'image/png') return imagecreatefrompng($path);
    if ($mime === 'image/webp' && function_exists('imagecreatefromwebp')) return imagecreatefromwebp($path);
    return false;
}

function apply_watermark($sourcePath, $destPath, $mime, $watermarkPath) {
    if (!extension_loaded('gd')) {
        return copy($sourcePath, $destPath);
    }

    $image = create_image_from_file($sourcePath, $mime);
    if (!$image) {
        return copy($sourcePath, $destPath);
    }

    $width = imagesx($image);
    $height = imagesy($image);

    // Resize very large uploads to keep gallery fast
    $maxWidth = 1800;
    if ($width > $maxWidth) {
        $newWidth = $maxWidth;
        $newHeight = (int)(($height / $width) * $newWidth);
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);
        $image = $resized;
        $width = $newWidth;
        $height = $newHeight;
    }

    if (file_exists($watermarkPath)) {
        $watermark = imagecreatefrompng($watermarkPath);

        if ($watermark) {
            $wmWidth = imagesx($watermark);
            $wmHeight = imagesy($watermark);

            // Logo width about 18% of image width
            $targetW = max(180, (int)($width * 0.18));
            $targetH = (int)(($wmHeight / $wmWidth) * $targetW);

            $wmResized = imagecreatetruecolor($targetW, $targetH);
            imagesavealpha($wmResized, true);
            imagealphablending($wmResized, false);
            $transparent = imagecolorallocatealpha($wmResized, 255, 255, 255, 127);
            imagefilledrectangle($wmResized, 0, 0, $targetW, $targetH, $transparent);
            imagecopyresampled($wmResized, $watermark, 0, 0, 0, 0, $targetW, $targetH, $wmWidth, $wmHeight);

            // Convert logo to semi-transparent white
            imagefilter($wmResized, IMG_FILTER_GRAYSCALE);
            imagefilter($wmResized, IMG_FILTER_BRIGHTNESS, 255);

            $opacity = 55; // 0 invisible, 100 solid
            $padding = max(22, (int)($width * 0.018));
            $x = $width - $targetW - $padding;
            $y = $height - $targetH - $padding;

            imagecopymerge($image, $wmResized, $x, $y, 0, 0, $targetW, $targetH, $opacity);

            imagedestroy($wmResized);
            imagedestroy($watermark);
        }
    }

    $saved = false;
    if ($mime === 'image/png') {
        $saved = imagepng($image, $destPath, 6);
    } elseif ($mime === 'image/webp' && function_exists('imagewebp')) {
        $saved = imagewebp($image, $destPath, 86);
    } else {
        $saved = imagejpeg($image, $destPath, 86);
    }

    imagedestroy($image);
    return $saved;
}

$title = trim($_POST['title'] ?? '');
$category = trim($_POST['category'] ?? 'other');
$description = trim($_POST['description'] ?? '');
$featured = isset($_POST['featured']);

if (!$title || !isset($_FILES['image'])) {
    die('Missing required fields.');
}

$file = $_FILES['image'];
if ($file['error'] !== UPLOAD_ERR_OK) {
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
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);


// Fallback extension detection for some hosting/browser combinations
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

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

if (!is_dir($UPLOAD_DIR)) {
    mkdir($UPLOAD_DIR, 0755, true);
}

$id = slugify($title) . '-' . time();
$filename = $id . '.' . $allowed[$mime];
$target = $UPLOAD_DIR . $filename;

if (!apply_watermark($file['tmp_name'], $target, $mime, $WATERMARK_LOGO)) {
    die('Could not save uploaded file.');
}

$projects = [];
if (file_exists($PROJECTS_JSON)) {
    $projects = json_decode(file_get_contents($PROJECTS_JSON), true);
    if (!is_array($projects)) {
        $projects = [];
    }
}

$projects[] = [
    'id' => $id,
    'title' => $title,
    'category' => $category,
    'description' => $description,
    'image' => 'uploads/projects/' . $filename,
    'featured' => $featured
];

file_put_contents($PROJECTS_JSON, json_encode($projects, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

header('Location: index.php');
exit;
?>
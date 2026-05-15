<?php
require_once __DIR__ . '/auth.php';
require_login();

$id = $_POST['id'] ?? '';
$title = trim($_POST['title'] ?? '');
$category = trim($_POST['category'] ?? 'other');
$description = trim($_POST['description'] ?? '');
$featured = isset($_POST['featured']);

if (!$id || !$title) {
    die('Missing required fields.');
}

$projects = [];
if (file_exists($PROJECTS_JSON)) {
    $projects = json_decode(file_get_contents($PROJECTS_JSON), true);
    if (!is_array($projects)) {
        $projects = [];
    }
}

$updated = false;

foreach ($projects as &$project) {
    if (($project['id'] ?? '') === $id) {
        $project['title'] = $title;
        $project['category'] = $category;
        $project['description'] = $description;
        $project['featured'] = $featured;
        $updated = true;
        break;
    }
}

if (!$updated) {
    die('Gallery item not found.');
}

file_put_contents($PROJECTS_JSON, json_encode($projects, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

header('Location: index.php');
exit;
?>
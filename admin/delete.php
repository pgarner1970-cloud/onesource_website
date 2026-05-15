<?php
require_once __DIR__ . '/auth.php';
require_login();

$id = $_POST['id'] ?? '';

$projects = [];
if (file_exists($PROJECTS_JSON)) {
    $projects = json_decode(file_get_contents($PROJECTS_JSON), true);
    if (!is_array($projects)) {
        $projects = [];
    }
}

$new = [];
foreach ($projects as $project) {
    if (($project['id'] ?? '') === $id) {
        $image = $project['image'] ?? '';
        if (strpos($image, 'uploads/projects/') === 0) {
            $file = __DIR__ . '/../' . $image;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        continue;
    }
    $new[] = $project;
}

file_put_contents($PROJECTS_JSON, json_encode($new, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

header('Location: index.php');
exit;
?>
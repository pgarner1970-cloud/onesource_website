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

foreach ($projects as &$project) {
    if (($project['id'] ?? '') === $id) {
        $project['featured'] = !(isset($project['featured']) ? (bool)$project['featured'] : true);
        break;
    }
}

file_put_contents($PROJECTS_JSON, json_encode($projects, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

header('Location: index.php');
exit;
?>
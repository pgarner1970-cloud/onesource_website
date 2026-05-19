<?php
require_once __DIR__ . '/includes/content-store.php';

$hoursData = get_opening_hours_data();

$dayKey = strtolower(date('l'));
$today = $hoursData[$dayKey] ?? ['status' => 'Closed', 'open' => '', 'close' => ''];

$statusText = $today['status'] ?? 'Closed';

if (strtolower($statusText) === 'open' && !empty($today['open']) && !empty($today['close'])) {
    $statusText = 'Open today ' . $today['open'] . '–' . $today['close'];
}
?>
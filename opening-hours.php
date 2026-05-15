<?php
$hoursData = json_decode(file_get_contents(__DIR__ . '/admin/data/opening-hours.json'), true);
$today = strtolower(date('l'));
$todayData = $hoursData[$today];

if ($todayData['type'] === 'open') {
    $statusText = "Today: Open " . $todayData['open'] . " - " . $todayData['close'];
} elseif ($todayData['type'] === 'appointment') {
    $statusText = "Today: By appointment";
} else {
    $statusText = "Today: Closed";
}
?>
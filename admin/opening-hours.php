<?php
$file = __DIR__ . '/data/opening-hours.json';
$data = json_decode(file_get_contents($file), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];

    foreach ($days as $day) {
        $data[$day]['type'] = $_POST[$day . '_type'] ?? 'closed';
        $data[$day]['open'] = $_POST[$day . '_open'] ?? '';
        $data[$day]['close'] = $_POST[$day . '_close'] ?? '';
    }

    $data['notice'] = $_POST['notice'] ?? '';

    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    header('Location: opening-hours.php?saved=1');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Opening Hours</title>
<link rel="stylesheet" href="../assets/css/styles.css">
<style>
.hours-admin{max-width:900px;margin:40px auto;padding:30px;background:#fff;border-radius:16px}
.hours-grid{display:grid;grid-template-columns:160px 180px 1fr 1fr;gap:12px;align-items:center}
.hours-grid input,.hours-grid select{padding:10px}
.save-btn{margin-top:25px;background:#2F80ED;color:#fff;border:none;padding:14px 24px;border-radius:10px;font-weight:700}
.notice{margin-top:20px}
</style>
</head>
<body>

<div class="hours-admin">
<h1>Opening Hours</h1>

<?php if(isset($_GET['saved'])): ?>
<p style="color:green;font-weight:bold;">Opening hours updated.</p>
<?php endif; ?>

<form method="post">
<div class="hours-grid">
<?php
$labels = [
'monday'=>'Monday','tuesday'=>'Tuesday','wednesday'=>'Wednesday',
'thursday'=>'Thursday','friday'=>'Friday','saturday'=>'Saturday','sunday'=>'Sunday'
];

foreach($labels as $key=>$label):
?>
<div><strong><?php echo $label; ?></strong></div>

<select name="<?php echo $key; ?>_type">
<option value="open" <?php if($data[$key]['type']=='open') echo 'selected'; ?>>Open</option>
<option value="appointment" <?php if($data[$key]['type']=='appointment') echo 'selected'; ?>>By appointment</option>
<option value="closed" <?php if($data[$key]['type']=='closed') echo 'selected'; ?>>Closed</option>
</select>

<input type="time" name="<?php echo $key; ?>_open" value="<?php echo $data[$key]['open']; ?>">
<input type="time" name="<?php echo $key; ?>_close" value="<?php echo $data[$key]['close']; ?>">

<?php endforeach; ?>
</div>

<div class="notice">
<label><strong>Top notice</strong></label><br>
<input type="text" name="notice" value="<?php echo htmlspecialchars($data['notice']); ?>" style="width:100%;padding:12px;">
</div>

<button class="save-btn">Save Opening Hours</button>
</form>
</div>
</body>
</html>

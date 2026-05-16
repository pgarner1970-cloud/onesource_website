<?php
$socialFile = __DIR__ . '/../data/social-links.json';
$socialLinks = [];

if (file_exists($socialFile)) {
    $socialLinks = json_decode(file_get_contents($socialFile), true);
    if (!is_array($socialLinks)) {
        $socialLinks = [];
    }
}

$socialIcons = [
    'facebook' => ['label' => 'Facebook', 'icon' => 'assets/icons/facebook.svg'],
    'instagram' => ['label' => 'Instagram', 'icon' => 'assets/icons/instagram.svg'],
    'whatsapp' => ['label' => 'WhatsApp', 'icon' => 'assets/icons/whatsapp.svg']
];
?>
<footer>
  <div class="wrap footer-inner">
    <div class="footer-legal">
      <strong>ONE SOURCE AIR & ENERGY LTD</strong>
      <span>Registered in England & Wales • Company No. 10162474</span>
      <span>Cleobury Mortimer, Kidderminster DY14 8DP</span>
      <span class="footer-links"><a href="privacy.php">Privacy & Cookie Policy</a><span aria-hidden="true">•</span><a href="admin/">Admin Login</a></span>
    </div>

    <div class="footer-social">
      <?php foreach ($socialIcons as $key => $item): ?>
        <?php if (!empty($socialLinks[$key])): ?>
          <a href="<?= htmlspecialchars($socialLinks[$key]) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= htmlspecialchars($item['label']) ?>">
            <img src="<?= htmlspecialchars($item['icon']) ?>" alt="<?= htmlspecialchars($item['label']) ?>">
          </a>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</footer>

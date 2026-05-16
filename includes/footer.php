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
    'whatsapp' => ['label' => 'WhatsApp', 'icon' => 'assets/icons/whatsapp.svg'],
    'linkedin' => ['label' => 'LinkedIn', 'icon' => 'assets/icons/linkedin.svg'],
    'youtube' => ['label' => 'YouTube', 'icon' => 'assets/icons/youtube.svg'],
    'tiktok' => ['label' => 'TikTok', 'icon' => 'assets/icons/tiktok.svg'],
    'x' => ['label' => 'X / Twitter', 'icon' => 'assets/icons/x.svg'],
    'google_business' => ['label' => 'Google Business', 'icon' => 'assets/icons/google-business.svg'],
    'trustpilot' => ['label' => 'Trustpilot', 'icon' => 'assets/icons/trustpilot.svg'],
    'checkatrade' => ['label' => 'Checkatrade', 'icon' => 'assets/icons/checkatrade.svg'],
    'mybuilder' => ['label' => 'MyBuilder', 'icon' => 'assets/icons/mybuilder.svg'],
    'rated_people' => ['label' => 'Rated People', 'icon' => 'assets/icons/rated-people.svg']
];

function social_url($socialLinks, $key) {
    if (!isset($socialLinks[$key])) {
        return '';
    }

    if (is_array($socialLinks[$key])) {
        if (empty($socialLinks[$key]['enabled'])) {
            return '';
        }
        return trim($socialLinks[$key]['url'] ?? '');
    }

    return trim((string)$socialLinks[$key]);
}
?>
<footer>
  <div class="wrap footer-inner">
    <div class="footer-legal">
      <strong>ONE SOURCE AIR & ENERGY LTD</strong>
      <span>Registered in England & Wales • Company No. 10162474</span>
      <span>Cleobury Mortimer, Kidderminster DY14 8DP</span>
      <span class="footer-links"><a href="privacy.php">Privacy & Cookie Policy</a><span aria-hidden="true">•</span><a href="admin/">Admin Login</a></span>\n      <span class="footer-credit">Website provided by Yellow Arrow <a class="yellow-arrow-link" href="https://www.yellowarrow.co.uk/" target="_blank" rel="noopener noreferrer" aria-label="Yellow Arrow website"><img src="assets/icons/yellow-arrow.svg" alt=""></a></span>
    </div>

    <div class="footer-social">
      <?php foreach ($socialIcons as $key => $item): ?>
        <?php $url = social_url($socialLinks, $key); ?>
        <?php if ($url !== ''): ?>
          <a href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= htmlspecialchars($item['label']) ?>" title="<?= htmlspecialchars($item['label']) ?>">
            <img src="<?= htmlspecialchars($item['icon']) ?>" alt="<?= htmlspecialchars($item['label']) ?>">
          </a>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</footer>

<?php include 'opening-hours.php'; ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Electrical Services | One Source Air & Energy Ltd</title><meta name="description" content="Domestic, commercial and industrial electrical installation, upgrades and maintenance."><link rel="stylesheet" href="assets/css/styles.css">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
<link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">

</head><body><div class="topbar">
  <div class="wrap topbar-inner">
    <div class="top-contact">
      <a href="tel:07502216131">☎ 07502 216131</a>
      <a href="mailto:luke@onesourceairandenergyltd.co.uk">✉ luke@onesourceairandenergyltd.co.uk</a>
    </div>
    <div class="top-meta"><?php echo $statusText; ?> • <?php echo htmlspecialchars($hoursData["notice"]); ?></div>
  </div>
</div>

<header class="site-header">
  <div class="wrap nav">
    <a href="index.php" class="logo" aria-label="One Source Air and Energy Ltd">
      <img src="assets/logos/onesource-logo.png" alt="One Source Air & Energy Ltd logo">
    </a>

    <button class="menu-toggle" type="button" aria-label="Open menu" aria-expanded="false">☰</button>

    <nav class="main-nav" aria-label="Main navigation">
      <a href="index.php">Home</a>
      <span class="nav-dropdown">
        <a href="services.php">Services</a>
        <span class="dropdown-menu">
          <a href="air-conditioning.php">Air Conditioning</a>
          <a href="solar-pv.php">Solar PV</a>
          <a href="battery-storage.php">Battery Storage</a>
          <a href="ev-chargers.php">EV Chargers</a>
          <a href="electrical-services.php">Electrical Services</a>
          <a href="gas-services.php">Gas Services</a>
          <a href="oil-installations.php">Oil Installations</a>
</span>
      </span>
      <a href="our-work.php">Our Work</a>
      <a href="about.php">About Us</a>
      <a href="contact.php">Contact</a>
    </nav>

    <div class="nav-actions">
      <a class="btn btn-primary" href="contact.php">Get a Quote →</a>
      <a href="https://wa.me/447502216131" class="header-whatsapp" aria-label="WhatsApp">
        <img src="assets/icons/whatsapp.svg" alt="WhatsApp">
      </a>
    </div>
  </div>
</header>
<main><section class="page-hero"><div class="wrap"><h1>Electrical Services</h1><p>Domestic, commercial and industrial electrical installation, upgrades and maintenance.</p></div></section><section class="section"><div class="wrap content-grid"><article class="content-card"><img class="service-detail-image" data-service-image="electrical" src="assets/images/services/electrical-services.jpg" alt="Electrical Services"><h2>Electrical Services for domestic, commercial and industrial properties</h2><p>Domestic, commercial and industrial electrical installation, upgrades and maintenance.</p><h3>How we can help</h3><ul><li>Clear advice before work begins</li><li>Safe and tidy installation</li><li>Domestic property focused service</li><li>Ongoing support and maintenance where appropriate</li></ul><a class="btn btn-primary" href="contact.php">Request a Quote →</a></article><aside class="side-nav">
<a href="air-conditioning.php">Air Conditioning</a><a href="solar-pv.php">Solar PV</a><a href="battery-storage.php">Battery Storage</a><a href="ev-chargers.php">EV Chargers</a><a href="electrical-services.php">Electrical Services</a><a href="gas-services.php">Gas Services</a>
          <a href="oil-installations.php">Oil Installations</a>
</aside></div></section></main>
<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="assets/js/site.js"></script>
<script id="FINAL_MOBILE_NAV_INLINE_FIX">
(function () {
  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  ready(function () {
    var toggle = document.querySelector('.menu-toggle');
    var nav = document.querySelector('.main-nav');
    var dropdown = document.querySelector('.main-nav .nav-dropdown');

    if (toggle && nav) {
      toggle.setAttribute('aria-expanded', 'false');

      toggle.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();

        var open = !nav.classList.contains('mobile-open');
        nav.classList.toggle('mobile-open', open);
        document.body.classList.toggle('mobile-nav-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
    }

    if (dropdown) {
      var serviceLink = dropdown.querySelector(':scope > a') || dropdown.querySelector('a');
      if (serviceLink) {
        serviceLink.addEventListener('click', function (event) {
          if (window.matchMedia('(max-width: 860px)').matches) {
            event.preventDefault();
            event.stopPropagation();
            dropdown.classList.toggle('services-open');
          }
        });
      }
    }
  });
})();
</script>

</body></html>
<?php include 'opening-hours.php'; ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Gas Services | One Source Air & Energy Ltd</title><meta name="description" content="Domestic gas work, boiler installation, servicing and repairs by Gas Safe engineers."><link rel="stylesheet" href="assets/css/styles.css">
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

    <button class="menu-toggle" aria-label="Open menu">☰</button>

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
<main><section class="page-hero"><div class="wrap"><h1>Gas Services</h1><p>Domestic gas work, boiler installation, servicing and repairs by Gas Safe engineers.</p></div></section><section class="section"><div class="wrap content-grid"><article class="content-card"><img class="service-detail-image" data-service-image="gas-services" src="assets/images/services/gas-services.jpg" alt="Gas Services"><h2>Gas Services for domestic properties</h2><p>Domestic gas work, boiler installation, servicing and repairs by Gas Safe engineers.</p><h3>How we can help</h3><ul><li>Clear advice before work begins</li><li>Safe and tidy installation</li><li>Domestic property focused service</li><li>Ongoing support and maintenance where appropriate</li></ul><a class="btn btn-primary" href="contact.php">Request a Quote →</a></article><aside class="side-nav">
<a href="air-conditioning.php">Air Conditioning</a><a href="solar-pv.php">Solar PV</a><a href="battery-storage.php">Battery Storage</a><a href="ev-chargers.php">EV Chargers</a><a href="electrical-services.php">Electrical Services</a><a href="gas-services.php">Gas Services</a>
          <a href="oil-installations.php">Oil Installations</a>
</aside></div></section></main>
<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="assets/js/site.js"></script></body></html>
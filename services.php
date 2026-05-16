<?php include 'opening-hours.php'; ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Domestic Energy Services | One Source Air & Energy Ltd</title><meta name="description" content="Explore domestic air conditioning, solar PV, battery storage, EV charger, electrical and gas services."><link rel="stylesheet" href="assets/css/styles.css">
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
<main><section class="page-hero"><div class="wrap">
<p>Explore services for homes across the West Midlands.</p></div></section><section class="section"><div class="wrap"><div class="service-grid"><article class="service-card"><img data-service-image="air-conditioning" src="assets/images/services/air-conditioning.jpg" alt="Air Conditioning"><div class="service-body"><span class="service-icon icon-air">❄</span><h3>Air Conditioning</h3><p>Installation, maintenance and servicing for bedrooms, living spaces, garden rooms and home offices.</p><a href="air-conditioning.php">Learn more →</a></div></article><article class="service-card"><img data-service-image="solar-pv" src="assets/images/services/solar-pv.jpg" alt="Solar PV"><div class="service-body"><span class="service-icon icon-solar">☀</span><h3>Solar PV</h3><p>Solar PV installed to MCS standards, designed to help reduce energy bills.</p><a href="solar-pv.php">Learn more →</a></div></article><article class="service-card"><img data-service-image="battery-storage" src="assets/images/services/battery-storage.jpg" alt="Battery Storage"><div class="service-body"><span class="service-icon icon-battery">▣</span><h3>Battery Storage</h3><p>Store generated electricity and make better use of your solar system day and night.</p><a href="battery-storage.php">Learn more →</a></div></article><article class="service-card"><img data-service-image="ev-chargers" src="assets/images/services/ev-chargers.jpg" alt="EV Chargers"><div class="service-body"><span class="service-icon icon-ev ev-charger-icon"><img src="assets/icons/ev-charger.svg" alt="" aria-hidden="true"></span><h3>EV Chargers</h3><p>Neat home EV charging installations carried out by experienced electrical engineers.</p><a href="ev-chargers.php">Learn more →</a></div></article><article class="service-card"><img data-service-image="electrical" src="assets/images/services/electrical-services.jpg" alt="Electrical Services"><div class="service-body"><span class="service-icon icon-electric">⚡</span><h3>Electrical Services</h3><p>Domestic electrical works, upgrades and installation support from NICEIC approved contractors.</p><a href="electrical-services.php">Learn more →</a></div></article><article class="service-card"><img data-service-image="gas-services" src="assets/images/services/gas-services.jpg" alt="Gas Services"><div class="service-body"><span class="service-icon icon-gas">🔥</span><h3>Gas Services</h3><p>Domestic gas work, boiler installation, servicing and repairs by Gas Safe engineers.</p><a href="gas-services.php">Learn more →</a></div></article></div></div></section></main>
<footer>
  <div class="wrap footer-inner">
    <div class="footer-legal">
      <strong>ONE SOURCE AIR & ENERGY LTD</strong>
      <span>Registered in England & Wales • Company No. 10162474</span>
      <span>Cleobury Mortimer, Kidderminster DY14 8DP</span>
      <span class="footer-links"><a href="privacy.php">Privacy & Cookie Policy</a><span aria-hidden="true">•</span><a href="admin/">Admin Login</a></span>
    </div>

    <div class="footer-social">
      <a href="https://facebook.com/" target="_blank" rel="noopener" aria-label="Facebook">
        <img src="assets/icons/facebook.svg" alt="Facebook">
      </a>
      <a href="https://instagram.com/" target="_blank" rel="noopener" aria-label="Instagram"><img src="assets/icons/instagram.svg" alt="Instagram"></a>
      <a href="https://wa.me/447502216131" class="header-whatsapp" aria-label="WhatsApp"><img src="assets/icons/whatsapp.svg" alt="WhatsApp"></a>
    </div>
  </div>
</footer>
<script src="assets/js/site.js"></script></body></html>
<?php include 'opening-hours.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Oil Installations | One Source Air & Energy Ltd</title>
<meta name="description" content="Domestic, commercial and industrial oil heating installation and servicing across the West Midlands.">

<link rel="icon" type="image/x-icon" href="favicon.ico">
<link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<div class="topbar">
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
    <a href="index.php" class="logo">
      <img src="assets/logos/onesource-logo.png" alt="One Source Air & Energy Ltd">
    </a>

    <nav class="main-nav">
      <a href="index.php">Home</a>
      <a href="services.php">Services</a>
      <a href="our-work.php">Our Work</a>
      <a href="about.php">About Us</a>
      <a href="contact.php">Contact</a>
    </nav>

    <div class="nav-actions">
      <a class="btn btn-primary" href="contact.php">Get a Quote →</a>
    </div>
  </div>
</header>

<section class="page-hero">
  <div class="wrap">
    <h1>Oil Installations</h1>
    <p>Domestic, commercial and industrial oil heating installation, servicing and support.</p>
  </div>
</section>

<section class="service-content">
  <div class="wrap">
    <div class="service-layout">

      <div class="service-main">
        <img src="assets/service-images/oil-installations.png" alt="Oil heating installation" style="width:100%; border-radius:12px; margin-bottom:30px;">

        <h2>How we can help</h2>

        <ul class="service-list">
          <li>Oil boiler installation</li>
          <li>Oil heating upgrades</li>
          <li>Replacement systems</li>
          <li>Commercial oil systems</li>
          <li>Industrial oil heating support</li>
          <li>General servicing and maintenance</li>
        </ul>

        <p>
          Professional oil heating services carried out safely and reliably across the West Midlands and surrounding areas.
        </p>
      </div>

      <aside class="service-sidebar">
        <div class="sidebar-card">
          <h3>Our Services</h3>

          <a href="air-conditioning.php">Air Conditioning</a>
          <a href="solar-pv.php">Solar PV</a>
          <a href="battery-storage.php">Battery Storage</a>
          <a href="ev-chargers.php">EV Chargers</a>
          <a href="electrical-services.php">Electrical Services</a>
          <a href="gas-services.php">Gas Services</a>
          <a href="oil-installations.php">Oil Installations</a>
        </div>
      </aside>

    </div>
  </div>
</section>

</body>
</html>

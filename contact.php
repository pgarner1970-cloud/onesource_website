<?php include 'opening-hours.php'; ?>
<?php require_once __DIR__ . '/site-config.php'; ?>
<?php include 'opening-hours.php'; ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Contact One Source Air & Energy Ltd | Request a Quote</title><meta name="description" content="Contact One Source Air & Energy Ltd to request a quote for domestic energy, electrical or gas services."><link rel="stylesheet" href="assets/css/styles.css">  
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
<p>Tell us about your property and the work you need help with.</p></div></section><section class="section contact-section"><div class="wrap contact-grid"><div>
<h2>Get in touch with One Source.</h2><p>Phone: 07502 216131<br>Email: luke@onesourceairandenergyltd.co.uk</p><p>Cleobury Mortimer, Kidderminster DY14 8DP</p></div>
          <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div class="form-message success">Thank you. Your enquiry has been sent.</div>
          <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
            <div class="form-message error"><?= htmlspecialchars($_GET['message'] ?? 'There was a problem sending your enquiry.') ?></div>
          <?php endif; ?>
<form class="quote-form" method="post" action="send-enquiry.php"><div class="form-row"><label>Name<input type="text" name="name" placeholder="Your name" required></label><label>Phone<input type="tel" name="phone" placeholder="Your phone number" required></label></div><div class="form-row"><label>Email<input type="email" name="email" placeholder="Your email address" required></label><label>Service<select name="service" required><option>Air Conditioning</option><option>Solar PV</option><option>Battery Storage</option><option>EV Charger</option><option>Electrical Services</option><option>Gas Services</option></select></label></div><label>Message<textarea name="message" placeholder="Tell us about the property and work required" required></textarea></label><input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off"><button type="submit" class="btn btn-primary">Send Enquiry →</button></form>
        <div class="contact-map">
          <h3>Find us</h3>
          <p>Cleobury Mortimer, Kidderminster DY14 8DP</p>
          <iframe
            title="Map showing Cleobury Mortimer, Kidderminster DY14 8DP"
            src="https://www.google.com/maps?q=Cleobury%20Mortimer%2C%20Kidderminster%20DY14%208DP&output=embed"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div></div></section></main>
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
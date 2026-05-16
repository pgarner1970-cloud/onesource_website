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
      <a href="advice-insights.php">Advice & Insights</a>
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
<form class="quote-form" method="post" action="send-enquiry.php"><div class="form-row"><label>Name<input type="text" name="name" placeholder="Your name" required></label><label>Phone<input type="tel" name="phone" placeholder="Your phone number" required></label></div><div class="form-row"><label>Email<input type="email" name="email" placeholder="Your email address" required></label><label>Service<select name="service" required><option>Air Conditioning</option>
              <option>Solar PV</option>
              <option>Battery Storage</option>
              <option>EV Chargers</option>
              <option>Electrical Services</option>
              <option>Gas Services</option>
              <option>Oil Installations</option></select></label></div><label>Message<textarea name="message" placeholder="Tell us about the property and work required" required></textarea></label><input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off"><button type="submit" class="btn btn-primary">Send Enquiry →</button></form>
        <div class="contact-map">
          <h3>Find us</h3>
          <p>Cleobury Mortimer, Kidderminster DY14 8DP</p>
          <iframe
            title="Map showing Cleobury Mortimer, Kidderminster DY14 8DP"
            src="https://www.google.com/maps?q=Cleobury%20Mortimer%2C%20Kidderminster%20DY14%208DP&output=embed"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div></div></section></main>
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
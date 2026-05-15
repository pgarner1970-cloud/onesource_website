<?php include 'opening-hours.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Privacy & Cookie Policy | One Source Air & Energy Ltd</title>
  <meta name="description" content="Privacy and Cookie Policy for One Source Air & Energy Ltd.">
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

    <button class="menu-toggle">☰</button>

    <nav class="main-nav">
      <a href="index.php">Home</a>
</nav>

    <div class="nav-actions">
      <a class="btn btn-primary" href="contact.php">Get a Quote →</a>
      <a href="https://wa.me/447502216131" class="header-whatsapp" aria-label="WhatsApp"><img src="assets/icons/whatsapp.svg" alt="WhatsApp"></a>
    </div>
  </div>
</header>

<main>
  <section class="page-hero">
    <div class="wrap">
      <h1>Privacy & Cookie Policy</h1>
      <p>Simple information about how we handle website enquiries and cookies.</p>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <article class="content-card compact-policy">
        <h2>Privacy</h2>

        <p>
          ONE SOURCE AIR & ENERGY LTD may collect your name, telephone number,
          email address and enquiry details when you contact us through this website.
        </p>

        <p>
          Your information is only used to respond to enquiries, provide quotations,
          arrange appointments and deliver our services.
        </p>

        <p>
          We do not sell your information to third parties.
        </p>

        <p>
          You may request access to or deletion of your personal information by contacting:
          <a href="mailto:luke@onesourceairandenergyltd.co.uk">luke@onesourceairandenergyltd.co.uk</a>
        </p>

        <h2>Cookies</h2>

        <p>
          This website may use basic cookies required for website functionality,
          performance and analytics.
        </p>

        <p>
          By continuing to use this website, you agree to the use of these cookies.
        </p>

        <h2>Company Information</h2>

        <p>
          ONE SOURCE AIR & ENERGY LTD<br>
          Registered in England & Wales<br>
          Company No. 10162474
        </p>
      </article>
    </div>
  </section>
</main>

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

<script src="assets/js/site.js"></script>
</body>
</html>

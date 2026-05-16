<?php include 'opening-hours.php'; ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>One Source Air & Energy Ltd | Domestic Energy Services West Midlands</title><meta name="description" content="Domestic air conditioning, solar PV, battery storage, EV chargers, electrical and gas services across the West Midlands."><link rel="stylesheet" href="assets/css/styles.css">
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
<main><section class="hero"><div class="hero-bg"></div><div class="wrap hero-content"><h1>Trusted home energy solutions across the West Midlands.</h1><p class="hero-copy">Professional domestic installations, certified engineers and quality workmanship you can rely on.</p><div class="hero-buttons"><a href="contact.php" class="btn btn-primary">Request a Quote →</a><a href="our-work.php" class="btn btn-ghost">View Our Work →</a></div></div></section>

<section class="trust-strip">
  <div class="wrap trust-grid">
    <div class="trust-heading">TRUSTED. CERTIFIED.<br>ACCREDITED.</div>

    <div class="badge">
      <img src="assets/logos/gas-safe-register.png" alt="Gas Safe Register">
    </div>

    <div class="badge">
      <img src="assets/accreditations/oftec.png" alt="OFTEC Registered">
    </div>

    <div class="badge">
      <img src="assets/logos/niceic-approved-contractor.png" alt="NICEIC Approved Contractor">
    </div>

    <div class="badge">
      <img src="assets/logos/refcom.png" alt="REFCOM F-Gas Certification">
    </div>

    <div class="badge">
      <img src="assets/logos/mcs-certified.png" alt="MCS Certified">
    </div>

    <div class="badge quality-badge">
      <span>★★★★★</span>
      <strong>QUALITY WORKMANSHIP</strong>
    </div>
  </div>
</section>

<section class="trust">
  <div class="wrap trust-grid">
<a href="https://www.gassaferegister.co.uk/" class="badge" target="_blank" rel="noopener"><img src="assets/logos/gas-safe-register.png" alt="Gas Safe Register logo"></a>
            <div class="accreditation-card">
                <a href="https://www.oftec.org/" target="_blank" rel="noopener noreferrer"><img src="assets/accreditations/oftec.png" alt="OFTEC Certified"></a>
            </div>

    <a href="https://niceic.com/" class="badge" target="_blank" rel="noopener"><img src="assets/logos/niceic-approved-contractor.png" alt="NICEIC Approved Contractor logo"></a>
    <a href="https://www.refcom.org.uk/" class="badge" target="_blank" rel="noopener"><img src="assets/logos/refcom.png" alt="REFCOM F-Gas Certification logo"></a>
    <a href="https://mcscertified.com/" class="badge" target="_blank" rel="noopener"><img src="assets/logos/mcs-certified.png" alt="MCS Certified logo"></a>
    <div class="rating">★★★★★<br><small>Quality workmanship</small></div>
</div>
</section>

<section class="section"><div class="wrap"><div class="section-title"><h2>Domestic, commercial & industrial services from one reliable source.</h2></div><div class="service-grid">
          <article class="service-card">
            <a href="air-conditioning.php">
              <img data-service-image="air-conditioning" src="assets/images/services/air-conditioning.jpg" alt="Air conditioning installation">
              <span class="service-icon icon-air-conditioning"><img src="assets/icons/air-conditioning.svg" alt=""></span>
              <h3>Air Conditioning</h3>
              <p>Domestic, commercial and industrial air conditioning installation, servicing and maintenance.</p>
              <strong>Learn more →</strong>
            </a>
          </article>
          <article class="service-card">
            <a href="solar-pv.php">
              <img data-service-image="solar-pv" src="assets/images/services/solar-pv.jpg" alt="Solar PV installation">
              <span class="service-icon icon-solar-pv"><img src="assets/icons/solar-pv.svg" alt=""></span>
              <h3>Solar PV</h3>
              <p>Solar PV installed to MCS standards, designed to help reduce energy bills.</p>
              <strong>Learn more →</strong>
            </a>
          </article>
          <article class="service-card">
            <a href="battery-storage.php">
              <img data-service-image="battery-storage" src="assets/images/services/battery-storage.jpg" alt="Battery storage installation">
              <span class="service-icon icon-battery-storage"><img src="assets/icons/battery-storage.svg" alt=""></span>
              <h3>Battery Storage</h3>
              <p>Store generated electricity and make better use of your solar system day and night.</p>
              <strong>Learn more →</strong>
            </a>
          </article>
          <article class="service-card">
            <a href="ev-chargers.php">
              <img data-service-image="ev-chargers" src="assets/images/services/ev-chargers.jpg" alt="EV charger installation">
              <span class="service-icon icon-ev-chargers"><img src="assets/icons/ev-charger.svg" alt=""></span>
              <h3>EV Chargers</h3>
              <p>Neat home EV charging installations carried out by experienced electrical engineers.</p>
              <strong>Learn more →</strong>
            </a>
          </article>
          <article class="service-card">
            <a href="electrical-services.php">
              <img data-service-image="electrical-services" src="assets/images/services/electrical-services.jpg" alt="Electrical services">
              <span class="service-icon icon-electrical-services"><img src="assets/icons/electrical-services.svg" alt=""></span>
              <h3>Electrical Services</h3>
              <p>Domestic, commercial and industrial electrical installation, upgrades and maintenance.</p>
              <strong>Learn more →</strong>
            </a>
          </article>
          <article class="service-card">
            <a href="gas-services.php">
              <img data-service-image="gas-services" src="assets/images/services/gas-services.jpg" alt="Gas services">
              <span class="service-icon icon-gas-services"><img src="assets/icons/gas-services.svg" alt=""></span>
              <h3>Gas Services</h3>
              <p>Domestic gas work, boiler installation, servicing and repairs by Gas Safe engineers.</p>
              <strong>Learn more →</strong>
            </a>
          </article>
          <article class="service-card">
            <a href="oil-installations.php">
              <img data-service-image="oil-installations" src="assets/images/services/oil-installations.jpg" alt="Domestic oil heating installation">
              <span class="service-icon icon-oil-installations"><img src="assets/icons/oil-installations.svg" alt=""></span>
              <h3>Oil Installations</h3>
              <p>Domestic oil heating installation, servicing and support.</p>
              <strong>Learn more →</strong>
            </a>
          </article>






</div></section></main>
<section class="accreditations-section">
  <div class="wrap">
    <div class="section-heading">
      <h2>Accreditations & Certifications</h2>
      <p>Trusted, certified and professionally accredited across domestic, commercial and industrial energy services.</p>
    </div>

    <div class="accreditation-grid">

      <article class="accreditation-info-card">
        <div class="accreditation-logo">
          <img src="assets/logos/gas-safe-register.png" alt="Gas Safe Register">
        </div>
        <div class="accreditation-content">
          <h3>Gas Safe Registered</h3>
          <h4>Domestic Gas Engineer</h4>
          <p>We are Gas Safe registered for domestic gas installations, servicing, repairs and boiler work carried out safely and professionally.</p>
        </div>
      </article>

      <article class="accreditation-info-card">
        <div class="accreditation-logo">
          <img src="assets/logos/niceic-approved-contractor.png" alt="NICEIC Approved Contractor">
        </div>
        <div class="accreditation-content">
          <h3>NICEIC Approved Contractor</h3>
          <h4>Electrical Contractor</h4>
          <p>We undertake domestic, commercial and industrial electrical installations, upgrades, inspections and EV charging solutions to NICEIC standards.</p>
        </div>
      </article>

      <article class="accreditation-info-card">
        <div class="accreditation-logo">
          <img src="assets/logos/refcom.png" alt="REFCOM F-Gas Certification">
        </div>
        <div class="accreditation-content">
          <h3>REFCOM Registered</h3>
          <h4>F-Gas Certified Engineers</h4>
          <p>REFCOM registration confirms we are qualified to safely handle F-Gas refrigerants used in air conditioning and cooling systems.</p>
        </div>
      </article>

      <article class="accreditation-info-card">
        <div class="accreditation-logo">
          <a href="https://www.oftec.org/" target="_blank" rel="noopener noreferrer">
            <img src="assets/accreditations/oftec.png" alt="OFTEC Registered">
          </a>
        </div>
        <div class="accreditation-content">
          <h3>OFTEC Registered</h3>
          <h4>Oil Heating Engineer</h4>
          <p>OFTEC registration demonstrates competence in domestic oil heating installation, servicing and maintenance work.</p>
        </div>
      </article>

    
      <article class="accreditation-info-card">
        <div class="accreditation-logo">
          <img src="assets/logos/mcs-certified.png" alt="MCS Certified">
        </div>
        <div class="accreditation-content">
          <h3>MCS Certified</h3>
          <h4>Renewable Energy Standards</h4>
          <p>MCS certification supports recognised standards for renewable energy installations such as Solar PV and related energy systems.</p>
        </div>
      </article>

    
      <article class="accreditation-info-card">
        <div class="accreditation-logo quality-logo">
          <span>★★★★★</span>
        </div>
        <div class="accreditation-content">
          <h3>Quality Workmanship</h3>
          <h4>Reliable, Professional Service</h4>
          <p>We focus on neat installations, clear advice and dependable workmanship across every service we provide.</p>
        </div>
      </article>

    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="assets/js/site.js"></script></body></html>
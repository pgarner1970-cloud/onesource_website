<header class="admin-header">
  <div class="admin-header-inner">
    <a class="admin-brand" href="index.php">Site Admin</a>

    <button class="admin-menu-toggle" type="button" aria-label="Open admin menu" aria-expanded="false">
      <span></span>
      <span></span>
      <span></span>
    </button>

    <div class="admin-nav-wrap" id="adminNav">
      <nav class="admin-nav admin-nav-main" aria-label="Admin navigation">
        <a href="index.php">Dashboard</a>
        <a href="gallery.php">Gallery</a>
        <a href="service-images.php">Service Images</a>
        <a href="enquiries.php">Enquiries</a>
        <a href="opening-hours.php">Opening Hours</a>
        <a href="advice-admin.php">Advice &amp; Insights</a>
        <a href="social-links.php">Social Media</a>
        <a href="trustpilot-settings.php">Trustpilot</a>
        <a href="admin-users.php">Admin Users</a>
        <a href="audit-log.php">Audit Log</a>
      </nav>

      <nav class="admin-nav admin-nav-actions" aria-label="Admin actions">
        <a href="../index.php">Back to Website</a>
        <a href="logout.php">Logout</a>
      </nav>
    </div>
  </div>
</header>

<script>
(function () {
  var toggle = document.querySelector('.admin-menu-toggle');
  var nav = document.querySelector('.admin-nav-wrap');

  if (!toggle || !nav) return;

  toggle.addEventListener('click', function () {
    var isOpen = nav.classList.toggle('is-open');
    toggle.classList.toggle('is-open', isOpen);
    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });

  nav.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', function () {
      nav.classList.remove('is-open');
      toggle.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
    });
  });
})();
</script>

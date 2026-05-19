<?php
require_once __DIR__ . '/auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Site Admin | One Source</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include __DIR__ . '/includes/admin-header.php'; ?>

<main class="admin-wrap">
  <section class="admin-panel">
    <h2>Site Admin</h2>
    <p class="admin-note">Manage the main website content, gallery, service images, settings and security tools.</p>

    <div class="admin-dashboard-grid">
      <a class="admin-dashboard-card" href="enquiries.php">
        <strong>Enquiries</strong>
        <span>View, track and export website contact enquiries.</span>
      </a>
      <a class="admin-dashboard-card" href="gallery.php">
        <strong>Gallery</strong>
        <span>Upload, edit, show/hide and delete project images.</span>
      </a>

      <a class="admin-dashboard-card" href="service-images.php">
        <strong>Service Images</strong>
        <span>Set default images for service cards and service pages.</span>
      </a>

      <a class="admin-dashboard-card" href="opening-hours.php">
        <strong>Opening Hours</strong>
        <span>Update the live opening-hours banner and bank holiday note.</span>
      </a>

      <a class="admin-dashboard-card" href="advice-admin.php">
        <strong>Advice &amp; Insights</strong>
        <span>Create, review and approve article drafts.</span>
      </a>

      <a class="admin-dashboard-card" href="social-links.php">
        <strong>Social Media</strong>
        <span>Manage footer social icons and links.</span>
      </a>

      <a class="admin-dashboard-card" href="trustpilot-settings.php">
        <strong>Trustpilot</strong>
        <span>Set Trustpilot profile and Business Unit ID.</span>
      </a>

      <a class="admin-dashboard-card" href="admin-users.php">
        <strong>Admin Users</strong>
        <span>Manage admin accounts and permissions.</span>
      </a>

      <a class="admin-dashboard-card" href="audit-log.php">
        <strong>Audit Log</strong>
        <span>Review recent admin and security activity.</span>
      </a>
    </div>
  </section>

  <section class="admin-panel admin-warning-panel">
    <h3>Migration tools</h3>
    <p>The JSON-to-MySQL migration tool is no longer shown in the main menu. Keep it only for fallback/support, and remove or disable it once you are fully satisfied everything has migrated.</p>
    <a class="admin-secondary-link" href="migrate-json-to-mysql.php">Open migration tool</a>
  </section>
</main>
</body>
</html>

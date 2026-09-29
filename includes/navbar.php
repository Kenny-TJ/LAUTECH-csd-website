<?php
// includes/navbar.php
$currentPage = basename($_SERVER['PHP_SELF']);

// Fetch active, non-expired announcements
$annResult = mysqli_query($conn,
    "SELECT * FROM announcements
     WHERE is_active = 1
     AND (expires_at IS NULL OR expires_at >= CURDATE())
     ORDER BY created_at DESC
     LIMIT 3"
);
$announcements = mysqli_fetch_all($annResult, MYSQLI_ASSOC);
?>

<?php if (!empty($announcements)): ?>
<div class="announcement-bar">
  <div class="container d-flex align-items-center justify-content-between gap-3">
    <div class="announcement-scroll d-flex gap-4 flex-wrap">
      <?php foreach ($announcements as $ann): ?>
        <span>
          <i class="fas fa-bullhorn me-1"></i>
          <?php echo htmlspecialchars($ann['message']); ?>
          <?php if ($ann['link_url'] && $ann['link_text']): ?>
            &mdash; <a href="<?php echo htmlspecialchars($ann['link_url']); ?>"
                       class="ann-link"><?php echo htmlspecialchars($ann['link_text']); ?></a>
          <?php endif; ?>
        </span>
      <?php endforeach; ?>
    </div>
    <button type="button" class="btn-close btn-close-white btn-sm flex-shrink-0"
            onclick="this.closest('.announcement-bar').remove()"></button>
  </div>
</div>
<?php endif; ?>

<header class="site-header">
  <!-- Top bar: Logo + School name -->
  <div class="header-top py-2">
    <div class="container d-flex align-items-center gap-3">
      <img src="/lautech_csd/assets/images/lautech-logo.png"
           alt="LAUTECH Logo" height="70"
           onerror="this.style.display='none'">
      <div>
        <div class="school-name">Ladoke Akintola University of Technology</div>
        <div class="dept-name">Department of Computer Science &amp; Engineering</div>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <button class="navbar-toggler" type="button"
              data-bs-toggle="collapse" data-bs-target="#mainNav"
              aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav me-auto">

          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage === 'index.php') ? 'active' : ''; ?>"
               href="/lautech_csd/index.php">Home</a>
          </li>

          <!-- About Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle <?php echo in_array($currentPage, array('about.php','contact.php')) ? 'active' : ''; ?>"
               href="#" data-bs-toggle="dropdown" role="button"
               aria-expanded="false">About</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/lautech_csd/about.php">About the Department</a></li>
              <li><a class="dropdown-item" href="/lautech_csd/contact.php">Contact Us</a></li>
            </ul>
          </li>

          <!-- Academics Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle <?php echo in_array($currentPage, array('courses.php','regulations.php','calendar.php','resources.php')) ? 'active' : ''; ?>"
               href="#" data-bs-toggle="dropdown" role="button"
               aria-expanded="false">Academics</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/lautech_csd/courses.php">Courses</a></li>
              <li><a class="dropdown-item" href="/lautech_csd/regulations.php">Regulations</a></li>
              <li><a class="dropdown-item" href="/lautech_csd/calendar.php">Academic Calendar</a></li>
              <li><a class="dropdown-item" href="/lautech_csd/resources.php">Student Resources</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage === 'staff.php') ? 'active' : ''; ?>"
               href="/lautech_csd/staff.php">Staff</a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage === 'research.php') ? 'active' : ''; ?>"
               href="/lautech_csd/research.php">Research</a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage === 'news.php') ? 'active' : ''; ?>"
               href="/lautech_csd/news.php">News</a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage === 'profile.php') ? 'active' : ''; ?>"
               href="/lautech_csd/profile.php">File Submission</a>
          </li>

        </ul>
      </div>
    </div>
  </nav>
</header>

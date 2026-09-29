<?php
// admin/admin_layout.php
// Usage: include after requireLogin() and before page content
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($adminPageTitle) ? htmlspecialchars($adminPageTitle) : 'Admin Panel'; ?> — CS Dept</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root { --admin-green: #006400; --admin-gold: #FFD700; }
    body { background: #f4f6f9; font-size: 0.92rem; }

    /* Sidebar */
    .admin-sidebar {
      width: 240px; min-height: 100vh;
      background: var(--admin-green);
      position: fixed; top: 0; left: 0;
      z-index: 100; overflow-y: auto;
    }
    .sidebar-brand {
      padding: 1.2rem 1rem;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .sidebar-brand .brand-title { color: white; font-weight: 700; font-size: 0.95rem; }
    .sidebar-brand .brand-sub   { color: var(--admin-gold); font-size: 0.75rem; }
    .sidebar-nav .nav-link {
      color: rgba(255,255,255,0.8);
      padding: 0.6rem 1rem;
      border-radius: 6px;
      margin: 2px 8px;
      font-size: 0.88rem;
    }
    .sidebar-nav .nav-link:hover,
    .sidebar-nav .nav-link.active {
      background: rgba(255,255,255,0.15);
      color: var(--admin-gold);
    }
    .sidebar-nav .nav-link i { width: 20px; }
    .sidebar-section {
      color: rgba(255,255,255,0.4);
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      padding: 0.8rem 1rem 0.3rem;
    }

    /* Main content */
    .admin-main {
      margin-left: 240px;
      min-height: 100vh;
    }
    .admin-topbar {
      background: white;
      border-bottom: 1px solid #e0e0e0;
      padding: 0.75rem 1.5rem;
      position: sticky; top: 0; z-index: 99;
    }
    .admin-content { padding: 1.5rem; }

    /* Cards */
    .stat-card { border: none; border-radius: 10px; }
    .stat-card .stat-icon {
      width: 48px; height: 48px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.3rem;
    }

    /* Tables */
    .admin-table th { background: #f8f9fa; font-weight: 600; font-size: 0.83rem; }
    .admin-table td { vertical-align: middle; font-size: 0.88rem; }

    /* Badges */
    .badge-active   { background: #d4edda; color: #155724; }
    .badge-inactive { background: #f8d7da; color: #721c24; }
  </style>
</head>
<body>

<!-- Sidebar -->
<aside class="admin-sidebar">
  <div class="sidebar-brand">
    <div class="brand-title"><i class="fas fa-shield-halved me-2" style="color:var(--admin-gold);"></i>Admin Panel</div>
    <div class="brand-sub">CS Department, LAUTECH</div>
  </div>

  <nav class="sidebar-nav mt-2">
    <?php $curPage = basename($_SERVER['PHP_SELF']); ?>

    <div class="sidebar-section">Main</div>
    <a href="/lautech_csd/admin/dashboard.php"
       class="nav-link <?php echo ($curPage === 'dashboard.php') ? 'active' : ''; ?>">
      <i class="fas fa-gauge-high"></i> Dashboard
    </a>

    <div class="sidebar-section">Content</div>
    <a href="/lautech_csd/admin/manage-news.php"
       class="nav-link <?php echo ($curPage === 'manage-news.php') ? 'active' : ''; ?>">
      <i class="fas fa-newspaper"></i> News &amp; Notices
    </a>
    <a href="/lautech_csd/admin/manage-announcements.php"
       class="nav-link <?php echo ($curPage === 'manage-announcements.php') ? 'active' : ''; ?>">
      <i class="fas fa-bullhorn"></i> Announcements
    </a>
    <a href="/lautech_csd/admin/manage-staff.php"
       class="nav-link <?php echo ($curPage === 'manage-staff.php') ? 'active' : ''; ?>">
      <i class="fas fa-users"></i> Staff
    </a>
    <a href="/lautech_csd/admin/manage-calendar.php"
       class="nav-link <?php echo ($curPage === 'manage-calendar.php') ? 'active' : ''; ?>">
      <i class="fas fa-calendar-alt"></i> Academic Calendar
    </a>

    <div class="sidebar-section">Students</div>
    <a href="/lautech_csd/admin/view-submissions.php"
       class="nav-link <?php echo ($curPage === 'view-submissions.php') ? 'active' : ''; ?>">
      <i class="fas fa-file-arrow-up"></i> File Submissions
    </a>

	<a href="/lautech_csd/admin/view-messages.php"
		class="nav-link <?php echo ($curPage === 'view-messages.php') ? 'active' : ''; ?>">
		<i class="fas fa-envelope"></i> Contact Messages
	</a>
    <div class="sidebar-section">Account</div>
    <a href="/lautech_csd/index.php" class="nav-link" target="_blank">
      <i class="fas fa-globe"></i> View Website
    </a>
    <a href="/lautech_csd/admin/logout.php" class="nav-link text-danger">
      <i class="fas fa-right-from-bracket"></i> Logout
    </a>
  </nav>
</aside>

<!-- Top Bar -->
<div class="admin-main">
  <div class="admin-topbar d-flex justify-content-between align-items-center">
    <h6 class="mb-0 fw-bold">
      <?php echo isset($adminPageTitle) ? htmlspecialchars($adminPageTitle) : 'Dashboard'; ?>
    </h6>
    <div class="d-flex align-items-center gap-3">
      <span class="text-muted small">
        <i class="fas fa-user-shield me-1"></i>
        <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?>
      </span>
      <a href="/lautech_csd/admin/logout.php" class="btn btn-sm btn-outline-danger">
        <i class="fas fa-right-from-bracket me-1"></i>Logout
      </a>
    </div>
  </div>
  <div class="admin-content">
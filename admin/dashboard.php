<?php
require_once dirname(__FILE__) . '/admin_config.php';
require_once dirname(__FILE__) . '/../config/db.php';
requireLogin();
$adminPageTitle = 'Dashboard';

// Fetch counts for stat cards
$counts = array();
$tables = array('news','staff','students','submissions','announcements','courses');
foreach ($tables as $table) {
    $r = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM $table");
    $counts[$table] = mysqli_fetch_assoc($r)['cnt'];
}

// Recent submissions
$recentSubs = mysqli_query($conn,
    "SELECT s.matric_no, s.full_name, s.level, s.session_year, s.submitted_at
     FROM students s ORDER BY s.submitted_at DESC LIMIT 5");
$recentSubs = mysqli_fetch_all($recentSubs, MYSQLI_ASSOC);

// Recent news
$recentNews = mysqli_query($conn,
    "SELECT id, title, category, published_at FROM news
     ORDER BY published_at DESC LIMIT 5");
$recentNews = mysqli_fetch_all($recentNews, MYSQLI_ASSOC);

include dirname(__FILE__) . '/admin_layout.php';
?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
  <?php
  $stats = array(
    array('label'=>'News Items',       'count'=>$counts['news'],        'icon'=>'fa-newspaper',     'bg'=>'#e8f5e9','ic'=>'#006400'),
    array('label'=>'Staff Members',    'count'=>$counts['staff'],       'icon'=>'fa-users',         'bg'=>'#e3f2fd','ic'=>'#1565c0'),
    array('label'=>'Submissions',      'count'=>$counts['students'],    'icon'=>'fa-file-arrow-up', 'bg'=>'#fff3e0','ic'=>'#e65100'),
    array('label'=>'Announcements',    'count'=>$counts['announcements'],'icon'=>'fa-bullhorn',     'bg'=>'#fce4ec','ic'=>'#880e4f'),
    array('label'=>'Courses',          'count'=>$counts['courses'],     'icon'=>'fa-book-open',     'bg'=>'#f3e5f5','ic'=>'#6a1b9a'),
    array('label'=>'Files Uploaded',   'count'=>$counts['submissions'], 'icon'=>'fa-folder-open',   'bg'=>'#e0f7fa','ic'=>'#006064'),
  );
  foreach ($stats as $s): ?>
  <div class="col-6 col-lg-4 col-xl-2">
    <div class="card stat-card shadow-sm h-100">
      <div class="card-body d-flex align-items-center gap-3 p-3">
        <div class="stat-icon" style="background:<?php echo $s['bg']; ?>;">
          <i class="fas <?php echo $s['icon']; ?>" style="color:<?php echo $s['ic']; ?>;"></i>
        </div>
        <div>
          <div class="fw-bold fs-5"><?php echo $s['count']; ?></div>
          <div class="text-muted" style="font-size:0.78rem;"><?php echo $s['label']; ?></div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Recent Activity -->
<div class="row g-4">

  <!-- Recent Submissions -->
  <div class="col-lg-6">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="fas fa-file-arrow-up me-2 text-success"></i>Recent Submissions</h6>
        <a href="/lautech_csd/admin/view-submissions.php" class="btn btn-sm btn-outline-success">View All</a>
      </div>
      <div class="card-body p-0">
        <?php if (empty($recentSubs)): ?>
          <p class="text-muted p-3 mb-0 text-center">No submissions yet.</p>
        <?php else: ?>
        <table class="table admin-table mb-0">
          <thead><tr><th>Matric No</th><th>Name</th><th>Level</th><th>Date</th></tr></thead>
          <tbody>
            <?php foreach ($recentSubs as $sub): ?>
            <tr>
              <td><span class="fw-semibold text-success"><?php echo htmlspecialchars($sub['matric_no']); ?></span></td>
              <td><?php echo htmlspecialchars($sub['full_name']); ?></td>
              <td><?php echo $sub['level']; ?>L</td>
              <td class="text-muted"><?php echo date('d M Y', strtotime($sub['submitted_at'])); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Recent News -->
  <div class="col-lg-6">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="fas fa-newspaper me-2 text-success"></i>Recent News</h6>
        <a href="/lautech_csd/admin/manage-news.php" class="btn btn-sm btn-outline-success">Manage</a>
      </div>
      <div class="card-body p-0">
        <table class="table admin-table mb-0">
          <thead><tr><th>Title</th><th>Category</th><th>Date</th></tr></thead>
          <tbody>
            <?php foreach ($recentNews as $n): ?>
            <tr>
              <td><?php echo htmlspecialchars(substr($n['title'], 0, 35)) . (strlen($n['title']) > 35 ? '...' : ''); ?></td>
              <td><span class="badge bg-success"><?php echo htmlspecialchars($n['category']); ?></span></td>
              <td class="text-muted"><?php echo date('d M Y', strtotime($n['published_at'])); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<?php
// Close the admin layout
echo '</div></div>';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
echo '</body></html>';
?>
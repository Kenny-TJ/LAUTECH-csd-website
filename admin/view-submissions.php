<?php
require_once dirname(__FILE__) . '/admin_config.php';
require_once dirname(__FILE__) . '/../config/db.php';
requireLogin();
$adminPageTitle = 'File Submissions';

// Filter by level or session
$filterLevel   = isset($_GET['level'])   ? intval($_GET['level'])                          : 0;
$filterSession = isset($_GET['session']) ? mysqli_real_escape_string($conn, $_GET['session']) : '';

$where = array();
if ($filterLevel)   { $where[] = "s.level = $filterLevel"; }
if ($filterSession) { $where[] = "s.session_year = '$filterSession'"; }
$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$submissions = mysqli_fetch_all(mysqli_query($conn,
    "SELECT s.*, sub.file_name, sub.file_type, sub.file_size, sub.file_path, sub.submitted_at AS file_date
     FROM students s
     JOIN submissions sub ON sub.student_id = s.id
     $whereSQL
     ORDER BY s.submitted_at DESC"),
    MYSQLI_ASSOC
);

$sessions = mysqli_fetch_all(
    mysqli_query($conn, "SELECT DISTINCT session_year FROM students ORDER BY session_year DESC"),
    MYSQLI_ASSOC
);

include dirname(__FILE__) . '/admin_layout.php';
?>

<!-- Filters -->
<div class="card shadow-sm mb-4">
  <div class="card-body py-2">
    <form method="GET" class="d-flex gap-3 align-items-center flex-wrap">
      <label class="fw-semibold mb-0">Filter:</label>
      <select name="level" class="form-select form-select-sm" style="width:140px;">
        <option value="">All Levels</option>
        <?php foreach (array(100,200,300,400,500) as $lv): ?>
          <option value="<?php echo $lv; ?>" <?php echo ($filterLevel === $lv) ? 'selected' : ''; ?>>
            <?php echo $lv; ?> Level
          </option>
        <?php endforeach; ?>
      </select>
      <select name="session" class="form-select form-select-sm" style="width:150px;">
        <option value="">All Sessions</option>
        <?php foreach ($sessions as $sess): ?>
          <option value="<?php echo $sess['session_year']; ?>"
            <?php echo ($filterSession === $sess['session_year']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($sess['session_year']); ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn btn-sm btn-success">Apply</button>
      <a href="/lautech_csd/admin/view-submissions.php" class="btn btn-sm btn-outline-secondary">Reset</a>
      <span class="text-muted small ms-auto">
        <?php echo count($submissions); ?> record(s) found
      </span>
    </form>
  </div>
</div>

<!-- Table -->
<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h6 class="mb-0"><i class="fas fa-file-arrow-up me-2 text-success"></i>Student Submissions</h6>
  </div>
  <div class="card-body p-0">
    <?php if (empty($submissions)): ?>
      <p class="text-muted text-center p-4">No submissions found.</p>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table admin-table mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Matric No</th>
            <th>Full Name</th>
            <th>Level</th>
            <th>Session</th>
            <th>Email</th>
            <th>File</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($submissions as $i => $sub): ?>
          <tr>
            <td class="text-muted"><?php echo $i + 1; ?></td>
            <td><span class="fw-semibold text-success"><?php echo htmlspecialchars($sub['matric_no']); ?></span></td>
            <td><?php echo htmlspecialchars($sub['full_name']); ?></td>
            <td><?php echo $sub['level']; ?>L</td>
            <td><?php echo htmlspecialchars($sub['session_year']); ?></td>
            <td><a href="mailto:<?php echo htmlspecialchars($sub['email']); ?>" class="small">
              <?php echo htmlspecialchars($sub['email']); ?></a></td>
            <td>
              <span class="badge bg-secondary text-uppercase"><?php echo $sub['file_type']; ?></span>
              <span class="text-muted small ms-1"><?php echo round($sub['file_size']/1024, 1); ?>KB</span>
            </td>
            <td class="text-muted small"><?php echo date('d M Y', strtotime($sub['submitted_at'])); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php
echo '</div></div>';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
echo '</body></html>';
?>
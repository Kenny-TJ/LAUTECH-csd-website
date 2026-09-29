<?php
require_once dirname(__FILE__) . '/admin_config.php';
require_once dirname(__FILE__) . '/../config/db.php';
requireLogin();
$adminPageTitle = 'Academic Calendar';

$success = $error = '';

if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
    $id   = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM academic_calendar WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt) ? $success = 'Event deleted.' : $error = 'Delete failed.';
    mysqli_stmt_close($stmt);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id           = intval(isset($_POST['id']) ? $_POST['id'] : 0);
    $session_year = trim(isset($_POST['session_year']) ? $_POST['session_year'] : '');
    $semester     = intval(isset($_POST['semester'])   ? $_POST['semester']     : 1);
    $activity     = trim(isset($_POST['activity'])     ? $_POST['activity']     : '');
    $start_date   = trim(isset($_POST['start_date'])   ? $_POST['start_date']   : '');
    $end_date     = trim(isset($_POST['end_date'])     ? $_POST['end_date']     : '');
    $category     = trim(isset($_POST['category'])     ? $_POST['category']     : 'General');
    $end_date     = $end_date ?: null;

    if (!$session_year || !$activity || !$start_date) {
        $error = 'Session year, activity and start date are required.';
    } else {
        if ($id > 0) {
            $stmt = mysqli_prepare($conn,
                "UPDATE academic_calendar
                 SET session_year=?, semester=?, activity=?, start_date=?, end_date=?, category=?
                 WHERE id=?");
            mysqli_stmt_bind_param($stmt, 'sisssi i',
                $session_year, $semester, $activity, $start_date, $end_date, $category, $id);
        } else {
            $stmt = mysqli_prepare($conn,
                "INSERT INTO academic_calendar
                 (session_year, semester, activity, start_date, end_date, category)
                 VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'sisss s',
                $session_year, $semester, $activity, $start_date, $end_date, $category);
        }
        mysqli_stmt_execute($stmt) ? $success = 'Event saved.' : $error = 'Save failed.';
        mysqli_stmt_close($stmt);
    }
}

$editing = null;
if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
    $id   = intval($_GET['edit']);
    $stmt = mysqli_prepare($conn, "SELECT * FROM academic_calendar WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $editing = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$allEvents = mysqli_fetch_all(
    mysqli_query($conn,
        "SELECT * FROM academic_calendar ORDER BY session_year DESC, start_date ASC"),
    MYSQLI_ASSOC
);

include dirname(__FILE__) . '/admin_layout.php';
?>

<?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
<?php if ($error):   ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

<div class="row g-4">

  <!-- Form -->
  <div class="col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header">
        <h6 class="mb-0">
          <i class="fas fa-<?php echo $editing ? 'pen' : 'plus'; ?> me-2 text-success"></i>
          <?php echo $editing ? 'Edit Event' : 'Add Calendar Event'; ?>
        </h6>
      </div>
      <div class="card-body">
        <form method="POST">
          <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?php echo $editing['id']; ?>">
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label fw-semibold">Session Year <span class="text-danger">*</span></label>
            <input type="text" name="session_year" class="form-control"
                   placeholder="e.g. 2024/2025" required
                   value="<?php echo $editing ? htmlspecialchars($editing['session_year']) : ''; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
            <select name="semester" class="form-select">
              <option value="1" <?php echo ($editing && $editing['semester'] == 1) ? 'selected' : ''; ?>>First Semester</option>
              <option value="2" <?php echo ($editing && $editing['semester'] == 2) ? 'selected' : ''; ?>>Second Semester</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Activity <span class="text-danger">*</span></label>
            <input type="text" name="activity" class="form-control" required
                   value="<?php echo $editing ? htmlspecialchars($editing['activity']) : ''; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Category</label>
            <select name="category" class="form-select">
              <?php foreach (array('Academic','Registration','Examination','Holiday','General') as $cat): ?>
                <option value="<?php echo $cat; ?>"
                  <?php echo ($editing && $editing['category'] === $cat) ? 'selected' : ''; ?>>
                  <?php echo $cat; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
            <input type="date" name="start_date" class="form-control" required
                   value="<?php echo $editing ? $editing['start_date'] : ''; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">
              End Date <span class="text-muted small">(optional)</span>
            </label>
            <input type="date" name="end_date" class="form-control"
                   value="<?php echo ($editing && $editing['end_date']) ? $editing['end_date'] : ''; ?>">
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success flex-grow-1">
              <i class="fas fa-save me-1"></i><?php echo $editing ? 'Update' : 'Add Event'; ?>
            </button>
            <?php if ($editing): ?>
              <a href="/lautech_csd/admin/manage-calendar.php" class="btn btn-outline-secondary">Cancel</a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Events List -->
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header">
        <h6 class="mb-0">
          <i class="fas fa-list me-2 text-success"></i>
          All Events (<?php echo count($allEvents); ?>)
        </h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table admin-table mb-0">
            <thead>
              <tr><th>Activity</th><th>Session</th><th>Sem</th><th>Start</th><th>Category</th><th>Actions</th></tr>
            </thead>
            <tbody>
              <?php foreach ($allEvents as $ev): ?>
              <tr>
                <td><?php echo htmlspecialchars(substr($ev['activity'], 0, 40)); ?>...</td>
                <td class="small"><?php echo htmlspecialchars($ev['session_year']); ?></td>
                <td class="text-center"><?php echo $ev['semester']; ?></td>
                <td class="small"><?php echo date('d M Y', strtotime($ev['start_date'])); ?></td>
                <td><span class="badge bg-success small"><?php echo htmlspecialchars($ev['category']); ?></span></td>
                <td>
                  <a href="?edit=<?php echo $ev['id']; ?>"
                     class="btn btn-sm btn-outline-primary me-1">
                    <i class="fas fa-pen"></i>
                  </a>
                  <a href="?delete=<?php echo $ev['id']; ?>"
                     class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Delete this event?')">
                    <i class="fas fa-trash"></i>
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<?php
echo '</div></div>';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
echo '</body></html>';
?>
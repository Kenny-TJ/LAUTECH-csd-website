<?php
require_once dirname(__FILE__) . '/admin_config.php';
require_once dirname(__FILE__) . '/../config/db.php';
requireLogin();
$adminPageTitle = 'Manage Staff';

$success = $error = '';

// DELETE
if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
    $id   = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM staff WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt) ? $success = 'Staff member deleted.' : $error = 'Delete failed.';
    mysqli_stmt_close($stmt);
}

// TOGGLE ACTIVE
if (isset($_GET['toggle']) && intval($_GET['toggle']) > 0) {
    $id   = intval($_GET['toggle']);
    $stmt = mysqli_prepare($conn, "UPDATE staff SET is_active = NOT is_active WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// ADD / EDIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = intval(isset($_POST['id']) ? $_POST['id'] : 0);
    $full_name   = trim(isset($_POST['full_name'])   ? $_POST['full_name']   : '');
    $title       = trim(isset($_POST['title'])       ? $_POST['title']       : '');
    $designation = trim(isset($_POST['designation']) ? $_POST['designation'] : '');
    $role        = trim(isset($_POST['role'])        ? $_POST['role']        : '');
    $email       = trim(isset($_POST['email'])       ? $_POST['email']       : '');
    $phone       = trim(isset($_POST['phone'])       ? $_POST['phone']       : '');
    $sort_order  = intval(isset($_POST['sort_order'])? $_POST['sort_order']  : 0);

    if (!$full_name || !$title || !$designation) {
        $error = 'Name, title and designation are required.';
    } else {
        if ($id > 0) {
            $stmt = mysqli_prepare($conn,
                "UPDATE staff SET full_name=?, title=?, designation=?, role=?,
                 email=?, phone=?, sort_order=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, 'ssssssi i',
                $full_name, $title, $designation, $role,
                $email, $phone, $sort_order, $id);
        } else {
            $stmt = mysqli_prepare($conn,
                "INSERT INTO staff (full_name, title, designation, role, email, phone, sort_order)
                 VALUES (?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'ssssssi',
                $full_name, $title, $designation, $role,
                $email, $phone, $sort_order);
        }
        mysqli_stmt_execute($stmt) ? $success = 'Staff saved.' : $error = 'Save failed.';
        mysqli_stmt_close($stmt);
    }
}

// Fetch editing item
$editing = null;
if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
    $id   = intval($_GET['edit']);
    $stmt = mysqli_prepare($conn, "SELECT * FROM staff WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $editing = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$allStaff = mysqli_fetch_all(
    mysqli_query($conn, "SELECT * FROM staff ORDER BY sort_order ASC"),
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
          <?php echo $editing ? 'Edit Staff Member' : 'Add Staff Member'; ?>
        </h6>
      </div>
      <div class="card-body">
        <form method="POST">
          <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?php echo $editing['id']; ?>">
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
            <select name="title" class="form-select" required>
              <?php foreach (array('Prof.','Dr.','Mr.','Mrs.','Miss','Engr.') as $t): ?>
                <option value="<?php echo $t; ?>"
                  <?php echo ($editing && $editing['title'] === $t) ? 'selected' : ''; ?>>
                  <?php echo $t; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="full_name" class="form-control" required
                   value="<?php echo $editing ? htmlspecialchars($editing['full_name']) : ''; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Designation <span class="text-danger">*</span></label>
            <select name="designation" class="form-select" required>
              <?php
              $designations = array('Professor','Associate Professor','Senior Lecturer',
                                    'Lecturer I','Lecturer II','Assistant Lecturer',
                                    'Graduate Assistant','Administrative Secretary','Technologist');
              foreach ($designations as $d):
              ?>
                <option value="<?php echo $d; ?>"
                  <?php echo ($editing && $editing['designation'] === $d) ? 'selected' : ''; ?>>
                  <?php echo $d; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Role / Position
              <span class="text-muted small">(optional)</span>
            </label>
            <input type="text" name="role" class="form-control"
                   placeholder="e.g. Head of Department"
                   value="<?php echo $editing ? htmlspecialchars($editing['role'] ?? '') : ''; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?php echo $editing ? htmlspecialchars($editing['email'] ?? '') : ''; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Phone</label>
            <input type="text" name="phone" class="form-control"
                   value="<?php echo $editing ? htmlspecialchars($editing['phone'] ?? '') : ''; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Display Order</label>
            <input type="number" name="sort_order" class="form-control" min="0"
                   value="<?php echo $editing ? intval($editing['sort_order']) : 0; ?>">
            <div class="form-text">Lower number = appears first.</div>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success flex-grow-1">
              <i class="fas fa-save me-1"></i><?php echo $editing ? 'Update' : 'Add Staff'; ?>
            </button>
            <?php if ($editing): ?>
              <a href="/lautech_csd/admin/manage-staff.php" class="btn btn-outline-secondary">Cancel</a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Staff List -->
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header">
        <h6 class="mb-0">
          <i class="fas fa-list me-2 text-success"></i>
          All Staff (<?php echo count($allStaff); ?>)
        </h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table admin-table mb-0">
            <thead>
              <tr><th>#</th><th>Name</th><th>Designation</th><th>Role</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
              <?php foreach ($allStaff as $m): ?>
              <tr>
                <td class="text-muted"><?php echo $m['sort_order']; ?></td>
                <td class="fw-semibold">
                  <?php echo htmlspecialchars($m['title'] . ' ' . $m['full_name']); ?>
                </td>
                <td class="small"><?php echo htmlspecialchars($m['designation']); ?></td>
                <td class="small text-muted">
                  <?php echo $m['role'] ? htmlspecialchars($m['role']) : '—'; ?>
                </td>
                <td>
                  <a href="?toggle=<?php echo $m['id']; ?>"
                     class="badge text-decoration-none <?php echo $m['is_active'] ? 'badge-active' : 'badge-inactive'; ?>">
                    <?php echo $m['is_active'] ? 'Active' : 'Inactive'; ?>
                  </a>
                </td>
                <td>
                  <a href="?edit=<?php echo $m['id']; ?>"
                     class="btn btn-sm btn-outline-primary me-1">
                    <i class="fas fa-pen"></i>
                  </a>
                  <a href="?delete=<?php echo $m['id']; ?>"
                     class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Delete this staff member?')">
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
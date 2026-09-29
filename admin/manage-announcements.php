<?php
require_once dirname(__FILE__) . '/admin_config.php';
require_once dirname(__FILE__) . '/../config/db.php';
requireLogin();
$adminPageTitle = 'Announcements';

$success = $error = '';

if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
    $id = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM announcements WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt) ? $success = 'Deleted.' : $error = 'Failed.';
    mysqli_stmt_close($stmt);
}

if (isset($_GET['toggle']) && intval($_GET['toggle']) > 0) {
    $id = intval($_GET['toggle']);
    $stmt = mysqli_prepare($conn, "UPDATE announcements SET is_active = NOT is_active WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = intval($_POST['id'] ?? 0);
    $message   = trim($_POST['message']   ?? '');
    $link_text = trim($_POST['link_text'] ?? '');
    $link_url  = trim($_POST['link_url']  ?? '');
    $expires   = trim($_POST['expires_at'] ?? '');
    $expires   = $expires ?: null;

    if (!$message) {
        $error = 'Message is required.';
    } else {
        if ($id > 0) {
            $stmt = mysqli_prepare($conn,
                "UPDATE announcements SET message=?, link_text=?, link_url=?, expires_at=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, 'ssssi', $message, $link_text, $link_url, $expires, $id);
        } else {
            $stmt = mysqli_prepare($conn,
                "INSERT INTO announcements (message, link_text, link_url, expires_at) VALUES (?,?,?,?)");
            mysqli_stmt_bind_param($stmt, 'ssss', $message, $link_text, $link_url, $expires);
        }
        mysqli_stmt_execute($stmt) ? $success = 'Announcement saved.' : $error = 'Save failed.';
        mysqli_stmt_close($stmt);
    }
}

$editing = null;
if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
    $id   = intval($_GET['edit']);
    $stmt = mysqli_prepare($conn, "SELECT * FROM announcements WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $editing = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$all = mysqli_fetch_all(
    mysqli_query($conn, "SELECT * FROM announcements ORDER BY created_at DESC"),
    MYSQLI_ASSOC
);

include dirname(__FILE__) . '/admin_layout.php';
?>

<?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
<?php if ($error):   ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

<div class="row g-4">
  <div class="col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header">
        <h6 class="mb-0"><i class="fas fa-<?php echo $editing ? 'pen' : 'plus'; ?> me-2 text-success"></i>
          <?php echo $editing ? 'Edit' : 'New'; ?> Announcement</h6>
      </div>
      <div class="card-body">
        <form method="POST">
          <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?php echo $editing['id']; ?>">
          <?php endif; ?>
          <div class="mb-3">
            <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
            <textarea name="message" class="form-control" rows="3" maxlength="300" required><?php
              echo $editing ? htmlspecialchars($editing['message']) : ''; ?></textarea>
            <div class="form-text">Max 300 characters.</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Link Text <span class="text-muted small">(optional)</span></label>
            <input type="text" name="link_text" class="form-control" placeholder="e.g. View Timetable"
                   value="<?php echo $editing ? htmlspecialchars($editing['link_text']) : ''; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Link URL <span class="text-muted small">(optional)</span></label>
            <input type="text" name="link_url" class="form-control" placeholder="/lautech_csd/calendar.php"
                   value="<?php echo $editing ? htmlspecialchars($editing['link_url']) : ''; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Expires On <span class="text-muted small">(optional)</span></label>
            <input type="date" name="expires_at" class="form-control"
                   value="<?php echo $editing ? htmlspecialchars($editing['expires_at'] ?? '') : ''; ?>">
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success flex-grow-1">
              <i class="fas fa-save me-1"></i><?php echo $editing ? 'Update' : 'Add'; ?>
            </button>
            <?php if ($editing): ?>
              <a href="/lautech_csd/admin/manage-announcements.php" class="btn btn-outline-secondary">Cancel</a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header">
        <h6 class="mb-0"><i class="fas fa-list me-2 text-success"></i>All Announcements</h6>
      </div>
      <div class="card-body p-0">
        <table class="table admin-table mb-0">
          <thead><tr><th>Message</th><th>Expires</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
            <?php foreach ($all as $ann): ?>
            <tr>
              <td><?php echo htmlspecialchars(substr($ann['message'], 0, 60)) . '...'; ?></td>
              <td class="text-muted small"><?php echo $ann['expires_at'] ? date('d M Y', strtotime($ann['expires_at'])) : 'Never'; ?></td>
              <td>
                <a href="?toggle=<?php echo $ann['id']; ?>"
                   class="badge text-decoration-none <?php echo $ann['is_active'] ? 'badge-active' : 'badge-inactive'; ?>">
                  <?php echo $ann['is_active'] ? 'Active' : 'Inactive'; ?>
                </a>
              </td>
              <td>
                <a href="?edit=<?php echo $ann['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-pen"></i></a>
                <a href="?delete=<?php echo $ann['id']; ?>" class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Delete this announcement?')"><i class="fas fa-trash"></i></a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php
echo '</div></div>';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
echo '</body></html>';
?>
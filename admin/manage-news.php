<?php
require_once dirname(__FILE__) . '/admin_config.php';
require_once dirname(__FILE__) . '/../config/db.php';
requireLogin();
$adminPageTitle = 'News & Notices';

$success = '';
$error   = '';

// DELETE
if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
    $id   = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM news WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt) ? $success = 'News item deleted.' : $error = 'Delete failed.';
    mysqli_stmt_close($stmt);
}

// TOGGLE PUBLISHED
if (isset($_GET['toggle']) && intval($_GET['toggle']) > 0) {
    $id   = intval($_GET['toggle']);
    $stmt = mysqli_prepare($conn, "UPDATE news SET is_published = NOT is_published WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// ADD / EDIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = intval(isset($_POST['id']) ? $_POST['id'] : 0);
    $title    = trim($_POST['title']    ?? '');
    $body     = trim($_POST['body']     ?? '');
    $category = trim($_POST['category'] ?? 'General');
    $author   = trim($_POST['author']   ?? 'Department');

    if (!$title || !$body) {
        $error = 'Title and body are required.';
    } else {
        if ($id > 0) {
            $stmt = mysqli_prepare($conn,
                "UPDATE news SET title=?, body=?, category=?, author=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, 'ssssi', $title, $body, $category, $author, $id);
        } else {
            $stmt = mysqli_prepare($conn,
                "INSERT INTO news (title, body, category, author) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'ssss', $title, $body, $category, $author);
        }
        mysqli_stmt_execute($stmt) ? $success = 'News saved successfully.' : $error = 'Save failed.';
        mysqli_stmt_close($stmt);
    }
}

// Fetch editing item
$editing = null;
if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
    $id   = intval($_GET['edit']);
    $stmt = mysqli_prepare($conn, "SELECT * FROM news WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $editing = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

// Fetch all news
$allNews = mysqli_fetch_all(
    mysqli_query($conn, "SELECT * FROM news ORDER BY published_at DESC"),
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
          <?php echo $editing ? 'Edit News Item' : 'Add News Item'; ?>
        </h6>
      </div>
      <div class="card-body">
        <form method="POST">
          <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?php echo $editing['id']; ?>">
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control"
                   value="<?php echo $editing ? htmlspecialchars($editing['title']) : ''; ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Body <span class="text-danger">*</span></label>
            <textarea name="body" class="form-control" rows="6" required><?php
              echo $editing ? htmlspecialchars($editing['body']) : ''; ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Category</label>
            <select name="category" class="form-select">
              <?php foreach (array('General','Results','Events','Notice','Announcement') as $cat): ?>
                <option value="<?php echo $cat; ?>"
                  <?php echo ($editing && $editing['category'] === $cat) ? 'selected' : ''; ?>>
                  <?php echo $cat; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Author</label>
            <input type="text" name="author" class="form-control"
                   value="<?php echo $editing ? htmlspecialchars($editing['author']) : 'Department'; ?>">
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success flex-grow-1">
              <i class="fas fa-save me-1"></i><?php echo $editing ? 'Update' : 'Publish'; ?>
            </button>
            <?php if ($editing): ?>
              <a href="/lautech_csd/admin/manage-news.php" class="btn btn-outline-secondary">Cancel</a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- List -->
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header">
        <h6 class="mb-0"><i class="fas fa-list me-2 text-success"></i>All News Items (<?php echo count($allNews); ?>)</h6>
      </div>
      <div class="card-body p-0">
        <table class="table admin-table mb-0">
          <thead>
            <tr><th>Title</th><th>Category</th><th>Status</th><th>Date</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php foreach ($allNews as $item): ?>
            <tr>
              <td><?php echo htmlspecialchars(substr($item['title'], 0, 40)) . (strlen($item['title']) > 40 ? '...' : ''); ?></td>
              <td><span class="badge bg-success"><?php echo htmlspecialchars($item['category']); ?></span></td>
              <td>
                <a href="?toggle=<?php echo $item['id']; ?>"
                   class="badge text-decoration-none <?php echo $item['is_published'] ? 'badge-active' : 'badge-inactive'; ?>">
                  <?php echo $item['is_published'] ? 'Published' : 'Hidden'; ?>
                </a>
              </td>
              <td class="text-muted"><?php echo date('d M Y', strtotime($item['published_at'])); ?></td>
              <td>
                <a href="?edit=<?php echo $item['id']; ?>" class="btn btn-xs btn-outline-primary btn-sm me-1">
                  <i class="fas fa-pen"></i>
                </a>
                <a href="?delete=<?php echo $item['id']; ?>"
                   class="btn btn-xs btn-outline-danger btn-sm"
                   onclick="return confirm('Delete this news item?')">
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

<?php
echo '</div></div>';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
echo '</body></html>';
?>
<?php
require_once dirname(__FILE__) . '/admin_config.php';
require_once dirname(__FILE__) . '/../config/db.php';
requireLogin();
$adminPageTitle = 'Contact Messages';

// Mark as read
if (isset($_GET['read']) && intval($_GET['read']) > 0) {
    $id   = intval($_GET['read']);
    $stmt = mysqli_prepare($conn, "UPDATE contact_messages SET is_read = 1 WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Delete
if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
    $id   = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM contact_messages WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

$messages = mysqli_fetch_all(
    mysqli_query($conn,
        "SELECT * FROM contact_messages ORDER BY created_at DESC"),
    MYSQLI_ASSOC
);

$unread = count(array_filter($messages, function($m) { return !$m['is_read']; }));

include dirname(__FILE__) . '/admin_layout.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <?php if ($unread > 0): ?>
      <span class="badge bg-danger me-2"><?php echo $unread; ?> unread</span>
    <?php endif; ?>
    <span class="text-muted small"><?php echo count($messages); ?> total messages</span>
  </div>
</div>

<?php if (empty($messages)): ?>
  <div class="text-center py-5">
    <i class="fas fa-envelope-open fa-3x text-muted mb-3"></i>
    <p class="text-muted">No contact messages yet.</p>
  </div>
<?php else: ?>
  <?php foreach ($messages as $msg): ?>
  <div class="card shadow-sm mb-3 <?php echo !$msg['is_read'] ? 'border-start border-success border-3' : ''; ?>">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
          <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($msg['subject']); ?></h6>
          <div class="text-muted small mt-1">
            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($msg['name']); ?>
            &nbsp;|&nbsp;
            <i class="fas fa-envelope me-1"></i>
            <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>">
              <?php echo htmlspecialchars($msg['email']); ?>
            </a>
            &nbsp;|&nbsp;
            <i class="fas fa-clock me-1"></i>
            <?php echo date('d M Y, g:i A', strtotime($msg['created_at'])); ?>
          </div>
        </div>
        <div class="d-flex gap-2">
          <?php if (!$msg['is_read']): ?>
            <a href="?read=<?php echo $msg['id']; ?>" class="btn btn-sm btn-outline-success">
              <i class="fas fa-check me-1"></i>Mark Read
            </a>
          <?php else: ?>
            <span class="badge badge-active align-self-center">Read</span>
          <?php endif; ?>
          <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>?subject=Re: <?php echo urlencode($msg['subject']); ?>"
             class="btn btn-sm btn-outline-primary">
            <i class="fas fa-reply me-1"></i>Reply
          </a>
          <a href="?delete=<?php echo $msg['id']; ?>"
             class="btn btn-sm btn-outline-danger"
             onclick="return confirm('Delete this message?')">
            <i class="fas fa-trash"></i>
          </a>
        </div>
      </div>
      <div class="mt-3 p-3 bg-light rounded small text-muted">
        <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
<?php endif; ?>

<?php
echo '</div></div>';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
echo '</body></html>';
?>
<?php
// admin/index.php — Login page
require_once dirname(__FILE__) . '/../admin/admin_config.php';
require_once dirname(__FILE__) . '/../config/db.php';

// If already logged in, go straight to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: /lautech_csd/admin/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(isset($_POST['username']) ? $_POST['username'] : '');
    $password = trim(isset($_POST['password']) ? $_POST['password'] : '');

    if (!$username || !$password) {
        $error = 'Please enter both username and password.';
    } elseif ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD)) {
        // Valid login
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username']  = $username;
        $_SESSION['admin_login_time'] = time();
        header('Location: /lautech_csd/admin/dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — CS Department</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background: #006400; min-height: 100vh; display: flex; align-items: center; }
    .login-card { border-radius: 12px; overflow: hidden; }
    .login-header { background: #006400; color: white; padding: 2rem; text-align: center; }
    .login-header .gold { color: #FFD700; }
  </style>
</head>
<body>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
      <div class="card shadow-lg login-card">

        <div class="login-header">
          <i class="fas fa-shield-halved fa-3x mb-2" style="color:#FFD700;"></i>
          <h5 class="mb-0">Admin Panel</h5>
          <div class="gold small">CS Department, LAUTECH</div>
        </div>

        <div class="card-body p-4">
          <?php if ($error): ?>
            <div class="alert alert-danger py-2 small">
              <i class="fas fa-circle-exclamation me-1"></i><?php echo $error; ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="">
            <div class="mb-3">
              <label class="form-label fw-semibold">Username</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="username" class="form-control"
                       placeholder="Admin username" required autofocus
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
              </div>
            </div>
            <div class="mb-4">
              <label class="form-label fw-semibold">Password</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control"
                       placeholder="Admin password" required>
              </div>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-right-to-bracket me-2"></i>Log In
              </button>
            </div>
          </form>
        </div>

        <div class="card-footer text-center text-muted small py-2">
          <a href="/lautech_csd/index.php">
            <i class="fas fa-arrow-left me-1"></i>Back to Website
          </a>
        </div>

      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
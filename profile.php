<?php
// profile.php
$pageTitle = 'File Submission — CS Department, LAUTECH';
require_once 'config/db.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$error = isset($_GET['error']) ? $_GET['error'] : '';
$errorMessages = array(
    'empty_fields'     => 'Please fill in all required fields.',
    'invalid_matric'   => 'Matric number format is invalid. Expected format: e.g. CSE/2021/001',
    'invalid_email'    => 'Please enter a valid email address.',
    'no_file'          => 'Please select a file to upload.',
    'file_too_large'   => 'File size must not exceed 2MB.',
    'invalid_type'     => 'Only PDF, JPG, and PNG files are allowed.',
    'upload_failed'    => 'File upload failed. Please try again.',
    'duplicate_matric' => 'This matric number has already made a submission.',
    'db_error'         => 'A database error occurred. Please try again.',
);

// Check submission status via matric number
$checkResult = null;
if (isset($_GET['check']) && !empty($_GET['check'])) {
    $checkMatric = mysqli_real_escape_string($conn, strtoupper(trim($_GET['check'])));
    $stmt = mysqli_prepare($conn,
        "SELECT s.full_name, s.level, s.session_year, s.submitted_at
         FROM students s WHERE s.matric_no = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $checkMatric);
    mysqli_stmt_execute($stmt);
    $checkResult = mysqli_stmt_get_result($stmt);
    $checkData   = mysqli_fetch_assoc($checkResult);
}
?>

<div class="page-banner text-white d-flex align-items-center">
  <div class="container">
    <h1 class="fw-bold">File Submission</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/lautech_csd/index.php" class="text-warning">Home</a></li>
        <li class="breadcrumb-item active text-white">File Submission</li>
      </ol>
    </nav>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-7">

        <div class="alert alert-info d-flex gap-2 mb-4">
          <i class="fas fa-circle-info mt-1"></i>
          <div>
            <strong>Before you submit:</strong> Ensure your file is in
            <strong>PDF, JPG, or PNG</strong> format and does not exceed
            <strong>2MB</strong>. Each matric number can only be submitted once.
          </div>
        </div>

        <?php if ($error && isset($errorMessages[$error])): ?>
          <div class="alert alert-danger d-flex gap-2">
            <i class="fas fa-circle-exclamation mt-1"></i>
            <div><?php echo $errorMessages[$error]; ?></div>
          </div>
        <?php endif; ?>

        <!-- Submission Form -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0">
              <i class="fas fa-file-arrow-up me-2"></i>
              Student Profiling &amp; File Submission
            </h5>
          </div>
          <div class="card-body p-4">
            <form action="/lautech_csd/submit_handler.php"
                  method="POST"
                  enctype="multipart/form-data"
                  novalidate>

              <div class="mb-3">
                <label for="matric_no" class="form-label fw-semibold">
                  Matric Number <span class="text-danger">*</span>
                </label>
                <input type="text" id="matric_no" name="matric_no"
                       class="form-control text-uppercase"
                       placeholder="e.g. CSE/2021/001"
                       maxlength="20" required>
              </div>

              <div class="mb-3">
                <label for="full_name" class="form-label fw-semibold">
                  Full Name <span class="text-danger">*</span>
                </label>
                <input type="text" id="full_name" name="full_name"
                       class="form-control"
                       placeholder="Surname First — e.g. Adeyemi Kehinde Blessing"
                       maxlength="150" required>
              </div>

              <div class="row">
                <div class="col-sm-6 mb-3">
                  <label for="level" class="form-label fw-semibold">
                    Level <span class="text-danger">*</span>
                  </label>
                  <select id="level" name="level" class="form-select" required>
                    <option value="">— Select Level —</option>
                    <option value="100">100 Level</option>
                    <option value="200">200 Level</option>
                    <option value="300">300 Level</option>
                    <option value="400">400 Level</option>
                    <option value="500">500 Level</option>
                  </select>
                </div>
                <div class="col-sm-6 mb-3">
                  <label for="session_year" class="form-label fw-semibold">
                    Academic Session <span class="text-danger">*</span>
                  </label>
                  <select id="session_year" name="session_year" class="form-select" required>
                    <option value="">— Select Session —</option>
                    <option value="2024/2025">2024/2025</option>
                    <option value="2023/2024">2023/2024</option>
                    <option value="2022/2023">2022/2023</option>
                  </select>
                </div>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">
                  Email Address <span class="text-danger">*</span>
                </label>
                <input type="email" id="email" name="email"
                       class="form-control"
                       placeholder="yourname@example.com"
                       maxlength="150" required>
              </div>

              <div class="mb-3">
                <label for="phone" class="form-label fw-semibold">Phone Number</label>
                <input type="tel" id="phone" name="phone"
                       class="form-control"
                       placeholder="e.g. 08012345678"
                       maxlength="20">
              </div>

              <div class="mb-4">
                <label for="student_file" class="form-label fw-semibold">
                  Upload File <span class="text-danger">*</span>
                </label>
                <input type="file" id="student_file" name="student_file"
                       class="form-control"
                       accept=".pdf,.jpg,.jpeg,.png" required>
                <div class="form-text">Accepted: PDF, JPG, PNG. Maximum size: 2MB.</div>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg">
                  <i class="fas fa-paper-plane me-2"></i>Submit
                </button>
              </div>

            </form>
          </div>
        </div>

        <!-- Check Submission Status -->
        <div class="card shadow-sm">
          <div class="card-header bg-light">
            <h6 class="mb-0">
              <i class="fas fa-magnifying-glass me-2 text-primary"></i>
              Check Your Submission Status
            </h6>
          </div>
          <div class="card-body">
            <form method="GET" action="/lautech_csd/profile.php" class="d-flex gap-2">
              <input type="text" name="check" class="form-control text-uppercase"
                     placeholder="Enter your Matric Number"
                     value="<?php echo isset($checkMatric) ? htmlspecialchars($checkMatric) : ''; ?>">
              <button type="submit" class="btn btn-outline-success flex-shrink-0">
                Check
              </button>
            </form>

            <?php if (isset($_GET['check'])): ?>
              <?php if (!empty($checkData)): ?>
                <div class="alert alert-success mt-3 mb-0">
                  <i class="fas fa-circle-check me-2"></i>
                  <strong>Submission found.</strong><br>
                  <strong>Name:</strong> <?php echo htmlspecialchars($checkData['full_name']); ?><br>
                  <strong>Level:</strong> <?php echo htmlspecialchars($checkData['level']); ?> Level<br>
                  <strong>Session:</strong> <?php echo htmlspecialchars($checkData['session_year']); ?><br>
                  <strong>Date:</strong> <?php echo date('d M Y, g:i A', strtotime($checkData['submitted_at'])); ?>
                </div>
              <?php else: ?>
                <div class="alert alert-danger mt-3 mb-0">
                  <i class="fas fa-circle-xmark me-2"></i>
                  No submission record found for that matric number.
                </div>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>

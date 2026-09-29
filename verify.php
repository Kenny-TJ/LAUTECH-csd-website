<?php
// verify.php
$pageTitle = 'Submission Confirmed — CS Department, LAUTECH';
require_once 'config/db.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$studentId = intval(isset($_GET['id']) ? $_GET['id'] : 0);
if (!$studentId) {
    header('Location: /lautech_csd/index.php');
    exit;
}

$stmt = mysqli_prepare($conn,
    "SELECT s.*, sub.file_name, sub.file_type, sub.file_size, sub.submitted_at AS file_submitted
     FROM students s
     JOIN submissions sub ON sub.student_id = s.id
     WHERE s.id = ?
     ORDER BY sub.submitted_at DESC
     LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $studentId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data   = mysqli_fetch_assoc($result);

if (!$data) {
    header('Location: /lautech_csd/index.php');
    exit;
}

$sizeKB = round($data['file_size'] / 1024, 1);
?>

<div class="page-banner text-white d-flex align-items-center">
  <div class="container">
    <h1 class="fw-bold">Submission Confirmed</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/lautech_csd/index.php" class="text-warning">Home</a></li>
        <li class="breadcrumb-item active text-white">Confirmed</li>
      </ol>
    </nav>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-7">

        <div class="text-center mb-4">
          <i class="fas fa-circle-check fa-4x text-success mb-3"></i>
          <h2 class="fw-bold text-success">Submission Successful!</h2>
          <p class="text-muted">
            Your file has been received and recorded. Keep the details below for your reference.
          </p>
        </div>

        <div class="card shadow-sm mb-4">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Submission Details</h5>
          </div>
          <div class="card-body p-0">
            <table class="table table-bordered mb-0">
              <tbody>
                <tr>
                  <th class="bg-light" style="width:40%">Matric Number</th>
                  <td class="fw-bold text-primary"><?php echo htmlspecialchars($data['matric_no']); ?></td>
                </tr>
                <tr>
                  <th class="bg-light">Full Name</th>
                  <td><?php echo htmlspecialchars($data['full_name']); ?></td>
                </tr>
                <tr>
                  <th class="bg-light">Level</th>
                  <td><?php echo htmlspecialchars($data['level']); ?> Level</td>
                </tr>
                <tr>
                  <th class="bg-light">Academic Session</th>
                  <td><?php echo htmlspecialchars($data['session_year']); ?></td>
                </tr>
                <tr>
                  <th class="bg-light">Email</th>
                  <td><?php echo htmlspecialchars($data['email']); ?></td>
                </tr>
                <tr>
                  <th class="bg-light">File Submitted</th>
                  <td>
                    <?php echo htmlspecialchars($data['file_name']); ?>
                    <span class="text-muted small">(<?php echo $sizeKB; ?> KB)</span>
                  </td>
                </tr>
                <tr>
                  <th class="bg-light">File Type</th>
                  <td><span class="badge bg-secondary text-uppercase"><?php echo htmlspecialchars($data['file_type']); ?></span></td>
                </tr>
                <tr>
                  <th class="bg-light">Date &amp; Time</th>
                  <td><?php echo date('d M Y, g:i A', strtotime($data['submitted_at'])); ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="alert alert-warning d-flex gap-2">
          <i class="fas fa-triangle-exclamation mt-1"></i>
          <div>
            Please take a screenshot of this page or note your matric number
            <strong><?php echo htmlspecialchars($data['matric_no']); ?></strong>
            as proof of submission.
          </div>
        </div>

        <div class="d-flex gap-3 justify-content-center">
          <a href="/lautech_csd/index.php" class="btn btn-outline-success">
            <i class="fas fa-house me-1"></i>Back to Home
          </a>
          <button onclick="window.print()" class="btn btn-success">
            <i class="fas fa-print me-1"></i>Print This Page
          </button>
        </div>

      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>

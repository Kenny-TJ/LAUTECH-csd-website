<?php
$pageTitle = 'Contact Us — CS Department, LAUTECH';
require_once 'config/db.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim(isset($_POST['name'])    ? $_POST['name']    : '');
    $email   = trim(isset($_POST['email'])   ? $_POST['email']   : '');
    $subject = trim(isset($_POST['subject']) ? $_POST['subject'] : '');
    $message = trim(isset($_POST['message']) ? $_POST['message'] : '');

    if (!$name || !$email || !$subject || !$message) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO contact_messages (name, email, subject, message)
             VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $subject, $message);
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Your message has been sent. We will get back to you shortly.';
        } else {
            $error = 'Failed to send message. Please try again.';
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<div class="page-banner text-white d-flex align-items-center">
  <div class="container">
    <h1 class="fw-bold">Contact Us</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/lautech_csd/index.php" class="text-warning">Home</a></li>
        <li class="breadcrumb-item active text-white">Contact</li>
      </ol>
    </nav>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row g-5">

      <!-- Contact Info -->
      <div class="col-lg-4">
        <h2 class="section-title mb-4">Get In Touch</h2>
        <p class="text-muted mb-4">
          We welcome enquiries from prospective students, current students,
          alumni, industry partners, and the general public.
        </p>

        <div class="d-flex gap-3 mb-3">
          <div class="flex-shrink-0">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width:42px;height:42px;background:#e8f5e9;">
              <i class="fas fa-location-dot text-success"></i>
            </div>
          </div>
          <div>
            <div class="fw-semibold">Address</div>
            <div class="text-muted small">
              Department of Computer Science &amp; Engineering,<br>
              Faculty of Engineering &amp; Technology,<br>
              Ladoke Akintola University of Technology,<br>
              Ogbomoso, Oyo State, Nigeria.
            </div>
          </div>
        </div>

        <div class="d-flex gap-3 mb-3">
          <div class="flex-shrink-0">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width:42px;height:42px;background:#e8f5e9;">
              <i class="fas fa-envelope text-success"></i>
            </div>
          </div>
          <div>
            <div class="fw-semibold">Email</div>
            <div class="text-muted small">
              <a href="mailto:cse@lautech.edu.ng">cse@lautech.edu.ng</a><br>
              <a href="mailto:hod.cse@lautech.edu.ng">hod.cse@lautech.edu.ng</a>
            </div>
          </div>
        </div>

        <div class="d-flex gap-3 mb-4">
          <div class="flex-shrink-0">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width:42px;height:42px;background:#e8f5e9;">
              <i class="fas fa-clock text-success"></i>
            </div>
          </div>
          <div>
            <div class="fw-semibold">Office Hours</div>
            <div class="text-muted small">
              Monday – Friday: 8:00 AM – 4:00 PM<br>
              Saturday – Sunday: Closed
            </div>
          </div>
        </div>

        <!-- Quick Contact Cards -->
        <div class="card shadow-sm mb-3">
          <div class="card-body py-2">
            <div class="fw-semibold small">HOD's Office</div>
            <div class="text-muted small">Dr. Mohammed Ibrahim</div>
            <a href="mailto:hod.cse@lautech.edu.ng" class="small text-success">
              hod.cse@lautech.edu.ng
            </a>
          </div>
        </div>
        <div class="card shadow-sm mb-3">
          <div class="card-body py-2">
            <div class="fw-semibold small">Examinations Officer</div>
            <div class="text-muted small">Dr. Adekunle Bello</div>
            <a href="mailto:abello.cse@lautech.edu.ng" class="small text-success">
              abello.cse@lautech.edu.ng
            </a>
          </div>
        </div>
        <div class="card shadow-sm">
          <div class="card-body py-2">
            <div class="fw-semibold small">Departmental Secretary</div>
            <div class="text-muted small">Mrs. Grace Okonkwo</div>
            <a href="mailto:gokonkwo.cse@lautech.edu.ng" class="small text-success">
              gokonkwo.cse@lautech.edu.ng
            </a>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="col-lg-8">
        <div class="card shadow-sm">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0">
              <i class="fas fa-paper-plane me-2"></i>Send Us a Message
            </h5>
          </div>
          <div class="card-body p-4">

            <?php if ($success): ?>
              <div class="alert alert-success d-flex gap-2">
                <i class="fas fa-circle-check mt-1"></i>
                <div><?php echo $success; ?></div>
              </div>
            <?php endif; ?>
            <?php if ($error): ?>
              <div class="alert alert-danger d-flex gap-2">
                <i class="fas fa-circle-exclamation mt-1"></i>
                <div><?php echo $error; ?></div>
              </div>
            <?php endif; ?>

            <form method="POST" novalidate>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold">
                    Full Name <span class="text-danger">*</span>
                  </label>
                  <input type="text" name="name" class="form-control"
                         placeholder="Your full name" maxlength="150" required
                         value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">
                    Email Address <span class="text-danger">*</span>
                  </label>
                  <input type="email" name="email" class="form-control"
                         placeholder="yourname@example.com" maxlength="150" required
                         value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold">
                    Subject <span class="text-danger">*</span>
                  </label>
                  <select name="subject" class="form-select" required>
                    <option value="">— Select a subject —</option>
                    <?php
                    $subjects = array(
                        'General Enquiry',
                        'Admission Information',
                        'Course Information',
                        'Industrial Training',
                        'Final Year Project',
                        'Result / Examination',
                        'Staff Matter',
                        'Research Collaboration',
                        'Industry Partnership',
                        'Other',
                    );
                    foreach ($subjects as $sub):
                        $sel = (isset($_POST['subject']) && $_POST['subject'] === $sub) ? 'selected' : '';
                    ?>
                      <option value="<?php echo $sub; ?>" <?php echo $sel; ?>>
                        <?php echo $sub; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold">
                    Message <span class="text-danger">*</span>
                  </label>
                  <textarea name="message" class="form-control" rows="6"
                            placeholder="Type your message here..." required><?php
                    echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '';
                  ?></textarea>
                </div>
                <div class="col-12">
                  <button type="submit" class="btn btn-success btn-lg px-5">
                    <i class="fas fa-paper-plane me-2"></i>Send Message
                  </button>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
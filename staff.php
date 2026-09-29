<?php
// staff.php
$pageTitle = 'Staff — CS Department, LAUTECH';
require_once 'config/db.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$result    = mysqli_query($conn,
    "SELECT * FROM staff WHERE is_active = 1 ORDER BY sort_order ASC");
$staffList = mysqli_fetch_all($result, MYSQLI_ASSOC);
$designations = array_unique(array_column($staffList, 'designation'));
?>

<div class="page-banner text-white d-flex align-items-center">
  <div class="container">
    <h1 class="fw-bold">Staff Directory</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/lautech_csd/index.php" class="text-warning">Home</a></li>
        <li class="breadcrumb-item active text-white">Staff</li>
      </ol>
    </nav>
  </div>
</div>

<section class="py-5">
  <div class="container">

    <p class="text-muted mb-3">
      Meet the dedicated academic and administrative team of the Department of
      Computer Science &amp; Engineering, LAUTECH.
      <span class="badge bg-success ms-2"><?php echo count($staffList); ?> Staff Members</span>
    </p>

    <!-- Filter Buttons -->
    <div class="d-flex gap-2 flex-wrap mb-4">
      <button class="btn btn-sm btn-success staff-filter active" data-filter="all">
        All Staff
      </button>
      <?php foreach ($designations as $d): ?>
        <button class="btn btn-sm btn-outline-success staff-filter"
                data-filter="<?php echo htmlspecialchars($d); ?>">
          <?php echo htmlspecialchars($d); ?>
        </button>
      <?php endforeach; ?>
    </div>

    <div class="row g-4">
      <?php foreach ($staffList as $member): ?>
      <div class="col-sm-6 col-lg-4 staff-item"
           data-designation="<?php echo htmlspecialchars($member['designation']); ?>">
        <div class="card staff-card h-100 shadow-sm text-center">

          <div class="staff-photo-wrap pt-4">
            <img src="/lautech_csd/assets/images/staff/<?php echo htmlspecialchars($member['photo']); ?>"
                 alt="<?php echo htmlspecialchars($member['full_name']); ?>"
                 class="staff-photo rounded-circle"
                 onerror="this.src='https://placehold.co/120x120/006400/FFD700?text=<?php echo urlencode(substr($member['title'], 0, 1)); ?>'">
          </div>

          <div class="card-body">
            <h5 class="card-title mb-0">
              <?php echo htmlspecialchars($member['title'] . ' ' . $member['full_name']); ?>
            </h5>
            <p class="text-muted small mb-1"><?php echo htmlspecialchars($member['designation']); ?></p>

            <?php if ($member['role']): ?>
              <span class="badge bg-success mb-2"><?php echo htmlspecialchars($member['role']); ?></span>
            <?php endif; ?>

            <?php if ($member['email']): ?>
              <p class="small mt-2 mb-0">
                <i class="fas fa-envelope text-primary me-1"></i>
                <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>">
                  <?php echo htmlspecialchars($member['email']); ?>
                </a>
              </p>
            <?php endif; ?>
          </div>

        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<script>
document.querySelectorAll('.staff-filter').forEach(function(btn) {
  btn.addEventListener('click', function() {
    document.querySelectorAll('.staff-filter').forEach(function(b) {
      b.classList.remove('active', 'btn-success');
      b.classList.add('btn-outline-success');
    });
    this.classList.add('active', 'btn-success');
    this.classList.remove('btn-outline-success');

    var filter = this.getAttribute('data-filter');
    document.querySelectorAll('.staff-item').forEach(function(item) {
      item.style.display =
        (filter === 'all' || item.getAttribute('data-designation') === filter)
          ? '' : 'none';
    });
  });
});
</script>

<?php require_once 'includes/footer.php'; ?>

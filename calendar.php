<?php
// calendar.php
$pageTitle = 'Academic Calendar — CS Department, LAUTECH';
require_once 'config/db.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$sessionsResult = mysqli_query($conn,
    "SELECT DISTINCT session_year FROM academic_calendar ORDER BY session_year DESC");
$sessions = mysqli_fetch_all($sessionsResult, MYSQLI_ASSOC);

$activeSession = !empty($sessions) ? $sessions[0]['session_year'] : '2024/2025';
if (isset($_GET['session']) && !empty($_GET['session'])) {
    $activeSession = mysqli_real_escape_string($conn, $_GET['session']);
}

$result = mysqli_query($conn,
    "SELECT * FROM academic_calendar
     WHERE session_year = '$activeSession'
     ORDER BY semester ASC, start_date ASC");

$calendar = array();
$today    = date('Y-m-d');
while ($row = mysqli_fetch_assoc($result)) {
    $calendar[$row['semester']][] = $row;
}

$categoryColours = array(
    'Registration' => 'bg-primary',
    'Academic'     => 'bg-success',
    'Examination'  => 'bg-danger',
    'Holiday'      => 'bg-warning text-dark',
    'General'      => 'bg-secondary',
);
$semesterNames = array(1 => 'First Semester', 2 => 'Second Semester');
?>

<div class="page-banner text-white d-flex align-items-center">
  <div class="container">
    <h1 class="fw-bold">Academic Calendar</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/lautech_csd/index.php" class="text-warning">Home</a></li>
        <li class="breadcrumb-item active text-white">Academic Calendar</li>
      </ol>
    </nav>
  </div>
</div>

<section class="py-5">
  <div class="container">

    <!-- Session Selector -->
    <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
      <label class="fw-bold mb-0">Academic Session:</label>
      <div class="d-flex gap-2 flex-wrap">
        <?php foreach ($sessions as $s): ?>
          <a href="?session=<?php echo urlencode($s['session_year']); ?>"
             class="btn btn-sm <?php echo ($s['session_year'] === $activeSession) ? 'btn-success' : 'btn-outline-success'; ?>">
            <?php echo htmlspecialchars($s['session_year']); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Legend -->
    <div class="d-flex gap-2 flex-wrap mb-4">
      <span class="badge bg-primary px-3 py-2">Registration</span>
      <span class="badge bg-success px-3 py-2">Academic</span>
      <span class="badge bg-danger px-3 py-2">Examination</span>
      <span class="badge bg-warning text-dark px-3 py-2">Holiday</span>
    </div>

    <?php foreach ($calendar as $semester => $events): ?>

    <h4 class="mt-4 mb-3 text-primary border-bottom pb-2">
      <i class="fas fa-calendar me-2"></i>
      <?php echo isset($semesterNames[$semester]) ? $semesterNames[$semester] : "Semester $semester"; ?>
      — <?php echo htmlspecialchars($activeSession); ?>
    </h4>

    <div class="table-responsive mb-5">
      <table class="table table-hover align-middle">
        <thead class="table-success">
          <tr>
            <th style="width:140px">Start Date</th>
            <th style="width:140px">End Date</th>
            <th>Activity</th>
            <th style="width:130px" class="text-center">Category</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $highlightDone = false;
          foreach ($events as $event):
            $colour    = isset($categoryColours[$event['category']]) ? $categoryColours[$event['category']] : 'bg-secondary';
            $start     = date('d M Y', strtotime($event['start_date']));
            $end       = $event['end_date'] ? date('d M Y', strtotime($event['end_date'])) : '—';
            $isUpcoming = (!$highlightDone && $event['start_date'] >= $today);
            if ($isUpcoming) { $highlightDone = true; }
          ?>
          <tr <?php echo $isUpcoming ? 'class="table-warning fw-semibold"' : ''; ?>>
            <td>
              <?php echo $start; ?>
              <?php if ($isUpcoming): ?>
                <span class="badge bg-warning text-dark ms-1" style="font-size:0.7rem;">Next</span>
              <?php endif; ?>
            </td>
            <td><?php echo $end; ?></td>
            <td><?php echo htmlspecialchars($event['activity']); ?></td>
            <td class="text-center">
              <span class="badge <?php echo $colour; ?>">
                <?php echo htmlspecialchars($event['category']); ?>
              </span>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php endforeach; ?>

  </div>
</section>

<?php require_once 'includes/footer.php'; ?>

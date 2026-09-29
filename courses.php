<?php
// courses.php
$pageTitle = 'Courses — CS Department, LAUTECH';
require_once 'config/db.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$sql    = "SELECT * FROM courses ORDER BY level ASC, semester ASC, code ASC";
$result = mysqli_query($conn, $sql);

$courses = array();
while ($row = mysqli_fetch_assoc($result)) {
    $courses[$row['level']][$row['semester']][] = $row;
}
$levels     = array_keys($courses);
$levelNames = array(100=>'100 Level',200=>'200 Level',300=>'300 Level',
                    400=>'400 Level',500=>'500 Level');
?>

<div class="page-banner text-white d-flex align-items-center">
  <div class="container">
    <h1 class="fw-bold">Courses</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/lautech_csd/index.php" class="text-warning">Home</a></li>
        <li class="breadcrumb-item active text-white">Courses</li>
      </ol>
    </nav>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <p class="text-muted mb-4">
      All courses offered in the B.Tech Computer Science &amp; Engineering programme,
      organised by level and semester.
      <span class="badge bg-success ms-1">C</span> = Compulsory &nbsp;
      <span class="badge bg-secondary ms-1">E</span> = Elective
    </p>

    <!-- Level Tabs -->
    <ul class="nav nav-tabs mb-4" id="levelTabs" role="tablist">
      <?php foreach ($levels as $i => $level): ?>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?php echo ($i === 0) ? 'active' : ''; ?>"
                  id="tab-<?php echo $level; ?>"
                  data-bs-toggle="tab"
                  data-bs-target="#pane-<?php echo $level; ?>"
                  type="button" role="tab">
            <?php echo isset($levelNames[$level]) ? $levelNames[$level] : $level . 'L'; ?>
          </button>
        </li>
      <?php endforeach; ?>
    </ul>

    <div class="tab-content" id="levelTabContent">
      <?php foreach ($levels as $i => $level): ?>
        <div class="tab-pane fade <?php echo ($i === 0) ? 'show active' : ''; ?>"
             id="pane-<?php echo $level; ?>" role="tabpanel">

          <?php
          // Total units across all semesters for this level
          $levelTotal = 0;
          foreach ($courses[$level] as $semCourses) {
              foreach ($semCourses as $c) {
                  $levelTotal += $c['units'];
              }
          }
          ?>

          <?php foreach ($courses[$level] as $semester => $semCourses): ?>
            <h5 class="mt-3 mb-2 text-primary">
              <i class="fas fa-graduation-cap me-2"></i>
              <?php echo ($semester == 1) ? 'First' : 'Second'; ?> Semester
            </h5>

            <div class="table-responsive mb-3">
              <table class="table table-hover table-bordered align-middle">
                <thead class="table-success">
                  <tr>
                    <th>Code</th>
                    <th>Course Title</th>
                    <th class="text-center">Units</th>
                    <th class="text-center">Type</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $semTotal = 0;
                  $compCount = 0; $electCount = 0;
                  foreach ($semCourses as $course):
                      $semTotal += $course['units'];
                      if ($course['is_compulsory']) { $compCount++; } else { $electCount++; }
                  ?>
                  <tr>
                    <td><span class="fw-bold text-primary"><?php echo htmlspecialchars($course['code']); ?></span></td>
                    <td><?php echo htmlspecialchars($course['title']); ?></td>
                    <td class="text-center"><?php echo $course['units']; ?></td>
                    <td class="text-center">
                      <?php if ($course['is_compulsory']): ?>
                        <span class="badge bg-success">C</span>
                      <?php else: ?>
                        <span class="badge bg-secondary">E</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                  <tr>
                    <td colspan="2" class="text-end fw-bold">Semester Total</td>
                    <td class="text-center fw-bold"><?php echo $semTotal; ?></td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>

            <p class="text-muted small mb-4">
              <i class="fas fa-circle-info me-1"></i>
              <?php echo count($semCourses); ?> courses this semester —
              <?php echo $compCount; ?> Compulsory, <?php echo $electCount; ?> Elective.
            </p>

          <?php endforeach; ?>

          <div class="alert alert-success">
            <i class="fas fa-sigma me-2"></i>
            <strong>Total credit units for <?php echo $level; ?> Level:</strong>
            <?php echo $levelTotal; ?> units
          </div>

        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<?php require_once 'includes/footer.php'; ?>

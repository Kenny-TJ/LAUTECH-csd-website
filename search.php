<?php
// search.php
$pageTitle = 'Search — CS Department, LAUTECH';
require_once 'config/db.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

// Get and sanitise the search query
$query   = trim(isset($_GET['q']) ? $_GET['q'] : '');
$results = array(
    'news'        => array(),
    'courses'     => array(),
    'staff'       => array(),
    'regulations' => array(),
);
$totalFound = 0;

if ($query && strlen($query) >= 2) {
    $safe = '%' . mysqli_real_escape_string($conn, $query) . '%';

    // Search news
    $r = mysqli_query($conn,
        "SELECT id, title, body, category, published_at
         FROM news
         WHERE is_published = 1
         AND (title LIKE '$safe' OR body LIKE '$safe' OR category LIKE '$safe')
         ORDER BY published_at DESC
         LIMIT 5");
    $results['news'] = mysqli_fetch_all($r, MYSQLI_ASSOC);

    // Search courses
    $r = mysqli_query($conn,
        "SELECT code, title, level, semester, units
         FROM courses
         WHERE code LIKE '$safe' OR title LIKE '$safe'
         ORDER BY level ASC, code ASC
         LIMIT 8");
    $results['courses'] = mysqli_fetch_all($r, MYSQLI_ASSOC);

    // Search staff
    $r = mysqli_query($conn,
        "SELECT full_name, title, designation, role, email
         FROM staff
         WHERE is_active = 1
         AND (full_name LIKE '$safe'
              OR designation LIKE '$safe'
              OR role LIKE '$safe')
         LIMIT 5");
    $results['staff'] = mysqli_fetch_all($r, MYSQLI_ASSOC);

    // Search regulations
    $r = mysqli_query($conn,
        "SELECT id, category, title, content
         FROM regulations
         WHERE title LIKE '$safe' OR content LIKE '$safe' OR category LIKE '$safe'
         ORDER BY category ASC
         LIMIT 5");
    $results['regulations'] = mysqli_fetch_all($r, MYSQLI_ASSOC);

    // Count total
    foreach ($results as $group) {
        $totalFound += count($group);
    }
}

// Helper: highlight matched term in text
function highlight($text, $query) {
    if (!$query) return htmlspecialchars($text);
    $safe = preg_quote(htmlspecialchars($query), '/');
    return preg_replace(
        '/(' . $safe . ')/i',
        '<mark>$1</mark>',
        htmlspecialchars($text)
    );
}

// Helper: truncate body text around the matched term
function excerpt($text, $query, $length = 160) {
    $pos = stripos($text, $query);
    if ($pos === false) {
        return substr($text, 0, $length) . '...';
    }
    $start = max(0, $pos - 60);
    $excerpt = substr($text, $start, $length);
    if ($start > 0) { $excerpt = '...' . $excerpt; }
    if (strlen($text) > $start + $length) { $excerpt .= '...'; }
    return $excerpt;
}
?>

<!-- Page Banner -->
<div class="page-banner text-white d-flex align-items-center">
  <div class="container">
    <h1 class="fw-bold">Search Results</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a href="/lautech_csd/index.php" class="text-warning">Home</a>
        </li>
        <li class="breadcrumb-item active text-white">Search</li>
      </ol>
    </nav>
  </div>
</div>

<section class="py-5">
  <div class="container">

    <!-- Search Form -->
    <div class="row justify-content-center mb-5">
      <div class="col-lg-7">
        <form method="GET" action="/lautech_csd/search.php">
          <div class="input-group input-group-lg shadow-sm">
            <input type="text" name="q" class="form-control"
                   placeholder="Search courses, staff, news, regulations..."
                   value="<?php echo htmlspecialchars($query); ?>"
                   autofocus>
            <button class="btn btn-warning px-4" type="submit">
              <i class="fas fa-search me-1"></i> Search
            </button>
          </div>
          <?php if ($query): ?>
            <div class="mt-2 text-muted small text-center">
              <?php if ($totalFound > 0): ?>
                <i class="fas fa-circle-check text-success me-1"></i>
                Found <strong><?php echo $totalFound; ?></strong>
                result<?php echo $totalFound !== 1 ? 's' : ''; ?>
                for <strong>"<?php echo htmlspecialchars($query); ?>"</strong>
              <?php else: ?>
                <i class="fas fa-circle-xmark text-danger me-1"></i>
                No results found for
                <strong>"<?php echo htmlspecialchars($query); ?>"</strong>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </form>
      </div>
    </div>

    <?php if ($query && strlen($query) < 2): ?>
      <div class="alert alert-warning text-center col-lg-6 mx-auto">
        Please enter at least 2 characters to search.
      </div>

    <?php elseif ($query && $totalFound === 0): ?>
      <div class="text-center py-4">
        <i class="fas fa-magnifying-glass fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">No results found</h4>
        <p class="text-muted">Try different keywords or browse using the navigation menu.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap mt-3">
          <a href="/lautech_csd/courses.php" class="btn btn-outline-success btn-sm">
            <i class="fas fa-book-open me-1"></i>Browse Courses
          </a>
          <a href="/lautech_csd/staff.php" class="btn btn-outline-success btn-sm">
            <i class="fas fa-users me-1"></i>View Staff
          </a>
          <a href="/lautech_csd/news.php" class="btn btn-outline-success btn-sm">
            <i class="fas fa-newspaper me-1"></i>Read News
          </a>
        </div>
      </div>

    <?php elseif ($query): ?>

      <div class="row g-4">

        <!-- Left: Results -->
        <div class="col-lg-8">

          <!-- News Results -->
          <?php if (!empty($results['news'])): ?>
          <div class="mb-5">
            <h4 class="section-title mb-4">
              <i class="fas fa-newspaper me-2"></i>
              News &amp; Announcements
              <span class="badge bg-success ms-2"><?php echo count($results['news']); ?></span>
            </h4>
            <?php foreach ($results['news'] as $item): ?>
            <div class="card shadow-sm mb-3">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-1">
                  <span class="badge bg-success"><?php echo htmlspecialchars($item['category']); ?></span>
                  <small class="text-muted"><?php echo date('d M Y', strtotime($item['published_at'])); ?></small>
                </div>
                <h6 class="fw-bold mt-1">
                  <?php echo highlight($item['title'], $query); ?>
                </h6>
                <p class="text-muted small mb-2">
                  <?php echo highlight(excerpt($item['body'], $query), $query); ?>
                </p>
                <a href="/lautech_csd/news.php?id=<?php echo $item['id']; ?>"
                   class="btn btn-sm btn-outline-success">
                  Read Article <i class="fas fa-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <!-- Course Results -->
          <?php if (!empty($results['courses'])): ?>
          <div class="mb-5">
            <h4 class="section-title mb-4">
              <i class="fas fa-book-open me-2"></i>
              Courses
              <span class="badge bg-success ms-2"><?php echo count($results['courses']); ?></span>
            </h4>
            <div class="table-responsive">
              <table class="table table-hover table-bordered align-middle">
                <thead class="table-success">
                  <tr>
                    <th>Code</th>
                    <th>Course Title</th>
                    <th class="text-center">Level</th>
                    <th class="text-center">Units</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($results['courses'] as $course): ?>
                  <tr>
                    <td>
                      <span class="fw-bold text-primary">
                        <?php echo highlight($course['code'], $query); ?>
                      </span>
                    </td>
                    <td><?php echo highlight($course['title'], $query); ?></td>
                    <td class="text-center"><?php echo $course['level']; ?>L</td>
                    <td class="text-center"><?php echo $course['units']; ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <a href="/lautech_csd/courses.php" class="btn btn-sm btn-outline-success">
              View All Courses <i class="fas fa-arrow-right ms-1"></i>
            </a>
          </div>
          <?php endif; ?>

          <!-- Staff Results -->
          <?php if (!empty($results['staff'])): ?>
          <div class="mb-5">
            <h4 class="section-title mb-4">
              <i class="fas fa-users me-2"></i>
              Staff
              <span class="badge bg-success ms-2"><?php echo count($results['staff']); ?></span>
            </h4>
            <?php foreach ($results['staff'] as $member): ?>
            <div class="card shadow-sm mb-3">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="flex-shrink-0">
                  <div class="rounded-circle d-flex align-items-center justify-content-center"
                       style="width:48px;height:48px;
                              background:var(--lautech-maroon);
                              color:var(--lautech-gold);
                              font-weight:700;font-size:1.1rem;">
                    <?php echo strtoupper(substr($member['full_name'], 0, 1)); ?>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <div class="fw-bold">
                    <?php echo highlight(
                        $member['title'] . ' ' . $member['full_name'], $query); ?>
                  </div>
                  <div class="text-muted small">
                    <?php echo highlight($member['designation'], $query); ?>
                    <?php if ($member['role']): ?>
                      — <?php echo highlight($member['role'], $query); ?>
                    <?php endif; ?>
                  </div>
                </div>
                <?php if ($member['email']): ?>
                <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>"
                   class="btn btn-sm btn-outline-success flex-shrink-0">
                  <i class="fas fa-envelope"></i>
                </a>
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
            <a href="/lautech_csd/staff.php" class="btn btn-sm btn-outline-success">
              View All Staff <i class="fas fa-arrow-right ms-1"></i>
            </a>
          </div>
          <?php endif; ?>

          <!-- Regulations Results -->
          <?php if (!empty($results['regulations'])): ?>
          <div class="mb-5">
            <h4 class="section-title mb-4">
              <i class="fas fa-scale-balanced me-2"></i>
              Regulations
              <span class="badge bg-success ms-2"><?php echo count($results['regulations']); ?></span>
            </h4>
            <?php foreach ($results['regulations'] as $reg): ?>
            <div class="card shadow-sm mb-3">
              <div class="card-body">
                <span class="badge bg-secondary mb-2">
                  <?php echo htmlspecialchars($reg['category']); ?>
                </span>
                <h6 class="fw-bold">
                  <?php echo highlight($reg['title'], $query); ?>
                </h6>
                <p class="text-muted small mb-2">
                  <?php echo highlight(excerpt($reg['content'], $query), $query); ?>
                </p>
                <a href="/lautech_csd/regulations.php" class="btn btn-sm btn-outline-success">
                  View Regulations <i class="fas fa-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

        </div>

        <!-- Right: Quick Links Sidebar -->
        <div class="col-lg-4">
          <div class="card shadow-sm sticky-top" style="top:80px;">
            <div class="card-header">
              <h6 class="mb-0">
                <i class="fas fa-compass me-2 text-primary"></i>Browse by Section
              </h6>
            </div>
            <div class="list-group list-group-flush">
              <a href="/lautech_csd/news.php"
                 class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                <i class="fas fa-newspaper text-primary" style="width:18px;"></i>
                News &amp; Announcements
              </a>
              <a href="/lautech_csd/courses.php"
                 class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                <i class="fas fa-book-open text-primary" style="width:18px;"></i>
                Courses
              </a>
              <a href="/lautech_csd/staff.php"
                 class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                <i class="fas fa-users text-primary" style="width:18px;"></i>
                Staff Directory
              </a>
              <a href="/lautech_csd/regulations.php"
                 class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                <i class="fas fa-scale-balanced text-primary" style="width:18px;"></i>
                Regulations
              </a>
              <a href="/lautech_csd/calendar.php"
                 class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                <i class="fas fa-calendar-alt text-primary" style="width:18px;"></i>
                Academic Calendar
              </a>
              <a href="/lautech_csd/resources.php"
                 class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                <i class="fas fa-download text-primary" style="width:18px;"></i>
                Student Resources
              </a>
            </div>
          </div>
        </div>

      </div>

    <?php else: ?>
      <!-- Empty state — no query yet -->
      <div class="text-center py-4">
        <i class="fas fa-magnifying-glass fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">What are you looking for?</h4>
        <p class="text-muted">
          Search across courses, staff, news, and regulations.
        </p>
      </div>
    <?php endif; ?>

  </div>
</section>

<style>
mark {
  background: rgba(192,160,98,0.35);
  color: inherit;
  border-radius: 2px;
  padding: 0 2px;
}
</style>

<?php require_once 'includes/footer.php'; ?>
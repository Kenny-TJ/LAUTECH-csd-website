<?php
$pageTitle = 'Home — CS Department, LAUTECH';
require_once 'config/db.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$newsResult = mysqli_query($conn,
    "SELECT * FROM news WHERE is_published = 1 ORDER BY published_at DESC LIMIT 3");
$latestNews = mysqli_fetch_all($newsResult, MYSQLI_ASSOC);

$eventsResult = mysqli_query($conn,
    "SELECT * FROM academic_calendar WHERE start_date >= CURDATE()
     ORDER BY start_date ASC LIMIT 4");
$upcomingEvents = mysqli_fetch_all($eventsResult, MYSQLI_ASSOC);

$catColours = array(
    'Examination'  => 'bg-danger',
    'Registration' => 'bg-primary',
    'Holiday'      => 'bg-warning text-dark',
    'Academic'     => 'bg-success',
);
?>

<!-- ── Hero ──────────────────────────────────────────── -->
<section class="hero-section text-white d-flex align-items-center"
         style="background-image:
                  linear-gradient(135deg, rgba(26,0,0,0.88) 0%,
                                          rgba(61,0,0,0.80) 50%,
                                          rgba(139,26,26,0.75) 100%),
                  url('/lautech_csd/assets/images/hero-bg.jpeg');
                background-size: cover;
                background-position: center;">
				
  <div class="container text-center">
    <p class="text-warning fw-semibold mb-2 reveal" style="letter-spacing:0.12em;font-size:0.88rem;">
      LADOKE AKINTOLA UNIVERSITY OF TECHNOLOGY
    </p>
    <h1 class="display-5 fw-bold mb-3 reveal" style="transition-delay:0.1s;">
      Department of Computer Science<br>&amp; Engineering
    </h1>
    <p class="lead mb-4 reveal" style="color:rgba(255,255,255,0.85);transition-delay:0.2s;">
      Producing innovative, ethical, and world-class computing graduates.
    </p>
    <div class="d-flex justify-content-center gap-3 flex-wrap reveal" style="transition-delay:0.3s;">
      <a href="/lautech_csd/about.php" class="btn btn-warning btn-lg px-4 fw-semibold">
        About the Department
      </a>
      <a href="/lautech_csd/courses.php" class="btn btn-outline-light btn-lg px-4">
        View Courses
      </a>
    </div>
  </div>
</section>

<!-- ── HOD Welcome ───────────────────────────────────── -->
<section class="py-5">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-3 text-center reveal reveal-left">
        <img src="/lautech_csd/assets/images/staff/hod.jpg"
             alt="Head of Department"
             class="rounded-circle shadow hod-photo"
             style="width:160px;height:160px;object-fit:cover;
                    border:5px solid var(--lautech-gold);"
             onerror="this.src='https://placehold.co/160x160/DA251D/C0A062?text=HOD'">
        <div class="mt-3 fw-bold">Dr. Mohammed Ibrahim</div>
        <div class="text-muted small">Head of Department</div>
      </div>
      <div class="col-lg-9 reveal reveal-right">
        <h2 class="section-title mb-4">Welcome from the HOD</h2>
        <p class="lead">
          On behalf of the staff and students of the Department of Computer
          Science and Engineering, I warmly welcome you to our departmental website.
        </p>
        <p class="text-muted">
          Our department is committed to academic excellence, research innovation,
          and producing graduates who are not only technically proficient but also
          ethically grounded and socially responsible. We continue to invest in our
          curriculum, our facilities, and our people — because the quality of our
          graduates is the most accurate measure of our success.
        </p>
        <p class="text-muted mb-0">
          Whether you are a prospective student, a current student, a member of staff,
          or an industry partner, I invite you to explore what our department has to offer.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ── Quick Access ──────────────────────────────────── -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="section-title text-center mb-2 reveal">What Would You Like To Do?</h2>
    <p class="text-muted text-center mb-4 reveal">Quick access to the most-used resources</p>
    <div class="row g-4 stagger-children">

      <div class="col-sm-6 col-lg-3">
        <div class="card h-100 shadow-sm text-center quick-card">
          <div class="card-body">
            <div class="mb-3" style="font-size:2rem;color:var(--lautech-red);">
              <i class="fas fa-book-open"></i>
            </div>
            <h6 class="fw-bold">View Courses</h6>
            <p class="small text-muted">Browse all courses from 100L to 500L</p>
            <a href="/lautech_csd/courses.php" class="btn btn-sm btn-primary mt-2">Go</a>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3">
        <div class="card h-100 shadow-sm text-center quick-card">
          <div class="card-body">
            <div class="mb-3" style="font-size:2rem;color:var(--lautech-red);">
              <i class="fas fa-file-arrow-up"></i>
            </div>
            <h6 class="fw-bold">File Submission</h6>
            <p class="small text-muted">Student profiling &amp; file submission portal</p>
            <a href="/lautech_csd/profile.php" class="btn btn-sm btn-primary mt-2">Go</a>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3 stagger-children">
        <div class="card h-100 shadow-sm text-center quick-card">
          <div class="card-body">
            <div class="mb-3" style="font-size:2rem;color:var(--lautech-red);">
              <i class="fas fa-calendar-alt"></i>
            </div>
            <h6 class="fw-bold">Academic Calendar</h6>
            <p class="small text-muted">Key dates for the current session</p>
            <a href="/lautech_csd/calendar.php" class="btn btn-sm btn-primary mt-2">Go</a>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3">
        <div class="card h-100 shadow-sm text-center quick-card">
          <div class="card-body">
            <div class="mb-3" style="font-size:2rem;color:var(--lautech-red);">
              <i class="fas fa-download"></i>
            </div>
            <h6 class="fw-bold">Student Resources</h6>
            <p class="small text-muted">Forms, handbooks &amp; past questions</p>
            <a href="/lautech_csd/resources.php" class="btn btn-sm btn-primary mt-2">Go</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ── Latest News ───────────────────────────────────── -->
<?php if (!empty($latestNews)): ?>
<section class="py-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="section-title mb-0 reveal">Latest News &amp; Notices</h2>
      <a href="/lautech_csd/news.php" class="btn btn-outline-success btn-sm reveal">
        View All <i class="fas fa-arrow-right ms-1"></i>
      </a>
    </div>
    <div class="row g-4 stagger-children">
      <?php foreach ($latestNews as $item): ?>
      <div class="col-md-4">
        <div class="card h-100 shadow-sm news-card">
          <div class="card-body">
            <span class="badge bg-success mb-2"><?php echo htmlspecialchars($item['category']); ?></span>
            <h5 class="card-title" style="font-size:1rem;">
              <?php echo htmlspecialchars($item['title']); ?>
            </h5>
            <p class="card-text text-muted small">
              <?php echo htmlspecialchars(substr($item['body'], 0, 120)); ?>...
            </p>
          </div>
          <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
            <small class="text-muted">
              <i class="fas fa-clock me-1"></i>
              <?php echo date('d M Y', strtotime($item['published_at'])); ?>
            </small>
            <a href="/lautech_csd/news.php?id=<?php echo $item['id']; ?>"
               class="btn btn-sm btn-outline-success">Read More</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── Upcoming Events ───────────────────────────────── -->
<?php if (!empty($upcomingEvents)): ?>
<section class="py-5 bg-light">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="section-title mb-0 reveal">Upcoming Events</h2>
      <a href="/lautech_csd/calendar.php" class="btn btn-outline-success btn-sm reveal">
        Full Calendar <i class="fas fa-arrow-right ms-1"></i>
      </a>
    </div>
    <div class="row g-3 stagger-children">
      <?php foreach ($upcomingEvents as $ev):
        $colour = isset($catColours[$ev['category']]) ? $catColours[$ev['category']] : 'bg-secondary';
      ?>
      <div class="col-md-6">
        <div class="d-flex align-items-center gap-3 p-3 bg-white border rounded shadow-sm">
          <div class="event-date-box text-center flex-shrink-0">
            <div class="event-day"><?php echo date('d', strtotime($ev['start_date'])); ?></div>
            <div class="event-month"><?php echo date('M', strtotime($ev['start_date'])); ?></div>
          </div>
          <div>
            <div class="fw-semibold"><?php echo htmlspecialchars($ev['activity']); ?></div>
            <span class="badge <?php echo $colour; ?> mt-1">
              <?php echo htmlspecialchars($ev['category']); ?>
            </span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── Stats Strip ───────────────────────────────────── -->
<section class="py-5 stats-strip text-white text-center">
  <div class="container reveal">
    <h2 class="fw-bold mb-2" style="color:var(--lautech-gold);">Our Vision</h2>
    <p class="col-md-8 mx-auto mb-4" style="color:rgba(255,255,255,0.9);">
      To produce the best graduates who will provide computer solutions to varieties of problems,
      and who will be self-employable by adapting to the exponential trend in
      Information Technology globally.
    </p>
    <div class="row g-3 stagger-children">
      <div class="col-6 col-md-3">
        <div class="stat-number" data-target="30+">30+</div>
        <div class="stat-label">Years of Excellence</div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-number" data-target="500+">500+</div>
        <div class="stat-label">Alumni Worldwide</div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-number" data-target="20+">20+</div>
        <div class="stat-label">Academic Staff</div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-number" data-target="5">5</div>
        <div class="stat-label">Levels of Study</div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>

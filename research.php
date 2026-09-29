<?php
$pageTitle = 'Research — CS Department, LAUTECH';
require_once 'config/db.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

// Pull staff with their designations for research profiles
$staff = mysqli_fetch_all(mysqli_query($conn,
    "SELECT * FROM staff WHERE is_active = 1 ORDER BY sort_order ASC"),
    MYSQLI_ASSOC
);

$researchAreas = array(
    'Artificial Intelligence & Machine Learning',
    'Cybersecurity & Information Assurance',
    'Software Engineering & Development',
    'Computer Networks & Distributed Systems',
    'Database Systems & Data Engineering',
    'Human-Computer Interaction',
    'Embedded Systems & IoT',
    'Cloud Computing & Virtualisation',
    'Bioinformatics & Computational Biology',
    'Mobile & Web Technologies',
);
?>

<div class="page-banner text-white d-flex align-items-center">
  <div class="container">
    <h1 class="fw-bold">Research</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/lautech_csd/index.php" class="text-warning">Home</a></li>
        <li class="breadcrumb-item active text-white">Research</li>
      </ol>
    </nav>
  </div>
</div>

<!-- Research Overview -->
<section class="py-5">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <h2 class="section-title mb-4">Research at CS &amp; Engineering</h2>
        <p class="lead">
          The Department of Computer Science and Engineering is committed to advancing
          knowledge through rigorous academic research that addresses real-world challenges
          in computing and technology.
        </p>
        <p class="text-muted">
          Our academic staff are actively engaged in research across a broad spectrum of
          computing disciplines — from fundamental theoretical work to applied research
          with direct industry relevance. We encourage and support student involvement
          in research activities at both undergraduate and postgraduate levels.
        </p>
        <p class="text-muted">
          The department collaborates with institutions, government agencies, and private
          organisations to ensure our research outputs contribute meaningfully to national
          development and global knowledge.
        </p>
      </div>
      <div class="col-lg-5">
        <div class="card bg-light border-0 p-4">
          <h5 class="fw-bold mb-3 text-primary">
            <i class="fas fa-flask me-2"></i>Research Focus Areas
          </h5>
          <ul class="list-unstyled mb-0">
            <?php foreach ($researchAreas as $area): ?>
              <li class="mb-2">
                <i class="fas fa-check-circle text-success me-2"></i>
                <?php echo htmlspecialchars($area); ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Academic Staff Research Profiles -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="section-title text-center mb-4">Academic Staff Research Profiles</h2>
    <p class="text-muted text-center mb-4">
      Contact any staff member directly to discuss research collaborations or postgraduate supervision.
    </p>
    <div class="row g-4">
      <?php
      $academics = array_filter($staff, function($m) {
          return strpos($m['designation'], 'Professor') !== false
              || strpos($m['designation'], 'Lecturer') !== false
              || strpos($m['designation'], 'Assistant') !== false;
      });
      foreach ($academics as $member):
      ?>
      <div class="col-md-6">
        <div class="card h-100 shadow-sm">
          <div class="card-body d-flex gap-3">
            <img src="/lautech_csd/assets/images/staff/<?php echo htmlspecialchars($member['photo']); ?>"
                 alt="<?php echo htmlspecialchars($member['full_name']); ?>"
                 class="rounded-circle flex-shrink-0"
                 style="width:70px;height:70px;object-fit:cover;border:3px solid var(--lautech-gold);"
                 onerror="this.src='https://placehold.co/70x70/006400/FFD700?text=<?php echo urlencode(substr($member['title'],0,1)); ?>'">
            <div>
              <h6 class="fw-bold mb-0">
                <?php echo htmlspecialchars($member['title'] . ' ' . $member['full_name']); ?>
              </h6>
              <div class="text-muted small mb-2"><?php echo htmlspecialchars($member['designation']); ?></div>
              <?php if ($member['email']): ?>
                <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>"
                   class="btn btn-sm btn-outline-success">
                  <i class="fas fa-envelope me-1"></i>Get in Touch
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Postgraduate Info -->
<section class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-6">
        <div class="card h-100 shadow-sm border-start border-success border-4">
          <div class="card-body">
            <h5 class="text-primary"><i class="fas fa-graduation-cap me-2"></i>Postgraduate Programmes</h5>
            <p class="text-muted">
              The department offers postgraduate programmes including M.Tech and Ph.D in
              Computer Science and Engineering. Candidates are supervised by experienced
              academic staff with strong research backgrounds.
            </p>
            <a href="/lautech_csd/contact.php" class="btn btn-outline-success btn-sm">
              Enquire About Postgraduate
            </a>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card h-100 shadow-sm border-start border-success border-4">
          <div class="card-body">
            <h5 class="text-primary"><i class="fas fa-handshake me-2"></i>Industry Collaboration</h5>
            <p class="text-muted">
              We welcome partnerships with industry organisations for sponsored research,
              student internship programmes, and applied research projects. Contact us
              to discuss how we can work together.
            </p>
            <a href="/lautech_csd/contact.php" class="btn btn-outline-success btn-sm">
              Partner With Us
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
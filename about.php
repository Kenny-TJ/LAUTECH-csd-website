<?php
// about.php
$pageTitle = 'About — CS Department, LAUTECH';
require_once 'config/db.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div class="page-banner text-white d-flex align-items-center">
  <div class="container">
    <h1 class="fw-bold">About the Department</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/lautech_csd/index.php" class="text-warning">Home</a></li>
        <li class="breadcrumb-item active text-white">About</li>
      </ol>
    </nav>
  </div>
</div>

<!-- History & Overview -->
<section class="py-5">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <h2 class="reveal reveal-left section-title mb-4">Our History</h2>

        <p>
          The Department of Computer Science and Engineering at Ladoke Akintola University
          of Technology (LAUTECH), Ogbomoso, was established to train highly skilled
          professionals capable of driving technological innovation in Nigeria and beyond.
        </p>
        <p>
          Over the years, the department has produced graduates who have made significant
          contributions in academia, industry, and public service — both locally and
          internationally.
        </p>
        <p>
          The department offers a B.Tech degree programme in Computer Science and Engineering,
          with a curriculum that balances theoretical foundations and practical applications,
          preparing students for the demands of a fast-evolving digital world.
        </p>
      </div>
      <div class="col-lg-5 text-center reveal reveal-right">
        <img src="/lautech_csd/assets/images/dept-building.jpg"
             alt="Department Building"
             class="img-fluid rounded shadow"
             onerror="this.src='https://placehold.co/500x350/006400/FFD700?text=CS+Department'">
      </div>
    </div>
  </div>
</section>

<!-- Vision, Mission, Objectives -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="section-title text-center mb-4">Our Core Values</h2>
    <div class="row g-4 stagger-children">

      <div class="col-md-4">
        <div class="card h-100 shadow-sm border-start border-success border-4">
          <div class="card-body">
            <h5 class="card-title text-primary">
              <i class="fas fa-eye me-2"></i>Vision
            </h5>
            <p class="card-text">
              To produce the best graduates who will provide computer solutions to varieties
              of problems and who will be self-employable by adapting to the exponential
              trend in Information Technology globally.
            </p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 shadow-sm border-start border-success border-4">
          <div class="card-body">
            <h5 class="card-title text-primary">
              <i class="fas fa-bullseye me-2"></i>Mission
            </h5>
            <p class="card-text">
              To provide quality education in Computer Science and Engineering through
              sound theoretical knowledge and practical training, fostering research,
              innovation, and ethical responsibility in our graduates.
            </p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 shadow-sm border-start border-success border-4">
          <div class="card-body">
            <h5 class="card-title text-primary">
              <i class="fas fa-list-check me-2"></i>Objectives
            </h5>
            <ul class="small ps-3 mb-0">
              <li class="mb-1">Train graduates in core computing disciplines</li>
              <li class="mb-1">Encourage research and intellectual curiosity</li>
              <li class="mb-1">Equip students with industry-relevant skills</li>
              <li class="mb-1">Promote ethical use of technology</li>
              <li>Foster collaboration with industry and government</li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Departmental Leadership -->
<section class="py-5">
  <div class="container">
    <h2 class="section-title text-center mb-4">Departmental Leadership</h2>
    <div class="table-responsive col-lg-8 mx-auto reveal">
      <table class="table table-bordered table-hover">
        <thead class="table-success">
          <tr><th>Role</th><th>Officer</th></tr>
        </thead>
        <tbody>
          <?php
          $leaders = mysqli_query($conn,
              "SELECT title, full_name, role FROM staff
               WHERE role IS NOT NULL AND is_active = 1
               ORDER BY sort_order ASC"
          );
          while ($ldr = mysqli_fetch_assoc($leaders)):
          ?>
          <tr>
            <td><?php echo htmlspecialchars($ldr['role']); ?></td>
            <td class="fw-semibold">
              <?php echo htmlspecialchars($ldr['title'] . ' ' . $ldr['full_name']); ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- Stats Strip -->
<section class="py-4 stats-strip text-white text-center">
  <div class="container reveal">
    <div class="row g-3">
      <div class="col-6 col-md-3">
        <div class="stat-number">30+</div>
        <div class="stat-label">Years of Excellence</div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-number">500+</div>
        <div class="stat-label">Alumni Worldwide</div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-number">20+</div>
        <div class="stat-label">Academic Staff</div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-number"><?php echo date('Y') - 1990; ?>+</div>
        <div class="stat-label">Years of Operation</div>
      </div>
    </div>
  </div>
</section>

<!-- Accreditation -->
<section class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="section-title text-center mb-3">Accreditation &amp; Affiliations</h2>
    <p class="text-muted col-md-7 mx-auto">
      The department is fully accredited by the National Universities Commission (NUC)
      and affiliated with the Computer Professionals (Registration Council) of Nigeria (CPN).
    </p>
    <div class="d-flex justify-content-center gap-4 mt-4 flex-wrap stagger-children">
      <span class="badge bg-success fs-6 px-4 py-2">NUC Accredited</span>
      <span class="badge bg-primary fs-6 px-4 py-2">CPN Affiliated</span>
      <span class="badge bg-secondary fs-6 px-4 py-2">LAUTECH — Est. 1990</span>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>

<?php
$pageTitle = 'Student Resources — CS Department, LAUTECH';
require_once 'config/db.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$resources = array(
    array(
        'category' => 'Forms & Applications',
        'icon'     => 'fa-file-lines',
        'items'    => array(
            array('name' => 'Course Registration Form',           'type' => 'PDF', 'note' => 'Required each semester'),
            array('name' => 'Change of Course Application',       'type' => 'PDF', 'note' => 'Submit to Exams Officer'),
            array('name' => 'Industrial Training Placement Form', 'type' => 'PDF', 'note' => '300 Level students'),
            array('name' => 'Deferment of Admission Form',        'type' => 'PDF', 'note' => 'With supporting documents'),
            array('name' => 'Carry-Over Course Registration',     'type' => 'PDF', 'note' => 'For failed courses'),
            array('name' => 'Final Year Project Proposal Form',   'type' => 'PDF', 'note' => '500 Level students'),
        ),
    ),
    array(
        'category' => 'Handbooks & Guides',
        'icon'     => 'fa-book',
        'items'    => array(
            array('name' => 'Student Handbook 2024/2025',          'type' => 'PDF', 'note' => 'All students'),
            array('name' => 'Final Year Project Guidelines',        'type' => 'PDF', 'note' => '500 Level'),
            array('name' => 'Industrial Training Guidelines',       'type' => 'PDF', 'note' => '300 Level'),
            array('name' => 'Laboratory Safety Rules',             'type' => 'PDF', 'note' => 'All students'),
            array('name' => 'Research & Writing Guide',            'type' => 'PDF', 'note' => 'All levels'),
        ),
    ),
    array(
        'category' => 'Academic Resources',
        'icon'     => 'fa-chalkboard-teacher',
        'items'    => array(
            array('name' => 'Grading & GPA Computation Guide',    'type' => 'PDF', 'note' => 'All students'),
            array('name' => 'Class Timetable — 2024/2025 Sem 2',  'type' => 'PDF', 'note' => 'Current semester'),
            array('name' => 'Examination Timetable',              'type' => 'PDF', 'note' => 'Updated each semester'),
            array('name' => 'NUC Accreditation Report Summary',   'type' => 'PDF', 'note' => 'Department overview'),
        ),
    ),
);

$usefulLinks = array(
    array('name' => 'LAUTECH Student Portal',     'url' => 'https://portal.lautech.edu.ng',  'desc' => 'Course registration, results, fees'),
    array('name' => 'LAUTECH Official Website',   'url' => 'https://www.lautech.edu.ng',     'desc' => 'University news and information'),
    array('name' => 'NUC Nigeria',                'url' => 'https://www.nuc.edu.ng',         'desc' => 'National Universities Commission'),
    array('name' => 'CPN Nigeria',                'url' => 'https://www.cpn.gov.ng',         'desc' => 'Computer Professionals (Registration Council)'),
    array('name' => 'Google Scholar',             'url' => 'https://scholar.google.com',     'desc' => 'Academic papers and research'),
    array('name' => 'IEEE Xplore',                'url' => 'https://ieeexplore.ieee.org',    'desc' => 'Technical papers in computing'),
);
?>

<div class="page-banner text-white d-flex align-items-center">
  <div class="container">
    <h1 class="fw-bold">Student Resources</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/lautech_csd/index.php" class="text-warning">Home</a></li>
        <li class="breadcrumb-item active text-white">Resources</li>
      </ol>
    </nav>
  </div>
</div>

<section class="py-5">
  <div class="container">

    <div class="alert alert-info d-flex gap-2 mb-5">
      <i class="fas fa-circle-info mt-1 flex-shrink-0"></i>
      <div>
        Physical copies of all forms are available at the <strong>Departmental Secretary's Office</strong>.
        Digital versions will be available for download here once uploaded by the department.
        Contact <a href="mailto:cse@lautech.edu.ng">cse@lautech.edu.ng</a> for urgent requests.
      </div>
    </div>

    <?php foreach ($resources as $section): ?>
    <div class="mb-5">
      <h3 class="section-title mb-4">
        <i class="fas <?php echo $section['icon']; ?> me-2 text-primary"></i>
        <?php echo htmlspecialchars($section['category']); ?>
      </h3>
      <div class="row g-3">
        <?php foreach ($section['items'] as $item): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="flex-shrink-0">
                <i class="fas fa-file-pdf fa-2x text-danger"></i>
              </div>
              <div class="flex-grow-1">
                <div class="fw-semibold small"><?php echo htmlspecialchars($item['name']); ?></div>
                <div class="text-muted" style="font-size:0.75rem;"><?php echo htmlspecialchars($item['note']); ?></div>
              </div>
              <span class="badge bg-secondary flex-shrink-0"><?php echo $item['type']; ?></span>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- Useful Links -->
    <div class="mb-5">
      <h3 class="section-title mb-4">
        <i class="fas fa-link me-2 text-primary"></i>Useful Links
      </h3>
      <div class="row g-3">
        <?php foreach ($usefulLinks as $link): ?>
        <div class="col-md-6 col-lg-4">
          <a href="<?php echo htmlspecialchars($link['url']); ?>"
             target="_blank" rel="noopener"
             class="card shadow-sm text-decoration-none h-100 useful-link-card">
            <div class="card-body d-flex align-items-center gap-3">
              <i class="fas fa-external-link-alt text-primary fa-lg flex-shrink-0"></i>
              <div>
                <div class="fw-semibold small text-dark"><?php echo htmlspecialchars($link['name']); ?></div>
                <div class="text-muted" style="font-size:0.75rem;"><?php echo htmlspecialchars($link['desc']); ?></div>
              </div>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>

<style>
.useful-link-card:hover { border-color: var(--lautech-green) !important; }
</style>

<?php require_once 'includes/footer.php'; ?>
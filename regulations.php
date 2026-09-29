<?php
// regulations.php
$pageTitle = 'Regulations — CS Department, LAUTECH';
require_once 'config/db.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$catResult  = mysqli_query($conn,
    "SELECT DISTINCT category FROM regulations ORDER BY category ASC");
$categories = mysqli_fetch_all($catResult, MYSQLI_ASSOC);

$regResult  = mysqli_query($conn,
    "SELECT * FROM regulations ORDER BY category ASC, sort_order ASC");
$regulations = array();
while ($row = mysqli_fetch_assoc($regResult)) {
    $regulations[$row['category']][] = $row;
}

$catIcons = array(
    'General Rules'      => 'fa-shield-halved',
    'Examination Rules'  => 'fa-pen-to-square',
    'Result Computation' => 'fa-calculator',
    'Faculty Rules'      => 'fa-building-columns',
);
?>

<div class="page-banner text-white d-flex align-items-center">
  <div class="container">
    <h1 class="fw-bold">Regulations</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/lautech_csd/index.php" class="text-warning">Home</a></li>
        <li class="breadcrumb-item active text-white">Regulations</li>
      </ol>
    </nav>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <p class="text-muted mb-4">
      The following regulations govern academic conduct, examinations, results,
      and faculty matters for all students of the Department of Computer Science
      &amp; Engineering, LAUTECH.
    </p>

    <div class="row g-4">

      <!-- Sidebar -->
      <div class="col-lg-3">
        <div class="list-group sticky-top" style="top:20px;">
          <?php foreach ($categories as $i => $cat):
            $icon = isset($catIcons[$cat['category']]) ? $catIcons[$cat['category']] : 'fa-circle-info';
          ?>
            <a href="#section-<?php echo $i; ?>"
               class="list-group-item list-group-item-action d-flex align-items-center gap-2">
              <i class="fas <?php echo $icon; ?> text-primary"></i>
              <?php echo htmlspecialchars($cat['category']); ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Main Content -->
      <div class="col-lg-9">
        <?php foreach ($categories as $i => $cat):
          $catName = $cat['category'];
          $icon    = isset($catIcons[$catName]) ? $catIcons[$catName] : 'fa-circle-info';
          $items   = isset($regulations[$catName]) ? $regulations[$catName] : array();
        ?>
        <div id="section-<?php echo $i; ?>" class="mb-5">
          <h3 class="text-primary mb-3 border-bottom pb-2">
            <i class="fas <?php echo $icon; ?> me-2"></i>
            <?php echo htmlspecialchars($catName); ?>
          </h3>

          <div class="accordion" id="accordion-<?php echo $i; ?>">
            <?php foreach ($items as $j => $reg): ?>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button <?php echo ($j > 0) ? 'collapsed' : ''; ?>"
                          type="button"
                          data-bs-toggle="collapse"
                          data-bs-target="#reg-<?php echo $reg['id']; ?>">
                    <?php echo htmlspecialchars($reg['title']); ?>
                  </button>
                </h2>
                <div id="reg-<?php echo $reg['id']; ?>"
                     class="accordion-collapse collapse <?php echo ($j === 0) ? 'show' : ''; ?>"
                     data-bs-parent="#accordion-<?php echo $i; ?>">
                  <div class="accordion-body text-muted">
                    <?php echo nl2br(htmlspecialchars($reg['content'])); ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>

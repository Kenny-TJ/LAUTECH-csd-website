<?php
// news.php
$pageTitle = 'News & Announcements — CS Department, LAUTECH';
require_once 'config/db.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

// Single article view
if (isset($_GET['id']) && intval($_GET['id']) > 0) {
    $id   = intval($_GET['id']);
    $stmt = mysqli_prepare($conn,
        "SELECT * FROM news WHERE id = ? AND is_published = 1 LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $article = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$article) {
        header('Location: /lautech_csd/news.php');
        exit;
    }

    // Fetch 3 related articles (same category, different id)
    $stmt2 = mysqli_prepare($conn,
        "SELECT id, title, published_at FROM news
         WHERE category = ? AND id != ? AND is_published = 1
         ORDER BY published_at DESC LIMIT 3");
    mysqli_stmt_bind_param($stmt2, 'si', $article['category'], $id);
    mysqli_stmt_execute($stmt2);
    $related = mysqli_fetch_all(mysqli_stmt_get_result($stmt2), MYSQLI_ASSOC);
?>

<div class="page-banner text-white d-flex align-items-center">
  <div class="container">
    <h1 class="fw-bold" style="font-size:1.6rem;">
      <?php echo htmlspecialchars($article['title']); ?>
    </h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/lautech_csd/index.php" class="text-warning">Home</a></li>
        <li class="breadcrumb-item"><a href="/lautech_csd/news.php" class="text-warning">News</a></li>
        <li class="breadcrumb-item active text-white">Article</li>
      </ol>
    </nav>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row g-4">

      <!-- Article -->
      <div class="col-lg-8">
        <div class="card shadow-sm">
          <div class="card-body p-4 p-md-5">
            <div class="d-flex gap-2 mb-3 flex-wrap">
              <span class="badge bg-success"><?php echo htmlspecialchars($article['category']); ?></span>
              <span class="text-muted small">
                <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($article['author']); ?>
              </span>
              <span class="text-muted small">
                <i class="fas fa-calendar me-1"></i>
                <?php echo date('d F Y', strtotime($article['published_at'])); ?>
              </span>
            </div>
            <h2 class="fw-bold mb-4"><?php echo htmlspecialchars($article['title']); ?></h2>
            <div class="article-body text-muted" style="line-height:1.9;">
              <?php echo nl2br(htmlspecialchars($article['body'])); ?>
            </div>
            <hr class="mt-4">
            <a href="/lautech_csd/news.php" class="btn btn-outline-success">
              <i class="fas fa-arrow-left me-1"></i>Back to News
            </a>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
          <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-layer-group me-2 text-success"></i>Related Articles</h6>
          </div>
          <div class="card-body p-0">
            <?php if (empty($related)): ?>
              <p class="text-muted p-3 mb-0 small">No related articles found.</p>
            <?php else: ?>
              <?php foreach ($related as $rel): ?>
              <a href="/lautech_csd/news.php?id=<?php echo $rel['id']; ?>"
                 class="d-block p-3 border-bottom text-decoration-none related-link">
                <div class="small fw-semibold text-dark">
                  <?php echo htmlspecialchars(substr($rel['title'], 0, 60)); ?>...
                </div>
                <div class="text-muted" style="font-size:0.75rem;">
                  <?php echo date('d M Y', strtotime($rel['published_at'])); ?>
                </div>
              </a>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>

        <div class="card shadow-sm">
          <div class="card-body text-center p-4">
            <i class="fas fa-envelope fa-2x text-primary mb-2"></i>
            <p class="small text-muted mb-2">Questions about this notice?</p>
            <a href="/lautech_csd/contact.php" class="btn btn-sm btn-outline-success">Contact the Department</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<?php
} else {
    // News listing page
    $category  = isset($_GET['cat']) ? mysqli_real_escape_string($conn, $_GET['cat']) : '';
    $whereSQL  = $category ? "AND category = '$category'" : '';

    $allNews = mysqli_fetch_all(mysqli_query($conn,
        "SELECT * FROM news WHERE is_published = 1 $whereSQL
         ORDER BY published_at DESC"),
        MYSQLI_ASSOC
    );

    $categories = mysqli_fetch_all(mysqli_query($conn,
        "SELECT DISTINCT category FROM news WHERE is_published = 1 ORDER BY category ASC"),
        MYSQLI_ASSOC
    );
?>

<div class="page-banner text-white d-flex align-items-center">
  <div class="container">
    <h1 class="fw-bold">News &amp; Announcements</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/lautech_csd/index.php" class="text-warning">Home</a></li>
        <li class="breadcrumb-item active text-white">News</li>
      </ol>
    </nav>
  </div>
</div>

<section class="py-5">
  <div class="container">

    <!-- Category Filter -->
    <div class="d-flex gap-2 flex-wrap mb-4">
      <a href="/lautech_csd/news.php"
         class="btn btn-sm <?php echo !$category ? 'btn-success' : 'btn-outline-success'; ?>">
        All
      </a>
      <?php foreach ($categories as $cat): ?>
        <a href="?cat=<?php echo urlencode($cat['category']); ?>"
           class="btn btn-sm <?php echo ($category === $cat['category']) ? 'btn-success' : 'btn-outline-success'; ?>">
          <?php echo htmlspecialchars($cat['category']); ?>
        </a>
      <?php endforeach; ?>
    </div>

    <?php if (empty($allNews)): ?>
      <div class="text-center py-5">
        <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
        <p class="text-muted">No news items found.</p>
      </div>
    <?php else: ?>
    <div class="row g-4">
      <?php foreach ($allNews as $item): ?>
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm news-card">
          <div class="card-body">
            <span class="badge bg-success mb-2"><?php echo htmlspecialchars($item['category']); ?></span>
            <h5 class="card-title" style="font-size:1rem;">
              <?php echo htmlspecialchars($item['title']); ?>
            </h5>
            <p class="card-text text-muted small">
              <?php echo htmlspecialchars(substr($item['body'], 0, 130)); ?>...
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
    <?php endif; ?>

  </div>
</section>

<?php } ?>

<style>
.related-link:hover { background: #f8f9fa; }
.related-link:last-child { border-bottom: none !important; }
</style>

<?php require_once 'includes/footer.php'; ?>
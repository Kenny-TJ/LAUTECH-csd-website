<?php
// includes/header.php
// $pageTitle must be set before this file is included
if (!isset($pageTitle)) {
    $pageTitle = 'CS Department — LAUTECH';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Official website of the Department of Computer Science and Engineering, Ladoke Akintola University of Technology (LAUTECH), Ogbomoso.">
  <title><?php echo htmlspecialchars($pageTitle); ?></title>

  <!-- Bootstrap 5 -->
  <link href="/lautech_csd/assets/vendor/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link href="/lautech_csd/assets/vendor/fontawesome/all.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="/lautech_csd/assets/css/style.css" rel="stylesheet">
</head>
<body>

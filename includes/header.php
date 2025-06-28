<?php $base = '/Hotel'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Hotel Reservation</title>
  <link rel="stylesheet" href="<?= $base ?>/style.css">
</head>
<body class="<?= isset($isHome) ? 'home-bg' : '' ?>">
  <header> 
    <?php if (isset($isHome) && $isHome): ?>
      <h1>Hotel Reservation System</h1>
    <?php endif; ?>
    <nav>
      <a href="<?= $base ?>/index.php">Home</a>
      <a href="<?= $base ?>/View/Availability.php">Availability</a>
      <a href="<?= $base ?>/View/BillingSummary.php">Billing Summary</a>
      <a href="<?= $base ?>/View/ReviewSystem.php">Review</a>
    </nav>
  </header>

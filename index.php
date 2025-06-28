<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

$isHome = true; 
include 'includes/header.php'; 
?>

<main class="home-overlay">
  <div class="home-content">
    <h2>Welcome to Our Hotel</h2>
    <p>Use the navigation to check availability, billing, or submit a review.</p>
    <a href="index.php?logout=true">Logout</a>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

<?php 
include('../includes/header.php'); 
require_once '../Model/ReviewModel.php';

$reviews = ReviewModel::getReviews();
?>

<h2>Review System</h2>

<?php if (isset($_GET['message'])): ?>
  <p style="color: green; font-weight: bold;"><?= htmlspecialchars($_GET['message']) ?></p>
<?php endif; ?>

<form method="post" action="../Controller/ReviewController.php">
<label>Your Name:</label><br>
<input type="text" name="name" required><br>
<label>Traveler Type:</label><br>
<select name="type" required>
<option value="Solo">Solo</option>
<option value="Couple">Couple</option>
<option value="Family">Family</option>
<option value="Business">Business</option>
</select><br>
<label>Rating:</label><br>
<select name="rating" required>
<option value="1">⭐</option>
<option value="2">⭐⭐</option>
<option value="3">⭐⭐⭐</option>
<option value="4">⭐⭐⭐⭐</option>
<option value="5">⭐⭐⭐⭐⭐</option>
</select><br>
<label>Comment:</label><br>
<textarea name="comment" required></textarea><br>
<input type="submit" value="Submit Review">
</form>

<h3>Previous Reviews</h3>
<?php foreach ($reviews as $review): ?>
  <div class="review-box">
    <strong><?= htmlspecialchars($review['name']) ?></strong> (<?= htmlspecialchars($review['type']) ?>) - 
    <?= str_repeat('⭐', (int)$review['rating']) ?><br>
    <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
  </div>
<?php endforeach; ?>

<?php include('../includes/footer.php'); ?>

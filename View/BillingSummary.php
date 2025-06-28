<?php include('../includes/header.php'); ?>

<h2>Billing Summary</h2>

<?php
  $name = $_GET['name'] ?? '';
  $guest_no = intval($_GET['guest_no'] ?? 1);
  $room_type = $_GET['room_type'] ?? '';
  $checkin = $_GET['checkin'] ?? '';
  $checkout = $_GET['checkout'] ?? '';
  $days = floatval($_GET['days'] ?? 0);
  $rate_per_night = floatval($_GET['rate_per_night'] ?? 0);
  $room_charge = floatval($_GET['room_charge'] ?? 0);
  $service_tax = floatval($_GET['service_tax'] ?? 0);
  $food_charge = floatval($_GET['food_charge'] ?? 0);
  $total = floatval($_GET['total'] ?? 0);

  if ($name && $guest_no && $room_type && $checkin && $checkout && $days > 0):
    $split_amount = $total / $guest_no;
?>

<p><strong>Guest:</strong> <?= htmlspecialchars($name) ?></p>
<p><strong>Room Type:</strong> <?= htmlspecialchars($room_type) ?></p>
<p><strong>Stay Duration:</strong> <?= htmlspecialchars($checkin) ?> to <?= htmlspecialchars($checkout) ?> (<?= $days ?> nights)</p>

<div class="billing-info">
  <div class="charge-item">Rate per Night: $<?= number_format($rate_per_night, 2) ?></div>
  <div class="charge-item">Room Charges: $<?= number_format($room_charge, 2) ?></div>
  <div class="charge-item">Service Tax (10%): $<?= number_format($service_tax, 2) ?></div>
  <div class="charge-item">Food Charges: $<?= number_format($food_charge, 2) ?></div>
  <strong>Total: $<?= number_format($total, 2) ?></strong>
</div>

<h3>Split Payment (<?= $guest_no ?> guests)</h3>
<p>
<?php
for ($i = 1; $i <= $guest_no; $i++) {
  echo "Guest $i: $" . number_format($split_amount, 2);
  if ($i < $guest_no) echo " | ";
}
?>
</p>

<h3>Email Receipt</h3>
<form method="post" action="../Controller/BillingController.php">
  <input type="hidden" name="name" value="<?= htmlspecialchars($name) ?>">
  <input type="hidden" name="guest_no" value="<?= $guest_no ?>">
  <input type="hidden" name="room_type" value="<?= htmlspecialchars($room_type) ?>">
  <input type="hidden" name="checkin" value="<?= htmlspecialchars($checkin) ?>">
  <input type="hidden" name="checkout" value="<?= htmlspecialchars($checkout) ?>">
  <input type="hidden" name="days" value="<?= $days ?>">
  <input type="hidden" name="rate_per_night" value="<?= $rate_per_night ?>">
  <input type="hidden" name="room_charge" value="<?= $room_charge ?>">
  <input type="hidden" name="service_tax" value="<?= $service_tax ?>">
  <input type="hidden" name="food_charge" value="<?= $food_charge ?>">
  <input type="hidden" name="total" value="<?= $total ?>">

  <label>Email:</label><br>
  <input type="email" name="email" required><br>
  <button type="submit">Send Receipt</button>
</form>

<?php else: ?>
<p>Please check availability first and then view billing summary.</p>
<?php endif; ?>

<?php include('../includes/footer.php'); ?>

<?php 
include('../includes/header.php'); 
require_once('../Model/AvailabilityModel.php'); 
?>
<main>

<h2>Check Availability</h2>
<p>Select dates to see if a room is available</p>

<label>Your Name:</label><br>
<input type="text" id="guest_name" required pattern="[A-Za-z\s]{2,}" title="Name must contain only letters and be at least 2 characters."><br>

<label>Number of Guests:</label><br>
<input type="number" id="guest_no" min="1" max="4" required><br>

<label>Room Type:</label><br>
<select id="room_type" required onchange="adjustGuests()">
  <option value="Single">Single</option>
  <option value="Double">Double</option>
  <option value="Deluxe">Deluxe</option>
</select><br>

<label>Check-in Date:</label><br>
<input type="date" id="checkin" required><br>

<label>Check-out Date:</label><br>
<input type="date" id="checkout" required><br>

<button type="button" onclick="checkAvailability()">Check</button>

<div id="check-result" style="margin: 20px 0; font-weight: bold;"></div>

<button id="billing-button" onclick="goToBilling()" style="display:none;">View Billing Summary</button>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['name'], $_GET['room_type'], $_GET['checkin'], $_GET['checkout'], $_GET['guest_no'])) {
    AvailabilityModel::bookRoom(
        $_GET['name'],
        $_GET['room_type'],
        $_GET['checkin'],
        $_GET['checkout'],
        intval($_GET['guest_no'])
    );
}

function generateAvailabilityMap($roomType) {
    $availability = AvailabilityModel::getMonthlyAvailability($roomType);
    $map = [];

    foreach ($availability as $range) {
        $start = new DateTime($range['checkin_date']);
        $end = new DateTime($range['checkout_date']);
        while ($start < $end) {
            $map[$start->format('Y-m-d')] = 'unavailable';
            $start->modify('+1 day');
        }
    }

    return $map;
}

$roomType = $_GET['room_type'] ?? 'Single';
$availabilityMap = generateAvailabilityMap($roomType);
?>

<h3>Visual Availability Calendar (Next 30 Days from Today)</h3>
<div id="calendar"></div>

<h3>Seasonal Rates</h3>
<div class="seasonal-rates">
  <div>Jan - Mar: $120/night</div>
  <div>Apr - Aug: $150/night</div>
  <div>Sep - Dec: $100/night</div>
</div>

<script>
const availabilityMap = <?= json_encode($availabilityMap) ?>;
let lastBillingParams = null;

function generateCalendar() {
  const calendarDiv = document.getElementById("calendar");
  const today = new Date();
  let html = '<table><tr>';
  const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
  for (let d of days) html += `<th>${d}</th>`;
  html += '</tr><tr>';

  let startDay = today.getDay();
  for (let i = 0; i < startDay; i++) html += '<td></td>';

  for (let i = 0; i < 30; i++) {
    let currentDate = new Date(today);
    currentDate.setDate(today.getDate() + i);
    const yyyy = currentDate.getFullYear();
    const mm = String(currentDate.getMonth() + 1).padStart(2, '0');
    const dd = String(currentDate.getDate()).padStart(2, '0');
    const fullDate = `${yyyy}-${mm}-${dd}`;
    const status = availabilityMap[fullDate] === 'unavailable' ? '❌' : '✅';
    const color = status === '❌' ? 'red' : 'green';
    html += `<td style="color:${color}">${dd}<br>${status}</td>`;
    if ((startDay + i) % 7 === 6) html += '</tr><tr>';
  }

  let cellsInLastRow = (startDay + 30) % 7;
  if (cellsInLastRow !== 0) {
    for (let i = cellsInLastRow; i < 7; i++) html += '<td></td>';
  }

  html += '</tr></table>';
  calendarDiv.innerHTML = html;
}

function adjustGuests() {
  const roomType = document.getElementById('room_type').value;
  const guestInput = document.getElementById('guest_no');
  if (roomType === 'Single') {
    guestInput.value = 1;
    guestInput.disabled = true;
  } else {
    guestInput.disabled = false;
  }
}

function checkAvailability() {
  const guestName = document.getElementById('guest_name').value.trim();
  const guestNo = parseInt(document.getElementById('guest_no').value, 10);
  const roomType = document.getElementById('room_type').value;
  const checkin = document.getElementById('checkin').value;
  const checkout = document.getElementById('checkout').value;

  if (!/^[A-Za-z\s]{2,}$/.test(guestName)) {
    alert("Please enter a valid name (letters and spaces only, min 2 characters).");
    return;
  }

  if (isNaN(guestNo) || guestNo < 1 || guestNo > 4) {
    alert("Please enter a valid number of guests (1 to 4).");
    return;
  }

  if (!checkin || !checkout) {
    alert("Please select both check-in and check-out dates.");
    return;
  }

  const checkinDate = new Date(checkin);
  const checkoutDate = new Date(checkout);
  if (checkinDate >= checkoutDate) {
    alert("Check-in must be before check-out.");
    return;
  }

  const days = (checkoutDate - checkinDate) / (1000 * 60 * 60 * 24);
  let ratePerNight = 120;
  const month = checkinDate.getMonth() + 1;
  if (month >= 4 && month <= 8) ratePerNight = 150;
  else if (month >= 9 && month <= 12) ratePerNight = 100;

  const total = days * ratePerNight;
  let isAvailable = true;
  let date = new Date(checkinDate);
  while (date < checkoutDate) {
    const dateStr = date.toISOString().split('T')[0];
    if (availabilityMap[dateStr] === 'unavailable') {
      isAvailable = false;
      break;
    }
    date.setDate(date.getDate() + 1);
  }

  const resultDiv = document.getElementById('check-result');
  const billingBtn = document.getElementById('billing-button');

  if (isAvailable) {
    resultDiv.style.color = "green";
    resultDiv.innerText = `${roomType} room available for ${days} night(s). Approximate total: $${total.toFixed(2)}`;
    const params = new URLSearchParams({
      name: guestName,
      guest_no: guestNo,
      room_type: roomType,
      checkin: checkin,
      checkout: checkout,
      days: days,
      rate_per_night: ratePerNight,
      room_charge: total.toFixed(2),
      service_tax: (total * 0.10).toFixed(2),
      food_charge: "0.00",
      total: (total * 1.10).toFixed(2)
    });
    lastBillingParams = params;
    billingBtn.style.display = "inline-block";
  } else {
    resultDiv.style.color = "red";
    resultDiv.innerText = `${roomType} room is NOT available for the selected dates.`;
    billingBtn.style.display = "none";
    lastBillingParams = null;
  }
}

function goToBilling() {

  
  if (lastBillingParams) {
   window.location.href = `../Controller/AvailabilityController.php?${lastBillingParams.toString()}`;
  }
}

generateCalendar();
adjustGuests();
</script>

</main>
<?php include('../includes/footer.php'); ?>

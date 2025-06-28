<?php
require_once '../includes/db.php';
require_once '../Model/AvailabilityModel.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $room = floatval($_POST['room_charge'] ?? 0);
    $service = floatval($_POST['service_tax'] ?? 0);
    $food = floatval($_POST['food_charge'] ?? 0);
    $total = floatval($_POST['total'] ?? 0);

    $stmt = $pdo->prepare("INSERT INTO billing (email, room_charges, service_tax, food_charges, total) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$email, $room, $service, $food, $total]);

    header("Location: ../View/BillingSummary.php?message=Billing info saved.");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['name'], $_GET['room_type'], $_GET['checkin'], $_GET['checkout'], $_GET['guest_no'])) {
    $guest_name = $_GET['name'];
    $room_type = $_GET['room_type'];
    $checkin = $_GET['checkin'];
    $checkout = $_GET['checkout'];
    $guest_no = intval($_GET['guest_no']);

    AvailabilityModel::bookRoom($guest_name, $room_type, $checkin, $checkout, $guest_no);

    // Redirect to billing summary with all same GET data
    $query = http_build_query($_GET);
    header("Location: ../View/BillingSummary.php?$query");
    exit();
}
?>

<?php
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $room = floatval($_POST['room_charge'] ?? 0);
    $service = floatval($_POST['service_tax'] ?? 0);
    $food = floatval($_POST['food_charge'] ?? 0);
    $total = floatval($_POST['total'] ?? 0);

    // Insert using actual posted billing info
    $stmt = $pdo->prepare("INSERT INTO billing (email, room_charges, service_tax, food_charges, total) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$email, $room, $service, $food, $total]);

    header("Location: ../View/BillingSummary.php?message=Billing info saved.");
    exit();
}


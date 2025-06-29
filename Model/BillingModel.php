<?php
require_once '../includes/db.php';

class BillingModel {
    public static function saveBilling($email, $room_charges, $service_tax, $food_charges) {
        global $pdo;
        $total = $room_charges + $service_tax + $food_charges;

        $stmt = $pdo->prepare("
            INSERT INTO billing (email, room_charges, service_tax, food_charges, total)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$email, $room_charges, $service_tax, $food_charges, $total]);
    }

   
}
?>

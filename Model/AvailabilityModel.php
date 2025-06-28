<?php
require_once __DIR__ . '/../includes/db.php';

class AvailabilityModel {
    public static function bookRoom($guest_name, $room_type, $checkin, $checkout, $guest_no) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO availability (guest_name, room_type, checkin_date, checkout_date, guest_no) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$guest_name, $room_type, $checkin, $checkout, $guest_no]);
    }


    public static function isRoomAvailable($room_type, $checkin, $checkout) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM availability 
            WHERE room_type = ? AND checkin_date < ? AND checkout_date > ?
        ");
        $stmt->execute([$room_type, $checkout, $checkin]);
        $count = $stmt->fetchColumn();
        return $count == 0;
    }

    public static function getMonthlyAvailability($room_type) {
        global $pdo;
        $stmt = $pdo->prepare("
           SELECT checkin_date, checkout_date 
           FROM availability 
           WHERE room_type = ? 
           AND checkin_date >= CURDATE() 
           AND checkin_date <= DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
        ");
    $stmt->execute([$room_type]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  
}
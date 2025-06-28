<?php
require_once '../includes/db.php';

class ReviewModel {
    public static function saveReview($name, $type, $rating, $comment) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO reviews (name, type, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $type, $rating, $comment]);
    }

    public static function getReviews() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
}
?>

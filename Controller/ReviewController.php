<?php
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $stmt = $pdo->prepare("INSERT INTO reviews (name, type, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $type, $rating, $comment]);

    header("Location: ../View/ReviewSystem.php?message=Thanks for your review!");
    exit();
}

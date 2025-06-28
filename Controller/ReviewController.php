<?php
require_once '../Model/ReviewModel.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? '';
    $rating = $_POST['rating'] ?? 1;
    $comment = $_POST['comment'] ?? '';

    ReviewModel::saveReview($name, $type, $rating, $comment);
    header("Location: ../View/ReviewSystem.php?message=Review submitted successfully!");
    exit();
}
?>

<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../config.php';

$id = $_GET['id'];

$sql = "DELETE FROM cars WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

header("Location: manage_cars.php");
exit();

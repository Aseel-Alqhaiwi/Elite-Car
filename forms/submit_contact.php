<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_name = $_POST['car_name'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $preferred_time = $_POST['preferred_time'] ?? '';
    $message = $_POST['message'] ?? '';

    $sql = "INSERT INTO contact_messages (car_name, name, email, phone, preferred_time, message) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$car_name, $name, $email, $phone, $preferred_time, $message]);

    exit;
}
?>

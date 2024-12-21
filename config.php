<?php
$host = 'localhost';
$db = 'car_marketplace'; // Replace with your database name
$user = 'root';          // Replace with your MySQL username
$password = '';          // Replace with your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

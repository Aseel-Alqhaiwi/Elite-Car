<?php
include '../config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$car_id = $_GET['id'] ?? null;
$amount = $_GET['amount'] ?? null;

if (!$car_id || !$amount) {
    die("Invalid payment details.");
}

// Fetch car details from the database
$sql = "SELECT * FROM cars WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$car_id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    die("Car not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment Options</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Payment Options</h1>
        <div class="row">
            <!-- Pay with PayPal -->
            <div class="col-md-6 text-center">
                <h3>Pay with PayPal</h3>
                <a href="enter_paypal_details.php?id=<?= $car_id; ?>&amount=<?= $amount; ?>" class="btn btn-primary w-100">Pay with PayPal</a>
            </div>

            <!-- Pay with Card -->
            <div class="col-md-6 text-center">
                <h3>Pay with Card</h3>
                <a href="enter_card_details.php?id=<?= $car_id; ?>&amount=<?= $amount; ?>" class="btn btn-success w-100">Pay with Card</a>
            </div>
        </div>

        <div class="text-center mt-5">
            <h3>Total Price: $<?= number_format($amount, 0); ?></h3>
            <a href="car_details.php?id=<?= $car_id; ?>" class="btn btn-secondary">Back to Car Details</a>
        </div>
    </div>
</body>
</html>

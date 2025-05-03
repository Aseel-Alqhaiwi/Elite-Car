<?php
include '../config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$car_id = $_POST['car_id'] ?? null;
$amount = $_POST['amount'] ?? null;

if (!$car_id || !$amount) {
    die("Invalid payment details.");
}

// Save transaction to database with 'pending' status
$sql = "INSERT INTO transactions (user_id, car_id, amount, status) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id, $car_id, $amount, 'pending']);

// Redirect to PayPal
$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr"; // Use sandbox for testing
$business_email = "test@gmail.com"; // Replace with your PayPal sandbox business email

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Processing Payment</title>
</head>
<body>
    <form id="paypalForm" action="<?= $paypal_url; ?>" method="POST">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="<?= $business_email; ?>">
        <input type="hidden" name="item_name" value="Car Purchase">
        <input type="hidden" name="amount" value="<?= htmlspecialchars($amount); ?>">
        <input type="hidden" name="currency_code" value="USD">
        <input type="hidden" name="return" value="http://localhost/car_marketplace/payment_success.php">
        <input type="hidden" name="cancel_return" value="http://localhost/car_marketplace/payment_cancel.php">
    </form>
    <script>
        document.getElementById('paypalForm').submit();
    </script>
</body>
</html>

<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../config.php';

// Fetch total cars
$cars_count = $conn->query("SELECT COUNT(*) FROM cars")->fetchColumn();

// Fetch total users
$users_count = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome, Admin!</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <h5>Total Cars: <?= $cars_count; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <h5>Total Users: <?= $users_count; ?></h5>
                    </div>
                </div>
            </div>
        </div>
        <a href="manage_cars.php" class="btn btn-info">Manage Cars</a>
        <a href="manage_users.php" class="btn btn-warning">Manage Users</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>

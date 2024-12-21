<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../config.php';

// Fetch all cars
$cars = $conn->query("SELECT * FROM cars ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Cars</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Manage Cars</h1>
        <a href="add_car.php" class="btn btn-success mb-4">Add New Car</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                    <tr>
                        <td><?= $car['id']; ?></td>
                        <td><?= htmlspecialchars($car['name']); ?></td>
                        <td><?= htmlspecialchars($car['brand']); ?></td>
                        <td>$<?= htmlspecialchars($car['price']); ?></td>
                        <td>
                            <a href="edit_car.php?id=<?= $car['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_car.php?id=<?= $car['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

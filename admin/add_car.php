<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    $target_dir = "../assets/images/";
    $target_file = $target_dir . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO cars (name, brand, price, description, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $brand, $price, $description, $image]);
        $message = "Car added successfully!";
    } else {
        $message = "Failed to upload image.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Car</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Add a New Car</h1>
        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form action="add_car.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Car Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" name="brand" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" name="price" class="form-control" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Car</button>
        </form>
    </div>
</body>
</html>
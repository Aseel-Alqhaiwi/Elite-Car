<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../config.php';

$id = $_GET['id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $sql = "UPDATE cars SET name = ?, brand = ?, price = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$name, $brand, $price, $description, $id]);

    $message = "Car updated successfully!";
}

// Fetch car details for editing
$sql = "SELECT * FROM cars WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Car</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Car</h1>
        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form action="edit_car.php?id=<?= $id; ?>" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Car Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($car['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($car['brand']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" name="price" class="form-control" step="0.01" value="<?= htmlspecialchars($car['price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="6" required><?= htmlspecialchars($car['description']); ?></textarea>
                </div>
            <button type="submit" class="btn btn-primary">Update Car</button>
        </form>
    </div>
</body>
</html>
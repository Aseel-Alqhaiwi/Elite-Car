<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_car'])) {
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
        header("Location: manage_cars.php");
        exit();
    } else {
        $message = "Failed to upload image.";
    }
}

// Fetch cars with optional filtering
$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM cars";
$params = [];

if (!empty($search)) {
    $sql .= " WHERE name LIKE ? OR brand LIKE ?";
    $params = ["%$search%", "%$search%"];
}

$sql .= " ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pagination
$perPage = 7;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total = count($cars);
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage;
$paginatedCars = array_slice($cars, $offset, $perPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Cars</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #111; color: #fff; font-family: 'Segoe UI', sans-serif; }
        .container { margin-top: 60px; }
        .form-control, .form-control::placeholder {
            background-color: #1e1e1e; border: 1px solid #444; color: #fff;
        }
        .form-control:focus {
            border-color: #e63946;
            box-shadow: 0 0 0 0.2rem rgba(230, 57, 70, 0.25);
        }
        .table-responsive { border-radius: 10px; overflow: hidden; box-shadow: 0 0 10px rgba(230, 57, 70, 0.3); }
        .table-dark { background-color: #1e1e1e; color: #fff; }
        .table-dark th, .table-dark td { border-color: #444; }
        .table thead { background-color: #f8d7da; color: #000; }
        .btn-success { background-color: #e63946; border: none; font-weight: 500; border-radius: 20px; }
        .btn-success:hover { background-color: #c62828; }
        .btn-outline-light:hover { background-color: #e63946; color: white; }
        .btn-warning { background-color: #f39c12; border: none; }
        .btn-danger { background-color: #e74c3c; border: none; }
        .pagination .page-link { background-color: #222; color: #fff; border-color: #333; }
        .pagination .page-item.active .page-link, .pagination .page-link:hover {
            background-color: #e63946; color: #fff;
        }
        h1 { color: #e63946; }
        .modal-content { background-color: #1e1e1e; color: #fff; border: 1px solid #e63946; }
        .btn-custom {
    border-radius: 20px;
    font-weight: 500;
    padding: 8px 20px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.btn-custom i {
    margin-right: 5px;
}

.btn-red {
    background-color: #e63946;
    border: none;
    color: #fff;
}

.btn-red:hover {
    background-color: #c62828;
    color: #fff;
}

.btn-outline-light {
    border: 2px solid #ccc;
    color: #fff;
}

.btn-outline-light:hover {
    background-color: #e63946;
    color: #fff;
    border-color: #e63946;
}

    </style>
</head>
<body>

<div class="container">
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="dashboard.php" class="btn btn-outline-light btn-custom">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
    <div>
        <button class="btn btn-red btn-custom me-2" data-bs-toggle="modal" data-bs-target="#addCarModal">
            <i class="bi bi-plus-circle"></i> Add New Car
        </button>
        <a href="export_excel.php" class="btn btn-outline-light btn-custom">
            <i class="bi bi-download"></i> Export to Excel
        </a>
    </div>
</div>

    <form method="get" class="d-flex mb-3">
        <input type="text" name="search" class="form-control me-2" placeholder="Search name or brand..." value="<?= htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-outline-light"><i class="bi bi-search"></i></button>
    </form>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (count($cars) === 0): ?>
        <div class="alert alert-info">No cars found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-dark table-bordered align-middle">
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
                    <?php foreach ($paginatedCars as $car): ?>
                        <tr>
                            <td><?= $car['id']; ?></td>
                            <td><?= htmlspecialchars($car['name']); ?></td>
                            <td><?= htmlspecialchars($car['brand']); ?></td>
                            <td>$<?= number_format($car['price'], 2); ?></td>
                            <td>
                                <a href="edit_car.php?id=<?= $car['id']; ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                                <a href="delete_car.php?id=<?= $car['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Add Car Modal -->
<div class="modal fade" id="addCarModal" tabindex="-1" aria-labelledby="addCarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" enctype="multipart/form-data">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCarModalLabel">Add New Car</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="add_car" value="1">
                    <div class="mb-3">
                        <label class="form-label">Car Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Brand</label>
                        <input type="text" name="brand" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" name="price" class="form-control" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add Car</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

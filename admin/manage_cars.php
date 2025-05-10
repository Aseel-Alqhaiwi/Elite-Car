<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_car'])) {
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
            header("Location: manage_cars.php?success=1");
            exit();
        } else {
            $message = "Failed to upload image.";
        }
    }

    if (isset($_POST['update_car'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $brand = $_POST['brand'];
        $price = $_POST['price'];
        $description = $_POST['description'];

        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            $target_dir = "../assets/images/";
            $target_file = $target_dir . basename($image);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $sql = "UPDATE cars SET name = ?, brand = ?, price = ?, description = ?, image = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$name, $brand, $price, $description, $image, $id]);
            } else {
                $message = "Image upload failed.";
            }
        } else {
            $sql = "UPDATE cars SET name = ?, brand = ?, price = ?, description = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $brand, $price, $description, $id]);
        }

        $message = "Car updated successfully!";
    }
}

$search = $_GET['search'] ?? '';
$perPage = 7;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

$countSql = "SELECT COUNT(*) FROM cars";
$dataSql = "SELECT * FROM cars";

if (!empty($search)) {
    $searchInt = (int)$search;
    $safeSearch = "%$search%";
    $countSql .= " WHERE id = $searchInt OR name LIKE " . $conn->quote($safeSearch) . " OR brand LIKE " . $conn->quote($safeSearch);
    $dataSql .= " WHERE id = $searchInt OR name LIKE " . $conn->quote($safeSearch) . " OR brand LIKE " . $conn->quote($safeSearch);
}

$dataSql .= " ORDER BY created_at DESC LIMIT $perPage OFFSET $offset";

// تنفيذ استعلام العد
$stmt = $conn->prepare($countSql);
$stmt->execute();
$total = $stmt->fetchColumn();
$totalPages = ceil($total / $perPage);

// تنفيذ استعلام البيانات
$stmt = $conn->prepare($dataSql);
$stmt->execute();
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Cars</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #121416;
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
    }
    .table thead {
      background-color: #f8d7da;
      color: #000;
    }
    .form-control, .form-control::placeholder {
      background-color: #1e1e1e;
      color: #fff;
      border: 1px solid #444;
    }
    input::placeholder {
      color: #ccc;
    }
    .btn-danger, .btn-outline-light {
      border-radius: 20px;
      font-weight: 500;
    }
    .btn-outline-light:hover {
      background-color: #e63946;
      color: white;
      border-color: #e63946;
    }
    .modal-content {
      background-color: #1e1e1e;
      color: #fff;
      border: 1px solid #e63946;
    }
    .table-responsive {
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(230, 57, 70, 0.3);
    }
    img.car-thumbnail {
      max-width: 80px;
      height: auto;
      border-radius: 8px;
      border: 1px solid #888;
    }
  </style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <a href="dashboard.php" class="btn btn-outline-light me-2">
        <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
      </a>
    </div>
    <h1 class="text-danger m-0">Manage Cars</h1>
    <div>
      <button class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#addCarModal">
        <i class="bi bi-plus-circle"></i> Add New Car
      </button>
      <a href="export_excel.php" class="btn btn-outline-light">
        <i class="bi bi-download"></i> Export to Excel
      </a>
    </div>
  </div>


  <form method="get" class="mb-4">
  <div class="input-group shadow-sm">
    <input type="text" name="search" class="form-control bg-dark text-light border-secondary rounded-start-pill"
           placeholder="Search by ID, Name, or Brand" value="<?= htmlspecialchars($search); ?>">
    <button class="btn btn-danger rounded-end-pill" type="submit">
      <i class="bi bi-search"></i> Search
    </button>
  </div>
</form>


<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success shadow-sm">✅ Car added successfully!</div>
<?php elseif ($message): ?>
  <div class="alert alert-info shadow-sm">ℹ️ <?= $message ?> </div>
<?php endif; ?>


  <?php if (count($cars) === 0): ?>
    <div class="alert alert-warning">No cars found.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-dark table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Brand</th>
            <th>Price</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($cars as $car): ?>
          <tr>
            <td><?= $car['id'] ?></td>
            <td><img src="../assets/images/<?= htmlspecialchars($car['image']) ?>" class="car-thumbnail" alt="Car Image"></td>
            <td><?= htmlspecialchars($car['name']) ?></td>
            <td><?= htmlspecialchars($car['brand']) ?></td>
            <td>$<?= number_format($car['price'], 2) ?></td>
            <td>
              <button class="btn btn-sm btn-warning edit-btn" data-id="<?= $car['id'] ?>" data-name="<?= htmlspecialchars($car['name']) ?>" data-brand="<?= htmlspecialchars($car['brand']) ?>" data-price="<?= $car['price'] ?>" data-description="<?= htmlspecialchars($car['description']) ?>" data-image="<?= $car['image'] ?>" data-bs-toggle="modal" data-bs-target="#editCarModal">Edit</button>
              <a href="delete_car.php?id=<?= $car['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
            <a class="page-link bg-dark text-light border-secondary" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"> <?= $i ?> </a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php endif; ?>
</div>

<!-- Add Car Modal -->
<div class="modal fade" id="addCarModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add New Car</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="add_car" value="1">
          <div class="mb-3"><label class="form-label">Car Name</label><input type="text" name="name" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Brand</label><input type="text" name="brand" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Price</label><input type="number" name="price" step="0.01" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" required></textarea></div>
          <div class="mb-3"><label class="form-label">Image</label><input type="file" name="image" class="form-control" required></div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Add Car</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Car Modal -->
<div class="modal fade" id="editCarModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Car</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="update_car" value="1">
          <input type="hidden" name="id" id="edit-id">
          <div class="mb-3"><label class="form-label">Car Name</label><input type="text" name="name" id="edit-name" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Brand</label><input type="text" name="brand" id="edit-brand" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Price</label><input type="number" name="price" id="edit-price" step="0.01" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Description</label><textarea name="description" id="edit-description" class="form-control" required></textarea></div>
          <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <img id="edit-image-preview" src="" alt="Current Image" style="max-width: 150px; height: auto; border: 1px solid #ccc;">
          </div>
          <div class="mb-3">
            <label class="form-label">Change Image</label>
            <input type="file" name="image" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update Car</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
document.querySelectorAll('.edit-btn').forEach(button => {
  button.addEventListener('click', () => {
    document.getElementById('edit-id').value = button.dataset.id;
    document.getElementById('edit-name').value = button.dataset.name;
    document.getElementById('edit-brand').value = button.dataset.brand;
    document.getElementById('edit-price').value = button.dataset.price;
    document.getElementById('edit-description').value = button.dataset.description;
    const imageUrl = '../assets/images/' + (button.dataset.image || '');
    document.getElementById('edit-image-preview').src = imageUrl;
  });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

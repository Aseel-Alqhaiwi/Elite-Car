<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../config.php';

// Fetch all users from the database
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #111;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            margin-top: 60px;
        }

        h1 {
            color: #e63946;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(230, 57, 70, 0.3);
        }

        .table-dark {
            background-color: #1e1e1e;
            color: #fff;
        }

        .table-dark th,
        .table-dark td {
            border-color: #444;
        }

        .table thead {
            background-color: #f8d7da;
            color: #000;
        }

        .btn-danger {
            background-color: #e74c3c;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-back {
            margin-bottom: 20px;
            color: #fff;
            border-color: #e63946;
        }

        .btn-back:hover {
            background-color: #e63946;
        }
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
<!-- العنوان في المنتصف -->
<div class="text-center mb-4">
    <h1 class="text-danger">Manage Users</h1>
</div>

<!-- أزرار التحكم بنفس صف واحد -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="dashboard.php" class="btn btn-outline-light btn-custom">
        <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
    </a>

</div>




    <?php if (count($users) === 0): ?>
        <div class="alert alert-info">No users found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-dark table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id']; ?></td>
                            <td><?= htmlspecialchars($user['name']); ?></td>
                            <td><?= htmlspecialchars($user['email']); ?></td>
                            <td><?= $user['created_at']; ?></td>
                            <td>
                                <a href="delete_user.php?id=<?= $user['id']; ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this user?');">
                                   <i class="bi bi-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>

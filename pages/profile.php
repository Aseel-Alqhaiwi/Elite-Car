<?php
include '../config.php';
include '../includes/header.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: /CarElite/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

// Update user profile
$success = "";
$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? $user['name'];
    $email = $_POST['email'] ?? $user['email'];
    $password = $_POST['password'] ?? null;

    if (empty($name) || empty($email)) {
        $error = "Name and email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $update_sql = "UPDATE users SET name = ?, email = ?" . ($password ? ", password = ?" : "") . " WHERE id = ?";
        $params = [$name, $email];
        if ($password) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $params[] = $hashed_password;
        }
        $params[] = $user_id;

        $update_stmt = $conn->prepare($update_sql);
        if ($update_stmt->execute($params)) {
            $success = "Profile updated successfully!";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = "Failed to update profile.";
        }
    }
}
?>

<style>
body {
    background-color: #121416;
    color: #fff;
}
.card.profile-card {
    background-color: #1e1e1e;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.7);
    border: 1px solid #2c2c2c;
}

.profile-card h3 {
    color: #ff4d4d;
    text-align: center;
    font-weight: bold;
}

.form-label {
    color: #ccc; /* Improve label contrast */
    font-weight: 500;
}

.form-control {
    background-color: #2a2a2a;
    color: #fff;
    border: 1px solid #555;
    border-radius: 8px;
    padding: 10px 14px;
}

.form-control::placeholder {
    color: #999;
}

.btn-primary {
    background-color: #e63946;
    border: none;
    padding: 10px 20px;
}

.btn-primary:hover {
    background-color: #c9303e;
}

.btn-secondary {
    background-color: #3a3a3a;
    color: #fff;
    padding: 10px 20px;
    border: none;
}

.btn-secondary:hover {
    background-color: #555;
}

.d-flex.justify-content-between {
    gap: 10px;
}
</style>

<div class="container my-5">
    <div class="card profile-card mx-auto" style="max-width: 600px;">
        <h3 class="text-center mb-4">Edit My Profile</h3>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password <small class="text-muted">(optional)</small></label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Leave blank to keep current">
            </div>
            <div class="d-flex justify-content-between">
                <a href="/CarElite/pages/index.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

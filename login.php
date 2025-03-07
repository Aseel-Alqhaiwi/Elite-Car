<?php
include 'config.php';
session_start();

// CSRF token protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';
$success = $_GET['success'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF protection check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = "Invalid CSRF token.";
    } else {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $remember_me = isset($_POST['remember_me']);

        // Email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email format.";
        } 
        // Password validation (Minimum 6 chars, at least 1 uppercase, 1 lowercase, 1 number, 1 special character)
        elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/', $password)) {
            $message = "Password must be at least 6 characters, include 1 uppercase, 1 lowercase, 1 number, and 1 special character.";
        } else {
            try {
                // Fetch user by email
                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verify password
                if ($user && password_verify($password, $user['password'])) {
                    // Regenerate session ID for security
                    session_regenerate_id(true);

                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = htmlspecialchars($user['name']);

                    // Set persistent login cookie if "Remember Me" is checked
                    if ($remember_me) {
                        setcookie('user_id', $user['id'], time() + (86400 * 30), "/", "", true, true); // Secure & HttpOnly
                        setcookie('user_name', htmlspecialchars($user['name']), time() + (86400 * 30), "/", "", true, true);
                    }

                    header("Location: index.php");
                    exit();
                } else {
                    $message = "Invalid email or password.";
                }
            } catch (Exception $e) {
                $message = "Error: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Login</h1>
        <?php if ($success): ?>
            <div class="alert alert-success">Registration successful! Please log in.</div>
        <?php endif; ?>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="remember_me" class="form-check-input" id="remember_me">
                <label class="form-check-label" for="remember_me">Remember Me</label>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <hr>
        <p>Don't have an account? <a href="register.php" class="btn btn-secondary btn-sm">Register</a></p>
    </div>
</body>
</html>

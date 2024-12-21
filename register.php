<?php
include 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Password strength validation
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $message = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Insert user into the database
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $email, $hashed_password]);
            header("Location: login.php?success=1"); // Redirect to login page with success indicator
            exit();
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <script>
        // Password strength checker
        function checkPasswordStrength() {
            const password = document.getElementById("password").value;
            const strengthIndicator = document.getElementById("strength-indicator");
            const regexStrong = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (regexStrong.test(password)) {
                strengthIndicator.textContent = "Strong";
                strengthIndicator.style.color = "green";
            } else {
                strengthIndicator.textContent = "Weak (At least 8 characters, include uppercase, lowercase, number, special character)";
                strengthIndicator.style.color = "red";
            }
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1>Register</h1>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" oninput="checkPasswordStrength()" required>
                <small id="strength-indicator" class="form-text"></small>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <hr>
        <p>Already have an account? <a href="login.php" class="btn btn-secondary btn-sm">Login</a></p>
    </div>
</body>
</html>

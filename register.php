<?php
include 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Name validation (must contain at least two words)
    if (!preg_match('/^\s*([A-Za-z]+)\s+([A-Za-z]+)\s*$/', $name)) {
        $message = "Please enter your full name (first and last name).";
    } 
    // Email validation
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } 
    // Password validation (At least 8 characters, 1 uppercase, 1 lowercase, 1 number, 1 special character)
    elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $message = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } else {
        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Insert user into the database with a prepared statement
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $email, $hashed_password]);

            // Redirect to login page with success message
            header("Location: login.php?success=1");
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

        // Name validation on input
        function validateName() {
            const nameInput = document.getElementById("name").value.trim();
            const nameFeedback = document.getElementById("name-feedback");
            const nameRegex = /^\s*([A-Za-z]+)\s+([A-Za-z]+)\s*$/;

            if (nameRegex.test(nameInput)) {
                nameFeedback.textContent = "Valid name format";
                nameFeedback.style.color = "green";
            } else {
                nameFeedback.textContent = "Enter at least first and last name";
                nameFeedback.style.color = "red";
            }
        }
        function validateEmail() {
    const emailInput = document.getElementById("email").value.trim();
    const emailFeedback = document.getElementById("email-feedback");
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (emailRegex.test(emailInput)) {
        emailFeedback.textContent = "Valid email format";
        emailFeedback.style.color = "green";
    } else {
        emailFeedback.textContent = "Enter a valid email (e.g., user@example.com)";
        emailFeedback.style.color = "red";
    }
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
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" oninput="validateName()" required>
                <small id="name-feedback" class="form-text"></small>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" oninput="validateEmail() required>
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

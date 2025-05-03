<?php
include '../includes/header.php';
include '../config.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!preg_match('/^\s*([A-Za-z]+)\s+([A-Za-z]+)\s*$/', $name)) {
        $message = "Please enter your full name (first and last name).";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/', $password)) {
        $message = "Password must include uppercase, lowercase, number, and special character.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        try {
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $email, $hashed_password]);
            header("Location: login.php?success=1");
            exit();
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!-- 🌑 Dark Theme Registration UI -->
<style>
    body {
        background-color: #111;
        color: #fff;
        font-family: 'Segoe UI', sans-serif;
    }

    .register-box {
        background-color: #1e1e1e;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
    }

    .register-box h3 {
        color: #e63946;
        font-weight: bold;
    }

    .form-control {
        background-color: #2a2a2a;
        border: 1px solid #444;
        color: #fff;
    }

    .form-control:focus {
        border-color: #e63946;
        box-shadow: 0 0 0 0.2rem rgba(230, 57, 70, 0.25);
    }

    .form-text {
        font-size: 0.85rem;
        color: #ccc;
    }

    .btn-primary {
        background-color: #e63946;
        border: none;
        font-weight: bold;
        border-radius: 10px;
    }

    .btn-primary:hover {
        background-color: #c62b3f;
    }

    .text-light a {
        color: #e63946;
        font-weight: bold;
    }

    .text-light a:hover {
        text-decoration: underline;
    }
</style>

<div class="container my-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="register-box w-100" style="max-width: 500px;">
        <h3 class="text-center mb-4">Create an Account</h3>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" oninput="validateName()" required>
                <small id="name-feedback" class="form-text"></small>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" oninput="validateEmail()" required>
                <small id="email-feedback" class="form-text"></small>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" oninput="checkPasswordStrength()" required>
                <small id="strength-indicator" class="form-text"></small>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <div class="text-center mt-3">
            <p class="text-light">Already have an account?
                <a href="login.php">Login here</a>
            </p>
        </div>
    </div>
</div>

<script>
    function checkPasswordStrength() {
        const password = document.getElementById("password").value;
        const strengthIndicator = document.getElementById("strength-indicator");
        const regexStrong = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;
        if (regexStrong.test(password)) {
            strengthIndicator.textContent = "Strong";
            strengthIndicator.style.color = "limegreen";
        } else {
            strengthIndicator.textContent = "Weak (8+ chars, upper, lower, number, special)";
            strengthIndicator.style.color = "orange";
        }
    }

    function validateName() {
        const name = document.getElementById("name").value.trim();
        const feedback = document.getElementById("name-feedback");
        const regex = /^\s*([A-Za-z]+)\s+([A-Za-z]+)\s*$/;
        if (regex.test(name)) {
            feedback.textContent = "Looks good!";
            feedback.style.color = "lightgreen";
        } else {
            feedback.textContent = "Enter first and last name.";
            feedback.style.color = "orange";
        }
    }

    function validateEmail() {
        const email = document.getElementById("email").value.trim();
        const feedback = document.getElementById("email-feedback");
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (regex.test(email)) {
            feedback.textContent = "Valid email.";
            feedback.style.color = "lightgreen";
        } else {
            feedback.textContent = "Invalid email format.";
            feedback.style.color = "orange";
        }
    }
</script>

<?php include '../includes/footer.php'; ?>

<?php
include '../includes/header.php';
include '../config.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';
$success = $_GET['success'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = "Invalid CSRF token.";
    } else {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $remember_me = isset($_POST['remember_me']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email format.";
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/', $password)) {
            $message = "Password must meet complexity requirements.";
        } else {
            try {
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = htmlspecialchars($user['name']);

                    if ($remember_me) {
                        setcookie('user_id', $user['id'], time() + (86400 * 30), "/", "", true, true);
                        setcookie('user_name', htmlspecialchars($user['name']), time() + (86400 * 30), "/", "", true, true);
                    }

                    header("Location: ../pages/index.php");
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

<!-- 🌙 Login UI -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background-color: #111;
        color: #fff;
        font-family: 'Segoe UI', sans-serif;
    }

    .login-box {
        background-color: #1e1e1e;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.05);
    }

    .login-box h2 {
        color: #e63946;
        font-weight: bold;
        text-align: center;
    }

    .form-group-icon {
        position: relative;
    }

    .form-icon {
        position: absolute;
        top: 50%;
        left: 12px;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 1.1rem;
    }

    .form-group-icon input {
        padding-left: 40px;
    }

    .form-control {
        background-color: #2a2a2a;
        border: 1px solid #444;
        color: #f1f1f1;
    }

    .form-control::placeholder {
        color: #aaa;
    }

    .form-control:focus {
        border-color: #e63946;
        box-shadow: 0 0 0 0.2rem rgba(230, 57, 70, 0.25);
    }

    .form-check-label {
        color: #ccc;
    }

    .btn-primary {
        background-color: #e63946;
        border: none;
    }

    .btn-primary:hover {
        background-color: #c9303e;
    }

    .text-muted a {
        color: #e63946;
    }

    .text-muted a:hover {
        text-decoration: underline;
    }
</style>

<div class="container my-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="login-box w-100" style="max-width: 450px;">
        <h2 class="mb-4">Login</h2>

        <?php if ($success): ?>
            <div class="alert alert-success">Registration successful. Please log in.</div>
        <?php endif; ?>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <div class="mb-3 form-group-icon">
                <i class="bi bi-envelope form-icon"></i>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <div class="mb-3 form-group-icon">
                <i class="bi bi-lock form-icon"></i>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
                <label class="form-check-label" for="remember_me">Remember Me</label>
            </div>

            <button class="btn btn-primary w-100" type="submit">Login</button>
        </form>

        <hr>
        <p class="text-center" style="color: #ccc;">Don't have an account? <a href="register.php" style="color: #e63946;">Register</a></p>
        </div>
</div>

<?php include '../includes/footer.php'; ?>

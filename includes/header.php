<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? "Elite Cars Marketplace"; ?></title>

    <!-- Bootstrap CSS (CDN - Always Up-to-Date) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <!-- Logo and Brand -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="assets/images/logo.png" alt="Elite Cars Logo" style="width: 80px; height: 80px; margin-right: 15px;">
                <span style="font-size: 1.5rem;">Elite Cars Marketplace</span>
            </a>

            <!-- Mobile Menu Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto text-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger text-light" href="logout.php" style="color: white;">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JavaScript (Fixed: Now Using CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

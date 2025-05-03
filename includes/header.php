<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $page_title ?? "Elite Cars Marketplace"; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }

    .navbar {
      background: #111;
      box-shadow: 0 2px 10px rgba(0,0,0,0.5);
    }

    .navbar-brand {
      font-weight: bold;
      font-size: 1.6rem;
      color: #e63946;
      display: flex;
      align-items: center;
    }

    .navbar-brand img {
      height: 45px;
      margin-right: 10px;
      border-radius: 50%;
    }

    .nav-link {
      color: #ddd !important;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .nav-link:hover, .nav-link.active {
      color: #e63946 !important;
    }

    .btn-login {
      background-color: transparent;
      border: 2px solid #e63946;
      color: #fff;
      padding: 6px 15px;
      border-radius: 20px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-login:hover {
      background: #e63946;
      color: #fff;
    }

    .btn-register {
      background: #e63946;
      border: 2px solid #e63946;
      color: #fff;
      padding: 6px 15px;
      border-radius: 20px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-register:hover {
      background: transparent;
      color: #e63946;
    }

    .navbar-toggler {
      border-color: #888;
    }

    @media (max-width: 991px) {
      .btn-login, .btn-register {
        margin-bottom: 10px;
        width: 100%;
      }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand" href="/CarElite/pages/index.php">
    <img src="/CarElite/assets/images/Logo.png" alt="Elite Cars"> Elite Cars
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="mainNav">
      <ul class="navbar-nav align-items-center me-3">
        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="/CarElite/pages/index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>" href="/CarElite/pages/contact.php">Contact</a>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="/CarElite/pages/profile.php">
              <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['user_name']); ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" href="/CarElite/pages/logout.php"><i class="bi bi-box-arrow-right"></i></a>
          </li>
        <?php else: ?>
          <li class="nav-item me-2">
            <a class="btn btn-login" href="/CarElite/pages/login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-register" href="/CarElite/pages/register.php">Register</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

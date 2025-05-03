<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../config.php';

$cars_count = $conn->query("SELECT COUNT(*) FROM cars")->fetchColumn();
$users_count = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #0d0d0d;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
        }
        .dashboard-box {
            background: linear-gradient(145deg, #1a1a1a, #141414);
            border-radius: 16px;
            padding: 30px;
            margin-top: 50px;
            box-shadow: 0 4px 30px rgba(0, 255, 200, 0.2);
        }
        .card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.2);
        }
        .card.bg-cars {
            background: linear-gradient(135deg, #00bcd4, #1de9b6);
        }
        .card.bg-users {
            background: linear-gradient(135deg, #ff6ec4, #7873f5);
        }
        .card h5 {
            font-weight: 600;
            color: #f1f1f1;
        }
        .card h3 {
            font-size: 2.2rem;
            font-weight: 700;
        }
        .btn-custom {
            border-radius: 30px;
            font-weight: 600;
            padding: 12px 26px;
            font-size: 1rem;
            transition: 0.3s ease-in-out;
        }
        .btn-cars {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: #fff;
            border: none;
        }
        .btn-cars:hover {
            background: linear-gradient(135deg, #0072ff, #00c6ff);
        }
        .btn-users {
            background: linear-gradient(135deg, #ffb347, #ffcc33);
            color: #000;
            border: none;
        }
        .btn-users:hover {
            background: linear-gradient(135deg, #ffcc33, #ffb347);
            color: #000;
        }
        .btn-logout {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            color: #fff;
            border: none;
        }
        .btn-logout:hover {
            background: linear-gradient(135deg, #ff4b2b, #ff416c);
        }
        .icon-left { margin-right: 8px; }
        .progress-circle {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }
        .progress-circle svg {
            width: 120px;
            height: 120px;
            transform: rotate(-90deg);
        }
        .progress-circle circle {
            fill: none;
            stroke-width: 12;
            stroke: #333;
            stroke-linecap: round;
        }
        .progress-circle circle:nth-child(2) {
            stroke: #fff;
            stroke-dasharray: 314;
            stroke-dashoffset: 314;
            transition: stroke-dashoffset 1.2s ease;
        }
        .progress-circle .number {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: bold;
            font-size: 1.3rem;
        }
    </style>
</head>
<body>
    <div class="container dashboard-box">
        <h1 class="text-center mb-4">🚀 Welcome, Admin!</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="card bg-cars text-white mb-4">
                    <div class="card-body text-center">
                        <h5><i class="bi bi-car-front icon-left"></i>Total Cars</h5>
                        <div class="progress-circle" data-value="<?= $cars_count; ?>" id="circleCars">
                            <svg>
                                <circle cx="60" cy="60" r="50"></circle>
                                <circle cx="60" cy="60" r="50" id="carsProgress"></circle>
                            </svg>
                            <div class="number" id="carsCounter">0</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-users text-white mb-4">
                    <div class="card-body text-center">
                        <h5><i class="bi bi-people icon-left"></i>Total Users</h5>
                        <div class="progress-circle" data-value="<?= $users_count; ?>" id="circleUsers">
                            <svg>
                                <circle cx="60" cy="60" r="50"></circle>
                                <circle cx="60" cy="60" r="50" id="usersProgress"></circle>
                            </svg>
                            <div class="number" id="usersCounter">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="manage_cars.php" class="btn btn-cars btn-custom me-2">
                <i class="bi bi-car-front icon-left"></i> Manage Cars
            </a>
            <a href="manage_users.php" class="btn btn-users btn-custom me-2">
                <i class="bi bi-people icon-left"></i> Manage Users
            </a>
            <a href="logout.php" class="btn btn-logout btn-custom">
                <i class="bi bi-box-arrow-right icon-left"></i> Logout
            </a>
        </div>
    </div>
    <script>
        function animateCounter(id, target) {
            const el = document.getElementById(id);
            let count = 0;
            const step = Math.ceil(target / 50);
            const update = () => {
                count += step;
                if (count >= target) {
                    el.textContent = target;
                } else {
                    el.textContent = count;
                    requestAnimationFrame(update);
                }
            };
            update();
        }

        function animateCircle(circleId, value) {
            const circle = document.getElementById(circleId);
            const max = 100;
            const percentage = Math.min((value / max) * 100, 100);
            const offset = 314 - (314 * percentage / 100);
            circle.style.strokeDashoffset = offset;
        }

        document.addEventListener("DOMContentLoaded", () => {
            const carsValue = parseInt(document.getElementById("circleCars").dataset.value);
            const usersValue = parseInt(document.getElementById("circleUsers").dataset.value);

            animateCounter("carsCounter", carsValue);
            animateCounter("usersCounter", usersValue);
            animateCircle("carsProgress", carsValue);
            animateCircle("usersProgress", usersValue);
        });
    </script>
</body>
</html>

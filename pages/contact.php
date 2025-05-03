<?php
$activePage = 'contact';
$useDarkHeader = true; 
include '../includes/header.php';
include '../config.php';

$name = $email = $message = $ticket_type = $success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    $ticket_type = trim($_POST['ticket_type']);

    if (empty($name) || empty($email) || empty($message) || empty($ticket_type)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $sql = "INSERT INTO messages (name, email, message, ticket_type) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$name, $email, $message, $ticket_type])) {
            $success = "Your message has been sent successfully!";
            $name = $email = $message = $ticket_type = "";
        } else {
            $error = "Failed to send your message. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact Us</title>
    <style>
        body {
            background-color: #121416;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
        }
        .form-control, .form-select {
            background-color: #1e1e1e;
            border: 1px solid #444;
            color: #f1f1f1;
        }
        .form-control::placeholder {
            color: #aaa;
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }
        .btn-primary:hover {
            background-color: #084298;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .alert {
            border-radius: 10px;
        }
        .container-box {
            background-color: #1c1e22;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.05);
        }
        .form-icon {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #aaa;
        }
        .form-group-icon {
            position: relative;
        }
        .form-group-icon input,
        .form-group-icon select,
        .form-group-icon textarea {
            padding-left: 40px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="container-box mx-auto" style="max-width: 600px;">
        <h2 class="text-center mb-2 text-white">Contact Us</h2>
        <p class="text-center" style="color: #bbb;">Feel free to get in touch with us for any inquiries.</p>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form action="contact.php" method="POST" novalidate>
            <div class="mb-3 form-group-icon">
                <i class="bi bi-person form-icon"></i>
                <input type="text" name="name" id="name" class="form-control" placeholder="Your Name" value="<?= htmlspecialchars($name); ?>" required>
            </div>
            <div class="mb-3 form-group-icon">
                <i class="bi bi-envelope form-icon"></i>
                <input type="email" name="email" id="email" class="form-control" placeholder="Your Email" value="<?= htmlspecialchars($email); ?>" required>
            </div>
            <div class="mb-3 form-group-icon">
                <i class="bi bi-list form-icon"></i>
                <select name="ticket_type" id="ticket_type" class="form-select" required>
                    <option value="">-- Select Type --</option>
                    <option value="Inquiry" <?= $ticket_type == 'Inquiry' ? 'selected' : ''; ?>>Inquiry</option>
                    <option value="Technical Support" <?= $ticket_type == 'Technical Support' ? 'selected' : ''; ?>>Technical Support</option>
                    <option value="Booking Request" <?= $ticket_type == 'Booking Request' ? 'selected' : ''; ?>>Booking Request</option>
                    <option value="Complaint" <?= $ticket_type == 'Complaint' ? 'selected' : ''; ?>>Complaint</option>
                    <option value="Feedback" <?= $ticket_type == 'Feedback' ? 'selected' : ''; ?>>Feedback</option>
                </select>
            </div>
            <div class="mb-3 form-group-icon">
                <i class="bi bi-chat-dots form-icon"></i>
                <textarea name="message" id="message" class="form-control" rows="5" placeholder="Write your message here..." required><?= htmlspecialchars($message); ?></textarea>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary px-4">Send</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>

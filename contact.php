<?php
include 'config.php';
session_start();

// Initialize variables
$name = "";
$email = "";
$message = "";
$success = "";
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Validate form inputs
    if (empty($name) || empty($email) || empty($message)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Save the message to the database
        $sql = "INSERT INTO messages (name, email, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$name, $email, $message])) {
            $success = "Your message has been sent successfully!";
            $name = $email = $message = ""; // Clear form
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
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Contact Us</h1>
        <p class="text-center">Feel free to get in touch with us for any inquiries.</p>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form action="contact.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Your Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($name); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Your Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($email); ?>" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Your Message</label>
                <textarea name="message" id="message" class="form-control" rows="5" required><?= htmlspecialchars($message); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>

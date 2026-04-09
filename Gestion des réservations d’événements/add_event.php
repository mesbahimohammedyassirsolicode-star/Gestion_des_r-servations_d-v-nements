<?php
// Simple, beginner-friendly Add Event page using mysqli (procedural)
// - Only admins can add events
// - Basic validation and friendly messages

session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "gestion_réservations");
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
$conn->set_charset('utf8');

// Check if current user is admin
$stmtRole = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmtRole->bind_param('i', $_SESSION['id']);
$stmtRole->execute();
$resultRole = $stmtRole->get_result();
$user = $resultRole->fetch_assoc();
$stmtRole->close();

if (!$user || ($user['role'] ?? '') !== 'admin') {
    $conn->close();
    die('Access denied. You must be an administrator to add events.');
}

// Initialize variables for the form
$title = '';
$date_event = '';
$nbPlaces = 1;
$price = 0;
$location = '';
$errors = [];
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get values and trim whitespace
    $title = trim($_POST['title'] ?? '');
    $date_event = $_POST['date_event'] ?? '';
    $nbPlaces = isset($_POST['nbPlaces']) ? (int) $_POST['nbPlaces'] : 0;
    $price = isset($_POST['price']) ? (float) $_POST['price'] : 0;
    $location = trim($_POST['location'] ?? '');

    // Simple validation
    if ($title === '') {
        $errors[] = 'Title is required.';
    }
    if ($date_event === '') {
        $errors[] = 'Date is required.';
    }
    if ($location === '') {
        $errors[] = 'Location is required.';
    }
    if ($nbPlaces <= 0) {
        $errors[] = 'Number of places must be at least 1.';
    }
    if ($price < 0) {
        $errors[] = 'Price cannot be negative.';
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO events (title, date_event, nbPlaces, price, location) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            // bind_param types: s (string), s (string), i (int), d (double), s (string)
            $stmt->bind_param('ssids', $title, $date_event, $nbPlaces, $price, $location);

            if ($stmt->execute()) {
                $success = 'Event added successfully.';
                // Clear the form values
                $title = '';
                $date_event = '';
                $nbPlaces = 1;
                $price = 0;
                $location = '';
            } else {
                $errors[] = 'Insert failed: ' . $stmt->error;
            }

            $stmt->close();
        } else {
            $errors[] = 'Database prepare failed: ' . $conn->error;
        }
    }
}

// Close the connection (optional, PHP will close it at script end)
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Add Event</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        label { display:block; margin-top:10px; }
        input[type="text"], input[type="date"], input[type="number"] { width:100%; padding:8px; box-sizing:border-box; }
        .errors { background:#ffe6e6; padding:10px; border:1px solid #ffcccc; }
        .success { background:#e6ffe6; padding:10px; border:1px solid #ccffcc; }
        button { margin-top:12px; padding:10px 16px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Add Event (Beginner friendly)</h1>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <strong>Please fix these errors:</strong>
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" required>

        <label>Date</label>
        <input type="date" name="date_event" value="<?= htmlspecialchars($date_event) ?>" required>

        <label>Number of places</label>
        <input type="number" name="nbPlaces" min="1" value="<?= htmlspecialchars($nbPlaces) ?>" required>

        <label>Price (DH)</label>
        <input type="number" name="price" step="0.01" min="0" value="<?= htmlspecialchars($price) ?>" required>

        <label>Location</label>
        <input type="text" name="location" value="<?= htmlspecialchars($location) ?>" required>

        <button type="submit">Add Event</button>
    </form>

    <p><a href="admin.php">Back to Admin Dashboard</a></p>
</div>
</body>
</html>

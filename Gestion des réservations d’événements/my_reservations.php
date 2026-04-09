<?php
session_start();
require 'config.php';

// Require login
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$user_id =  $_SESSION['id'];

try {
    $sql = "SELECT r.id AS reservation_id, e.id AS event_id, e.title, e.date_event, e.location, e.price
            FROM reservations r
            JOIN events e ON r.event_id = e.id
            WHERE r.user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur: " . $e->getMessage();
    $reservations = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Reservations</title>
<link rel="stylesheet" href="styleb.css">
</head>
<body>
<header>
    <nav class="nav-btn">
        <a href="login.php">Login</a>
        <a href="signup.php">Register</a>
        <a href="index.php">Home</a>
        <a href="my_reservations.php">My Reservations</a>
    </nav>
</header>

<main>
    <div class="card-container">
        <h2>My Reservations</h2>

    <p>
        <?= $error ?>
    </p>

        <?php if (count($reservations) === 0): ?>
            <p>You have no reservations yet.</p>
        <?php else: ?>
            <table class="reservations-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['reservation_id']) ?></td>
                        <td><?= htmlspecialchars($r['title']) ?></td>
                        <td><?= htmlspecialchars($r['date_event']) ?></td>
                        <td><?= htmlspecialchars($r['location']) ?></td>
                        <td><?= htmlspecialchars($r['price']) ?> DH</td>
                        <td><a class="btn" href="booking.php?id=<?= htmlspecialchars($r['event_id']) ?>">View Event</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>

</body>
</html>

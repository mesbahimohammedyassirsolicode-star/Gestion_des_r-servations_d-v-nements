<?php 
session_start();
require 'config.php';

if(!isset($_GET['id'])) {
    die("No event selected");
}

$id = (int) $_GET['id'];

$message = "";
$messageType = "";

try {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        die("Event not found.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!isset($_SESSION['id'])) {
            header("Location: login.php");
            exit();
        }

        if ($event['nbPlaces'] <= 0) {
            $message = "Sold out!";
            $messageType = "error";
        } else {

            $pdo->beginTransaction();

            $stmt2 = $pdo->prepare("INSERT INTO reservations (user_id, event_id) VALUES (:user_id, :event_id)");
            $stmt2->execute([
                'user_id' => $_SESSION['id'],
                'event_id' => $id
            ]);

            $stmt3 = $pdo->prepare("UPDATE events SET nbPlaces = nbPlaces - 1 WHERE id = :id");
            $stmt3->execute(['id' => $id]);

            $pdo->commit();

            $message = "Reservation confirmed!";
            $messageType = "success";

            // refresh
            $stmt->execute(['id' => $id]);
            $event = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

} catch (PDOException $e) {
    $pdo->rollBack();
    $message = "Error occurred!";
    $messageType = "error";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Booking</title>
<link rel="stylesheet" href="styleb.css">
</head>

<body>

<header>
    <nav class="nav-btn">
        <a href="login.php">Login</a>
        <a href="signup.php">Register</a>
        <a href="index.php">Home</a>
    </nav>
</header>

<main>
    <div class="card-container">

        <h2><?= htmlspecialchars($event['title']) ?></h2>

        <p><strong>Date:</strong> <?= htmlspecialchars($event['date_event']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
        <p><strong>Price:</strong> <?= htmlspecialchars($event['price']) ?> DH</p>
        <p><strong>Available places:</strong> <?= $event['nbPlaces'] ?></p>

        <?php if ($message): ?>
            <p class="<?= $messageType ?>"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST">
            <button 
                <?= $event['nbPlaces'] <= 0 ? 'disabled' : '' ?>>
                <?= $event['nbPlaces'] <= 0 ? 'Sold Out' : 'Book Now' ?>
            </button>
        </form>

    </div>
</main>

</body>
</html>
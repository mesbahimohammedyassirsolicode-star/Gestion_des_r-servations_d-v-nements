<?php
session_start();
require 'config.php';
$sql = "SELECT * FROM `events`;";
$stmt = $pdo->query($sql);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="nav-btn">
            <a href="login.php">login</a>
            <a href="signup.php">Register</a>
            <a href="index.php">Home</a>
        </nav>
    </header>
    <main>
        <div class="card-container">
        <?php foreach($events as $event): ?>
        <div class="card">
            <h2>
                Name  :<?= $event['title'] ?>
            </h2>
                <p>
                    Date event:<?= $event['date_event'] ?>
                </p>
                <p>
                   Places: <?= $event['nbPlaces'] ?>
                </p>
                <p>
                    Price :<?= $event['price'] ?>
                </p>
                <p>
                    Location: <?= $event['location'] ?>
                </p>


             <a class="btn" href="booking.php?id=<?php echo $event['id'];?>">book</a>

        </div>
        <?php endforeach; ?>
        </div>
    </main>
    <footer></footer>
</body>
</html>
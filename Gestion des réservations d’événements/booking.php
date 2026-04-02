<?php 
require 'config.php';

if(!isset($_GET['id'])) {
    die("No event selected");
}
$id = ($_GET['id']);

$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

$events = $stmt->fetch(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>booking</title>
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
            
</body>
</html>
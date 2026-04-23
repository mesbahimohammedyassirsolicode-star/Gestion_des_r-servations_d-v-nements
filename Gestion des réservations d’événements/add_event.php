<?php
session_start();
require 'config.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$stmtRole = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmtRole->execute([$_SESSION['id']]);
$user = $stmtRole->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['role'] !== 'admin') {
    die('Access denied');
}

$title = '';
$date_event = '';
$nbPlaces = 1;
$price = 0;
$location = '';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $date_event = $_POST['date_event'] ?? '';
    $nbPlaces =  ($_POST['nbPlaces'] ?? 0);
    $price =  ($_POST['price'] ?? 0);
    $location = trim($_POST['location'] ?? '');

    if ($title === '') 
        $errors[] = 'Title is required';
    if ($date_event === '') 
        $errors[] = 'Date is required';
    if ($location === '')
         $errors[] = 'Location is required';
    if ($nbPlaces <= 0) 
        $errors[] = 'Places must be > 0';
    if ($price < 0)
         $errors[] = 'Price invalid';

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO events (title, date_event, nbPlaces, price, location)
            VALUES (?, ?, ?, ?, ?)
        ");

        if ($stmt->execute([$title, $date_event, $nbPlaces, $price, $location])) {
            $success = "Event added successfully";

            $title = '';
            $date_event = '';
            $nbPlaces = 1;
            $price = 0;
            $location = '';
        } else {
            $errors[] = "Insert failed";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Event</title>
</head>
<body>

<h2>Add Event</h2>

<?php if (!empty($errors)): ?>
    <div style="color:red;">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div style="color:green;">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<form method="POST">

    Title:<br>
    <input type="text" name="title" value="<?= htmlspecialchars($title) ?>"><br><br>

    Date:<br>
    <input type="date" name="date_event" value="<?= htmlspecialchars($date_event) ?>"><br><br>

    Places:<br>
    <input type="number" name="nbPlaces" value="<?= htmlspecialchars($nbPlaces) ?>"><br><br>

    Price:<br>
    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($price) ?>"><br><br>

    Location:<br>
    <input type="text" name="location" value="<?= htmlspecialchars($location) ?>"><br><br>

    <button type="submit">Add</button>

</form>

</body>
</html>
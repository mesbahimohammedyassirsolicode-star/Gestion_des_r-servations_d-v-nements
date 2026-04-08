<?php
session_start();
require 'config.php';

// check admin
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['id']]);
$user = $stmt->fetch();

if (!$user || $user['role'] !== 'admin') {
    die("Access denied");
}

if(isset($_POST['add'])){
    $stmt = $pdo->prepare("
        INSERT INTO events(title,date_event,location,nbPlaces,price)
        VALUES(:t,:d,:l,:p,:pr)
    ");

    $stmt->execute([
        't'=>$_POST['title'],
        'd'=>$_POST['date'],
        'l'=>$_POST['location'],
        'p'=>$_POST['places'],
        'pr'=>$_POST['price']
    ]);
}

$search = $_GET['search'] ?? "";

$sql = "
SELECT e.*, COUNT(r.id) AS total_reservations
FROM events e
LEFT JOIN reservations r ON e.id = r.event_id
WHERE e.title LIKE :search
GROUP BY e.id
ORDER BY e.date_event DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>

<h1>Admin Dashboard</h1>


<form method="GET">
    <input type="text" name="search" placeholder="Search event..." value="<?= htmlspecialchars($search) ?>">
    <button>Search</button>
</form>

<hr>


<h3>Add Event</h3>
<form method="POST">
    <input name="title" placeholder="Title" required>
    <input type="date" name="date" required>
    <input name="location" placeholder="Location" required>
    <input name="places" placeholder="Places" required>
    <input name="price" placeholder="Price" required>
    <button name="add">Add</button>
</form>

<hr>

<h3>All Events</h3>

<table border="1" cellpadding="10">
<tr>
    <th>Title</th>
    <th>Date</th>
    <th>Places</th>
    <th>Bookings</th>
    <th>Status</th>
</tr>

<?php foreach($events as $e): ?>
<tr>
    <td><?= htmlspecialchars($e['title']) ?></td>
    <td><?= $e['date_event'] ?></td>
    <td><?= $e['nbPlaces'] ?></td>
    <td><?= $e['total_reservations'] ?></td>
    <td>
        <?php if($e['nbPlaces'] == 0): ?>
             Sold Out
        <?php else: ?>
             Available
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
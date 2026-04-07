<?php 
require 'config.php';
session_start();
$erreur = "";
if(isset($_POST['ok']))
    {
        $email = htmlspecialchars($_POST['email']);
        $password=htmlspecialchars($_POST['password']);
        // fetch the data and verfy 
         $sql = "SELECT * FROM users WHERE email = :email";
        $stm = $pdo->prepare($sql);
    $stm->execute(["email" => $email]);
        $user = $stm->fetch(PDO::FETCH_ASSOC);
        if($user){

        if(password_verify($password,$user['password'])){
            $_SESSION['user']= $user['name'];
            $_SESSION['id']=$user['id'];
            header("location: index.php");
            exit;
        }else{
            $erreur="password isnt not correct";
        }
        }else{
            $erreur="email not found";
        }
        }
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <form  method="POST">
        <label>email</label>
        <input type="text" name="email" placeholder="saisie  l'email">
        <label>mote de passe</label>
        <input type="password" name="password" placeholder="saisie  le mot de passe">
         <button type="submit" name="ok">login</button>
    </form>
    <p> <?= $erreur ?></p>
</body>
</html>

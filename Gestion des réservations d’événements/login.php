<?php 
require 'config.php';
session_start();
$erreur = false;
if(isset($_POST['ok']))
    {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password=htmlspecialchars($_POST['password']);
        
        
        
        
        
        
        
        
        
        
        
        
        
        }






?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>
<body>
    <form  method="post">
        <label>email</label>
        <input type="text" name="email" placeholder="saisie  l'email">
        <label>mote de passe</label>
        <input type="password" name="password" placeholder="saisie  le mot de passe">
         <button type="submit" name="ok">login</button>





    </form>
</body>
</html>

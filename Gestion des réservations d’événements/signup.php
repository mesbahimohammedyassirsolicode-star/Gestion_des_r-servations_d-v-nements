<?php 
require 'config.php';
session_start();
$erreur = false;
if(isset($_POST['ok']))
    {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password=htmlspecialchars($_POST['password']);
        $password2=htmlspecialchars($_POST['password2']);


         if(empty($name) || empty($email) ||  empty($password)){
            echo "<p>all the filieds are required</p> ";
                $erreur = true;
         }else
         {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                echo "<p>email invalide</p>";
                $erreur = true;
            }
            if(strlen($password)<=8){
echo "passowrd are low than we expect";
                $erreur = true;

}
if (!preg_match("/^[a-zA-Z0-9]+$/",$password)) {
      echo"<p>Only letters and numbers allowed</p>";
      $erreur=true;
      }
        if($password !== $password2){
    echo "<p>password not match</p>";
    $erreur=true;
    }
    }
    if($erreur == false)
        {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, password)
                    VALUES (:name,:email,:password)";

            $stm = $pdo->prepare($sql);

           try {
    $stm->execute([
        "name" => $name,
        "email" => $email,
        "password" => $password
    ]);

    echo "<p>user add successfully</p>";
    header("Location: login.php");
    exit;

} catch(PDOException $e) {

    if($e){
        echo "<p>Email already used </p>";
    } else {
        echo "Erreur : " . $e->getMessage();
    }
}
        }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sign up</title>
</head>
<body>
    <form method="post">
        <label >Name:</label>
        <input type="text" name="name" placeholder="enter your name">
        <label>email</label>
        <input type="text" name="email" placeholder="entery the email ">
        <label>mote de passe</label>
        <input type="password" name="password" placeholder="enter the password">
        <label>confirme le mote pass</label>
        <input type="password" name="password2"  placeholder="confirme your password">
        <button type="submit" name="ok">creat an account</button>

    </form>
</body>
</html>

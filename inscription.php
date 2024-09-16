<?php

$host = 'localhost';
$dbname = 'utilisateurs';
$user = 'mysqlUser';
$pass = 'azertyuiop';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $username = $_POST['username'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $password = $_POST['password'];
        $mail = $_POST['mail'];

        
        $sql = "INSERT INTO user (username, nom, prenom, mail, password) 
                VALUES (:username, :nom, :prenom, :mail, :password)";
        $stmt = $pdo->prepare($sql);

        
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':mail', $mail);

        
        if ($stmt->execute()) {
            header("Location: connection.html");
            exit();
        } else {
            header("Location: index.html");
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
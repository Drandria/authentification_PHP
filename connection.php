<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    $host = 'localhost';
    $dbname = 'utilisateurs';
    $user = 'mysqlUser';
    $pass = 'azertyuiop';

    try {

        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM user WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($password == $row['password']) {
                // Démarrer une session utilisateur
                $_SESSION['username'] = $username;
                echo "Connexion réussie. Bienvenue " . $username . "!";
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Nom d'utilisateur non trouvé.";
        }

    } catch(PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }

    $conn = null;
}
?>

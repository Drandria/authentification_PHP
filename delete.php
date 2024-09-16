<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: connection.html");
    exit();
}

$host = 'localhost';
$dbname = 'utilisateurs';
$user = 'mysqlUser';
$pass = 'azertyuiop';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "DELETE FROM etudiant WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        header("Location: dashboard.php");
        exit();

    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "ID manquant.";
    exit();
}
?>

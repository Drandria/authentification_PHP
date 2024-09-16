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

        $sql = "SELECT etudiant.*, parcours.nom AS parcours 
                FROM etudiant 
                JOIN parcours ON etudiant.parcours_id = parcours.id 
                WHERE etudiant.id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "ID manquant.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Détails de l'utilisateur</title>
</head>
<body>
    <div class="container">
        <h1>Détails de l'utilisateur</h1>
        <p>ID : <?php echo $etudiant['id']; ?></p>
        <p>Nom : <?php echo $etudiant['nom']; ?></p>
        <p>Prénom : <?php echo $etudiant['prenom']; ?></p>
        <p>Date de naissance : <?php echo $etudiant['date_de_naissance']; ?></p>
        <p>Sexe : <?php echo $etudiant['sexe']; ?></p>
        <p>Parcours : <?php echo $etudiant['parcours']; ?></p>

        <a href="dashboard.php">Retour</a>
    </div>
</body>
</html>

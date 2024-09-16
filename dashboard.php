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

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM etudiant";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Bienvenue, <?php echo $_SESSION['username']; ?>!</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($etudiants as $etudiant): ?>
            <tr>
                <td><?php echo $etudiant['id']; ?></td>
                <td><?php echo $etudiant['nom']; ?></td>
                <td><?php echo $etudiant['prenom']; ?></td>
                <td class="action">
                    <a href="details.php?id=<?php echo $etudiant['id']; ?>">Détails</a>
                    <a href="edit.php?id=<?php echo $etudiant['id']; ?>">Modifier</a>
                    <a href="delete.php?id=<?php echo $etudiant['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <a href="ajout.php">Ajouter étudiant</a>
    <a href="logout.php">Se déconnecter</a>
</body>
</html>

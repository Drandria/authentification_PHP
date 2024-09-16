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

    $req = "SELECT * FROM parcours";
    $prep = $conn->prepare($req);
    $prep->execute();
    $parcours = $prep->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $parcours = $_POST['parcours'];
        $date_naissance = $_POST['date_naissance'];
        $sexe = $_POST['sexe'];
        $adresse = $_POST['adresse'];

        $sql = "INSERT INTO etudiant (nom, prenom, parcours_id, date_de_naissance, sexe, adresse) 
                VALUES (:nom, :prenom, :parcours, :date_naissance, :sexe, :adresse)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':parcours', $parcours);
        $stmt->bindParam(':date_naissance', $date_naissance);
        $stmt->bindParam(':sexe', $sexe);
        $stmt->bindParam(':adresse', $adresse);

        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Erreur lors de l'insertion des données.";
        }
    }

} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Formulaire Étudiants</title>
</head>
<body>
    <form action="" method="POST">
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" required><br>
        
        <label for="prenom">Prénom</label>
        <input type="text" name="prenom" id="prenom" required><br>
        
        <label for="parcours">Parcours</label>
        <select name="parcours" id="parcours" required><br>
            <?php foreach ($parcours as $parcour): ?>
                <option value="<?php echo $parcour['id'] ?>"><?php echo $parcour['nom'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="date_naissance">Date de naissance</label>
        <input type="date" name="date_naissance" id="date_naissance" required><br>

        <label for="sexe">Sexe</label>
        <label for="m">Masculin</label>
        <input type="radio" name="sexe" id="m" value="M" required>
        <label for="f">Féminin</label>
        <input type="radio" name="sexe" id="f" value="F" required><br>

        <label for="adresse">Adresse</label>
        <input type="text" name="adresse" id="adresse" required><br>

        <input type="submit" value="Soumettre">
    </form>

    <a href="dashboard.php">Retour</a>
</body>
</html>
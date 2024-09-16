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

        $req = "SELECT * FROM parcours";
        $prep = $conn->prepare($req);
        $prep->execute();
        $parcours = $prep->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $parcoursChoix = $_POST['parcours'];
            $date_naissance = $_POST['date_naissance'];
            $sexe = $_POST['sexe'];
            $adresse = $_POST['adresse'];

            $sql = "UPDATE etudiant SET nom = :nom, prenom = :prenom, parcours_id = :parcours, date_de_naissance = :date_naissance, sexe = :sexe, adresse = :adresse WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':parcours', $parcoursChoix);
            $stmt->bindParam(':date_naissance', $date_naissance);
            $stmt->bindParam(':sexe', $sexe);
            $stmt->bindParam(':adresse', $adresse);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            header("Location: dashboard.php");
            exit();
        }

        $sql = "SELECT * FROM etudiant WHERE id = :id";
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
    <title>Modifier l'utilisateur</title>
</head>
<body>
    <h1>Modifier l'utilisateur</h1>
    <form action="" method="post">
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" value="<?php echo $etudiant['nom'] ?>" required><br>
        
        <label for="prenom">Prénom</label>
        <input type="text" name="prenom" id="prenom" value="<?php echo $etudiant['prenom'] ?>" required><br>
        
        <label for="parcours">Parcours</label>
        <select name="parcours" id="parcours" required>
            <?php foreach ($parcours as $parcour): ?>
                <option value="<?php echo $parcour['id'] ?>" <?php if ($parcour['id'] == $etudiant['parcours_id']) echo 'selected'; ?> ><?php echo $parcour['nom'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="date_naissance">Date de naissance</label>
        <input type="date" name="date_naissance" id="date_naissance" value="<?php echo $etudiant['date_de_naissance'] ?>" required><br>

        <label for="sexe">Sexe</label>
        <label for="m">Masculin</label>
        <input type="radio" name="sexe" id="m" value="M" required <?php if ($etudiant['sexe'] == "M") echo "checked"; ?> >
        <label for="f">Féminin</label>
        <input type="radio" name="sexe" id="f" value="F" required <?php if ($etudiant['sexe'] == "F") echo "checked"; ?> ><br>

        <label for="adresse">Adresse</label>
        <input type="text" name="adresse" id="adresse" required value="<?php echo $etudiant['adresse'] ?>"><br>

        <input type="submit" value="Mettre à jour">
    </form>

    <a href="dashboard.php">Retour</a>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $host = 'localhost';
    $dbname = 'utilisateurs';
    $user = 'mysqlUser';
    $pass = 'azertyuiop';
    

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM user WHERE mail = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $new_password = '123456';

            $sql = "UPDATE user SET password = :password WHERE mail = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':password', $new_password);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $to = $email;
            $subject = "Votre mot de passe a été réinitialisé";
            $message = "Bonjour,\n\nVotre mot de passe a été réinitialisé avec succès. Votre nouveau mot de passe est : " . $new_password . "\n\nVeuillez vous connecter et le changer dès que possible.";

            $headers = "From: expéditeur@example.com" . "\r\n" .
                       "Reply-To: expéditeur@example.com" . "\r\n" .
                       "X-Mailer: PHP/" . phpversion();

            if (mail($to, $subject, $message, $headers)) {
                echo "E-mail envoyé avec succès.";
            } else {
                echo "Échec de l'envoi de l'e-mail.";
            }

        } else {
            echo "Aucun utilisateur trouvé avec cet e-mail.";
        }

    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Mot de passe oublié</title>
</head>
<body>
    <h1>Mot de passe oublié</h1>
    <form action="forgot_password.php" method="post">
        <label for="email">Adresse e-mail :</label>
        <input type="email" name="email" id="email" required><br>
        <input type="submit" value="Envoyer les instructions">
    </form>
    <a href="connection.html">Retour</a>
</body>
</html>

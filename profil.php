<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "Romain-1964";
$dbname = "moduleconnexion";

session_start();

$errors = array();
$success = "";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Rediriger vers la page de connexion
    header("Location: connexion.php");
    exit;
}

try {
    // Créer une connexion
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configurer PDO pour lancer des exceptions en cas d'erreur
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les informations de l'utilisateur connecté
    $login = $_SESSION['login'];
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE login = :login");
    $stmt->bindParam(':login', $login);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $prenom = $_POST['prenom'];
        $nom = $_POST['nom'];
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Vérifier si l'utilisateur souhaite changer de mot de passe
        if (!empty($old_password) || !empty($new_password) || !empty($confirm_password)) {
            // Vérifier que l'ancien mot de passe est correct
            if (!password_verify($old_password, $user['password'])) {
                $errors[] = "L'ancien mot de passe est incorrect";
            }

            // Vérifier que les nouveaux mots de passe correspondent
            if ($new_password != $confirm_password) {
                $errors[] = "Les nouveaux mots de passe ne correspondent pas";
            }

            // Hacher le nouveau mot de passe
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        } else {
            // Si l'utilisateur ne souhaite pas changer de mot de passe, conserver l'ancien
            $hashed_password = $user['password'];
        }

        // Si aucune erreur n'est présente, procéder à la mise à jour
        if (empty($errors)) {
            // Mettre à jour les informations de l'utilisateur dans la base de données
            $stmt = $conn->prepare("UPDATE utilisateurs SET prenom = :prenom, nom = :nom, password = :password WHERE login = :login");
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':login', $login);
            $stmt->execute();

            // Mettre à jour les informations de l'utilisateur dans la session
            $_SESSION['prenom'] = $prenom;
            $_SESSION['nom'] = $nom;

            $success = "Informations mises à jour avec succès";
        }
    }
} catch (PDOException $e) {
    $errors[] = "Erreur: " . $e->getMessage();
}

$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil</title>
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <h1>Profil</h1>

        <?php if (!empty($errors)) : ?>
            <div class="errors">
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)) : ?>
            <div class="success-message">
                <p><?php echo $success; ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="profil.php">
            <label for="login">Login:</label>
            <input type="text" id="login" name="login" value="<?php echo $_SESSION['login']; ?>" disabled>
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo $_SESSION['prenom']; ?>">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" value="<?php echo $_SESSION['nom']; ?>">
            <label for="old_password">Ancien mot de passe:</label>
            <input type="password" id="old_password" name="old_password">
            <label for="new_password">Nouveau mot de passe:</label>
            <input type="password" id="new_password" name="new_password">
            <label for="confirm_password">Confirmer le nouveau mot de passe:</label>
            <input type="password" id="confirm_password" name="confirm_password"><br>
            <input type="submit" value="Mettre à jour" class="button"><br>
            <a href="index.php" class="button">Retour à l'accueil</a>
        </form>
    </div>
</body>
</html>

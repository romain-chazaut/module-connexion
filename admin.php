<?php
$servername = "localhost";
$username = "root";
$password = "Romain-1964";
$dbname = "moduleconnexion";

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['login'] !== 'admin') {
    header("Location: connexion.php");
    exit;
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->query("SELECT * FROM utilisateurs");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}

$conn = null;
?>




<!DOCTYPE html>
<html>
<head>
    <title>Administration</title>
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <h1>Administration</h1>
        <table>
            <tr>
                <th>Login</th>
                <th>Prénom</th>
                <th>Nom</th>
            </tr>
            <?php foreach ($users as $user) : ?>
            <tr>
                <td><?php echo $user['login']; ?></td>
                <td><?php echo $user['prenom']; ?></td>
                <td><?php echo $user['nom']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a href="index.php" class="button">Retour à l'accueil</a>
    </div>
</body>
</html>
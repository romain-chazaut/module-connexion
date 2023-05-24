<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "Romain-1964";
$dbname = "moduleconnexion";

session_start();

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

        // Mettre à jour les informations de l'utilisateur dans la base de données
        $stmt = $conn->prepare("UPDATE utilisateurs SET prenom = :prenom, nom = :nom WHERE login = :login");
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        // Mettre à jour les informations de l'utilisateur dans la session
        $_SESSION['prenom'] = $prenom;
        $_SESSION['nom'] = $nom;

        echo "Informations mises à jour avec succès";
    }
} catch(PDOException $e) {
    echo "Erreur: " . $e->getMessage();
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
    <h1>Profil</h1>
    <form method="POST" action="profil.php">
        <label for="login">Login:</label>
        <input type="text" id="login" name="login" value="<?php echo $_SESSION['login']; ?>" disabled>
        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" name="prenom" value="<?php echo $_SESSION['prenom']; ?>">
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" value="<?php echo $_SESSION['nom']; ?>">
        <input type="submit" value="Mettre à jour">
    </form>
    <?php
    if ($_SESSION['login'] === 'admin') {
        echo '<a href="admin.php">Page d\'administration</a>';
    }
    ?>
</body>
</html>

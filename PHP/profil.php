<?php
    require_once __DIR__ . '/session_config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../LOGIN.php");
    exit;
}

$host = 'localhost';
$dbname = 'dawnsea4';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user_id = $_SESSION['user_id'];

    // Mise à jour des infos
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $num_tel = $_POST['num_tel'] ?? '';
        $pays = $_POST['pays_naissance'] ?? '';
        $date_naissance = $_POST['date_naissance'] ?? '';

        $sql = "UPDATE utilisateur SET nom = :nom, prenom = :prenom, Email = :email,
                num_de_telephone = :tel, pays_de_Naissance = :pays, date_de_Naissance = :naissance
                WHERE ID_Utilisateur = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':tel' => $num_tel,
            ':pays' => $pays,
            ':naissance' => $date_naissance,
            ':id' => $user_id
        ]);
        $message = "Profil mis à jour avec succès.";
    }

    // Suppression du compte
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE ID_Utilisateur = :id");
        $stmt->execute([':id' => $user_id]);

        session_destroy();
        header("Location: ../SIGN_UP.php");
        exit;
    }

    // Récupération des infos utilisateur
    $stmt = $pdo->prepare("SELECT nom, prenom, Email, num_de_telephone, pays_de_Naissance, date_de_Naissance
                           FROM utilisateur WHERE ID_Utilisateur = :id");
    $stmt->execute([':id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Utilisateur introuvable.");
    }

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil - Dawn & Sea</title>
    <link rel="stylesheet" href="../assets/CSS/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/CSS/profil.css">
</head>
<body>
    <header>
        <div class="container-fluid px-0">
            <nav class="navbar navbar-expand-md navbar-dark bg-dark p-2">
                <img src="IMG/4-removebg-preview.png" alt="Mon Logo" class="navbar-brand logo-navbar">
                <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#x">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <script>
                    function confirmLogout() {
                        if (confirm("Voulez-vous vraiment vous déconnecter ?")) {
                            window.location.href = "logout.php";
                        }
                    }
                </script>

                <div id="x" class="collapse navbar-collapse w-100 justify-content-center">
                    <ul class="navbar-nav d-flex gap-4 align-items-center">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                        <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-info btn-sm" href="LOGIN.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-success btn-sm" href="SIGN_UP.php">Sign_Up</a>
                        </li>
                        <li class="nav-item">
                            <form id="SRCH" class="d-flex" role="search">
                                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                <button class="btn btn-outline-success" type="submit">Search</button>
                            </form>
                        </li>
                        <!-- Bienvenue -->
                    <span class="navbar-text text-white">
                        Bienvenue, <strong><?= htmlspecialchars($user['prenom'] ?? 'Utilisateur') ?></strong>
                    </span>
                    </ul>
                </div>
            </nav>
        </div>
    </header>


<main class="container mt-5">
    <h2>Ton Profil</h2>
    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom :</label>
            <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom :</label>
            <input type="text" class="form-control" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['Email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="num_tel" class="form-label">Numéro de téléphone :</label>
            <input type="tel" class="form-control" name="num_tel" value="<?= htmlspecialchars($user['num_de_telephone']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="pays_naissance" class="form-label">Pays de naissance :</label>
            <input type="text" class="form-control" name="pays_naissance" value="<?= htmlspecialchars($user['pays_de_Naissance']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="date_naissance" class="form-label">Date de naissance :</label>
            <input type="date" class="form-control" name="date_naissance" value="<?= htmlspecialchars($user['date_de_Naissance']) ?>" required>
        </div>
        <div class="d-flex gap-3">
            <button type="submit" name="update" class="btn btn-success">Modifier</button>
            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?');">Supprimer</button>
        </div>
    </form>
</main>

<script src="../assets/JS/bootstrap.bundle.min.js"></script>
</body>
</html>

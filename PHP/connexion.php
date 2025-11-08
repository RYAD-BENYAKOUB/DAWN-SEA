<?php
    require_once __DIR__ . '/session_config.php';
    // Connexion à la base
    $host   = 'localhost';
    $dbname = 'dawnsea4';
    $user   = 'root';
    $pass   = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email       = trim($_POST['email'] ?? '');
            $passwordRaw = $_POST['password'] ?? '';

            // Requête préparée
            $sql  = 'SELECT ID_Utilisateur, Mot_de_passe FROM utilisateur WHERE Email = :email';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérification du mot de passe
            if ($user && password_verify($passwordRaw, $user['Mot_de_passe'])) {
                // Succès → on stocke l’ID en session
                $_SESSION['user_id'] = $user['ID_Utilisateur'];

                header('Location: profil.php');
                exit;
            } else {
                // Affichage simple ; vous pouvez remplacer par un message flash
                echo '<p style="color:red;">Email ou mot de passe incorrect.</p>';
            }
        }
    } catch (PDOException $e) {
        // En prod, évitez d’afficher directement l’exception
        echo '<p>Erreur de connexion à la base de données.</p>';
        // Décommenter pour debug :
        // echo '<pre>' . $e->getMessage() . '</pre>';
    }
?>

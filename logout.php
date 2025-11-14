<?php
    // Démarrer la session si ce n'est pas déjà fait
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Supprimer toutes les variables de session
    $_SESSION = [];

    // Détruire la session
    session_destroy();

    // Rediriger vers la page de connexion
    header("Location: ../LOGIN.php");
    exit;
?>
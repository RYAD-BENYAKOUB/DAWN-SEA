<?php
// PHP/profile_action.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';

// Vérification de l'authentification
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autorisé. Veuillez vous connecter.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$method  = $_SERVER['REQUEST_METHOD'];
$action  = $_GET['action'] ?? '';

// Permet de lire aussi bien les requêtes POST standard que les requêtes JSON (fetch body)
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

if ($method === 'GET') {
    // Récupérer les informations du profil
    try {
        $stmt = $pdo->prepare('SELECT "Nom", "Prenom", "Email", "Num_de_telephone", "Pays_de_naissance", "Date_de_naissance" 
                               FROM "utilisateur" WHERE "ID_Utilisateur" = :id');
        $stmt->execute(['id' => $user_id]);
        $user = $stmt->fetch();

        if ($user) {
            echo json_encode([
                'success' => true,
                'data' => [
                    'nom'              => $user['Nom'],
                    'prenom'           => $user['Prenom'],
                    'email'            => $user['Email'],
                    'num_de_telephone' => $user['Num_de_telephone'],
                    'pays_naissance'   => $user['Pays_de_naissance'],
                    'date_naissance'   => $user['Date_de_naissance']
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la récupération des données du profil.']);
    }
    exit;
}

if ($method === 'POST') {
    if ($action === 'update') {
        $nom       = trim($input['nom'] ?? '');
        $prenom    = trim($input['prenom'] ?? '');
        $email     = trim($input['email'] ?? '');
        $tel       = trim($input['num_tel'] ?? '');
        $pays      = trim($input['pays_naissance'] ?? '');
        $dateNaiss = trim($input['date_naissance'] ?? '');

        if (empty($nom) || empty($prenom) || empty($email) || empty($tel) || empty($pays) || empty($dateNaiss)) {
            echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Adresse email invalide.']);
            exit;
        }

        try {
            // Vérifier que le nouvel email n'appartient pas à un autre utilisateur
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM "utilisateur" WHERE "Email" = :email AND "ID_Utilisateur" != :id');
            $stmt->execute(['email' => $email, 'id' => $user_id]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé par un autre compte.']);
                exit;
            }

            $sql = "UPDATE \"utilisateur\" 
                    SET \"Nom\" = :nom, \"Prenom\" = :prenom, \"Email\" = :email, 
                        \"Num_de_telephone\" = :tel, \"Pays_de_naissance\" = :pays, \"Date_de_naissance\" = :date_naiss 
                    WHERE \"ID_Utilisateur\" = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nom'        => $nom,
                'prenom'     => $prenom,
                'email'      => $email,
                'tel'        => $tel,
                'pays'       => $pays,
                'date_naiss' => $dateNaiss,
                'id'         => $user_id
            ]);

            echo json_encode(['success' => true, 'message' => 'Profil mis à jour avec succès.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour du profil.']);
        }
        exit;
    }

    if ($action === 'delete') {
        try {
            $stmt = $pdo->prepare('DELETE FROM "utilisateur" WHERE "ID_Utilisateur" = :id');
            $stmt->execute(['id' => $user_id]);

            // Détruire la session après suppression
            $_SESSION = [];
            session_destroy();

            echo json_encode(['success' => true, 'message' => 'Votre compte a été supprimé avec succès.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression du compte.']);
        }
        exit;
    }
}

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Requête ou action invalide.']);

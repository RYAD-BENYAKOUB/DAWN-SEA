<?php
// PHP/auth.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';

$action = $_GET['action'] ?? '';

// Permet de lire aussi bien les requêtes POST standard que les requêtes JSON (fetch body)
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

switch ($action) {
    case 'login':
        $email    = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs.']);
            exit;
        }

        try {
            $stmt = $pdo->prepare('SELECT "ID_Utilisateur", "Mot_de_passe", "Prenom", "Role" FROM "utilisateur" WHERE "Email" = :email');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user) {
                if (password_verify($password, $user['Mot_de_passe'])) {
                    $_SESSION['user_id'] = $user['ID_Utilisateur'];
                    $_SESSION['user_role'] = $user['Role'];
                    echo json_encode([
                        'success' => true,
                        'message' => 'Connexion réussie !',
                        'user' => [
                            'prenom' => $user['Prenom'],
                            'role'   => $user['Role']
                        ]
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Le mot de passe saisi est incorrect.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'L\'adresse email saisie n\'existe pas.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la connexion : ' . $e->getMessage()]);
        }
        break;

    case 'signup':
        $nom        = trim($input['nom'] ?? '');
        $prenom     = trim($input['prenom'] ?? '');
        $email      = trim($input['email'] ?? '');
        $password   = $input['password'] ?? '';
        $password_c = $input['password_confirmation'] ?? '';
        $indicatif  = trim($input['indicatif'] ?? '');
        $numero     = trim($input['num_de_telephone'] ?? '');
        $pays       = trim($input['pays_naissance'] ?? '');
        $dateNaiss  = trim($input['date_naissance'] ?? '');

        // Validation
        if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($indicatif) || empty($numero) || empty($pays) || empty($dateNaiss)) {
            echo json_encode(['success' => false, 'message' => 'Tous les champs sont obligatoires.']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Adresse email invalide.']);
            exit;
        }

        if (strlen($password) < 6) {
            echo json_encode(['success' => false, 'message' => 'Le mot de passe doit faire au moins 6 caractères.']);
            exit;
        }

        if ($password !== $password_c) {
            echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas.']);
            exit;
        }

        if (!preg_match('/^\+\d{1,4}$/', $indicatif)) {
            echo json_encode(['success' => false, 'message' => 'Indicatif téléphonique invalide.']);
            exit;
        }

        if (!preg_match('/^\d{4,15}$/', $numero)) {
            echo json_encode(['success' => false, 'message' => 'Numéro de téléphone invalide.']);
            exit;
        }

        try {
            // Vérifier l'unicité de l'email
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM "utilisateur" WHERE "Email" = :email');
            $stmt->execute(['email' => $email]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé.']);
                exit;
            }

            // Insérer l'utilisateur
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $phone  = $indicatif . $numero;
            $role   = ($email === 'ryadbenyakoub@gmail.com') ? 'superadmin' : 'user';

            $sql = "INSERT INTO \"utilisateur\" 
                    (\"Nom\", \"Prenom\", \"Email\", \"Mot_de_passe\", \"Num_de_telephone\", \"Pays_de_naissance\", \"Date_de_naissance\", \"Role\")
                    VALUES 
                    (:nom, :prenom, :email, :mdp, :tel, :pays, :dateNaiss, :role)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nom'       => $nom,
                'prenom'    => $prenom,
                'email'     => $email,
                'mdp'       => $hashed,
                'tel'       => $phone,
                'pays'      => $pays,
                'dateNaiss' => $dateNaiss,
                'role'      => $role
            ]);

            // Connecter automatiquement l'utilisateur après inscription
            $newUserId = $pdo->lastInsertId();
            $_SESSION['user_id'] = $newUserId;
            $_SESSION['user_role'] = $role;

            echo json_encode([
                'success' => true,
                'message' => 'Inscription réussie et connexion automatique !',
                'user' => [
                    'prenom' => $prenom,
                    'role'   => $role
                ]
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la création du compte : ' . $e->getMessage()]);
        }
        break;

    case 'status':
        if (isset($_SESSION['user_id'])) {
            try {
                $stmt = $pdo->prepare('SELECT "Prenom", "Nom", "Email", "Role" FROM "utilisateur" WHERE "ID_Utilisateur" = :id');
                $stmt->execute(['id' => $_SESSION['user_id']]);
                $user = $stmt->fetch();

                if ($user) {
                    // S'assurer de synchroniser le rôle en session
                    $_SESSION['user_role'] = $user['Role'];
                    
                    echo json_encode([
                        'logged_in' => true,
                        'user' => [
                            'prenom' => $user['Prenom'],
                            'nom'    => $user['Nom'],
                            'email'  => $user['Email'],
                            'role'   => $user['Role']
                        ]
                    ]);
                    exit;
                }
            } catch (PDOException $e) {
                // Silencieusement échouer et détruire la session
            }
        }
        echo json_encode(['logged_in' => false]);
        break;

    case 'logout':
        $_SESSION = [];
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Déconnexion réussie.']);
        break;

    case 'reset_password':
        $email      = trim($input['email'] ?? '');
        $dateNaiss  = trim($input['date_naissance'] ?? '');
        $pays       = trim($input['pays_naissance'] ?? '');
        $password   = $input['password'] ?? '';
        $password_c = $input['password_confirmation'] ?? '';

        if (empty($email) || empty($dateNaiss) || empty($pays) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Tous les champs sont obligatoires.']);
            exit;
        }

        if ($password !== $password_c) {
            echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas.']);
            exit;
        }

        if (strlen($password) < 6) {
            echo json_encode(['success' => false, 'message' => 'Le nouveau mot de passe doit faire au moins 6 caractères.']);
            exit;
        }

        try {
            // Vérifier si un utilisateur correspond à l'email, pays de naissance et date de naissance
            $stmt = $pdo->prepare('
                SELECT "ID_Utilisateur" FROM "utilisateur" 
                WHERE "Email" = :email 
                  AND "Pays_de_naissance" ILIKE :pays 
                  AND "Date_de_naissance" = :dateNaiss
            ');
            $stmt->execute([
                'email'     => $email,
                'pays'      => $pays,
                'dateNaiss' => $dateNaiss
            ]);
            $userId = $stmt->fetchColumn();

            if ($userId) {
                // Mettre à jour le mot de passe
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmtUpdate = $pdo->prepare('UPDATE "utilisateur" SET "Mot_de_passe" = :mdp WHERE "ID_Utilisateur" = :id');
                $stmtUpdate->execute(['mdp' => $hashed, 'id' => $userId]);

                echo json_encode(['success' => true, 'message' => 'Votre mot de passe a été réinitialisé avec succès !']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Les informations fournies ne correspondent à aucun compte.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la réinitialisation : ' . $e->getMessage()]);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Action non spécifiée ou invalide.']);
        break;
}

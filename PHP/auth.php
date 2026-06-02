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
            $stmt = $pdo->prepare('SELECT "ID_Utilisateur", "Mot_de_passe", "Prenom" FROM "utilisateur" WHERE "Email" = :email');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user) {
                if (password_verify($password, $user['Mot_de_passe'])) {
                    $_SESSION['user_id'] = $user['ID_Utilisateur'];
                    echo json_encode([
                        'success' => true,
                        'message' => 'Connexion réussie !',
                        'user' => [
                            'prenom' => $user['Prenom']
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

            $sql = "INSERT INTO \"utilisateur\" 
                    (\"Nom\", \"Prenom\", \"Email\", \"Mot_de_passe\", \"Num_de_telephone\", \"Pays_de_naissance\", \"Date_de_naissance\")
                    VALUES 
                    (:nom, :prenom, :email, :mdp, :tel, :pays, :dateNaiss)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nom'       => $nom,
                'prenom'    => $prenom,
                'email'     => $email,
                'mdp'       => $hashed,
                'tel'       => $phone,
                'pays'      => $pays,
                'dateNaiss' => $dateNaiss
            ]);

            // Connecter automatiquement l'utilisateur après inscription
            $newUserId = $pdo->lastInsertId();
            $_SESSION['user_id'] = $newUserId;

            echo json_encode([
                'success' => true,
                'message' => 'Inscription réussie et connexion automatique !',
                'user' => [
                    'prenom' => $prenom
                ]
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la création du compte : ' . $e->getMessage()]);
        }
        break;

    case 'status':
        if (isset($_SESSION['user_id'])) {
            try {
                $stmt = $pdo->prepare('SELECT "Prenom", "Nom", "Email" FROM "utilisateur" WHERE "ID_Utilisateur" = :id');
                $stmt->execute(['id' => $_SESSION['user_id']]);
                $user = $stmt->fetch();

                if ($user) {
                    echo json_encode([
                        'logged_in' => true,
                        'user' => [
                            'prenom' => $user['Prenom'],
                            'nom'    => $user['Nom'],
                            'email'  => $user['Email']
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

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Action non spécifiée ou invalide.']);
        break;
}

<?php
// PHP/google_callback.php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/config.php';

// 1. Vérifier l'état de sécurité pour parer aux attaques CSRF
$state = $_GET['state'] ?? '';
if (empty($state) || !isset($_SESSION['oauth_state']) || $state !== $_SESSION['oauth_state']) {
    header('Location: ../login.html?error=' . urlencode('État de sécurité invalide.'));
    exit;
}

// Nettoyer la variable d'état
unset($_SESSION['oauth_state']);

$code = $_GET['code'] ?? '';
if (empty($code)) {
    header('Location: ../login.html?error=' . urlencode('Code d\'autorisation manquant.'));
    exit;
}

// 2. Échanger le code contre un token d'accès auprès de Google
$tokenUrl = 'https://oauth2.googleapis.com/token';
$postData = http_build_query([
    'code'          => $code,
    'client_id'     => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri'  => GOOGLE_REDIRECT_URI,
    'grant_type'    => 'authorization_code'
]);

$opts = [
    'http' => [
        'method'        => 'POST',
        'header'        => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content'       => $postData,
        'ignore_errors' => true
    ]
];

$context  = stream_context_create($opts);
$response = file_get_contents($tokenUrl, false, $context);
$tokens   = json_decode($response, true);

if (!isset($tokens['access_token'])) {
    $errorMsg = $tokens['error_description'] ?? 'Impossible d\'échanger le code d\'autorisation.';
    header('Location: ../login.html?error=' . urlencode('Erreur Google Auth : ' . $errorMsg));
    exit;
}

// 3. Récupérer les informations de profil de l'utilisateur
$userinfoUrl = 'https://www.googleapis.com/oauth2/v3/userinfo';
$optsUser = [
    'http' => [
        'method' => 'GET',
        'header' => "Authorization: Bearer " . $tokens['access_token'] . "\r\n"
    ]
];

$contextUser  = stream_context_create($optsUser);
$userinfoJson = file_get_contents($userinfoUrl, false, $contextUser);
$userinfo     = json_decode($userinfoJson, true);

$email      = trim($userinfo['email'] ?? '');
$givenName  = trim($userinfo['given_name'] ?? '');
$familyName = trim($userinfo['family_name'] ?? '');

if (empty($email)) {
    header('Location: ../login.html?error=' . urlencode('Impossible de récupérer votre adresse email Google.'));
    exit;
}

try {
    // 4. Vérifier si l'utilisateur existe déjà
    $stmt = $pdo->prepare('SELECT ID_Utilisateur FROM utilisateur WHERE Email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // L'utilisateur existe déjà, on le connecte
        $_SESSION['user_id'] = $user['ID_Utilisateur'];
    } else {
        // Création automatique de compte
        $nom       = !empty($familyName) ? $familyName : 'GoogleUser';
        $prenom    = !empty($givenName) ? $givenName : 'Utilisateur';
        $dummyMdp  = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT); // Mot de passe aléatoire inutilisé
        $dummyTel  = '';
        $dummyPays = 'Non spécifié';
        $dummyDate = '2000-01-01';

        $sql = "INSERT INTO utilisateur 
                (Nom, Prenom, Email, Mot_de_passe, Num_de_telephone, Pays_de_naissance, Date_de_naissance)
                VALUES 
                (:nom, :prenom, :email, :mdp, :tel, :pays, :dateNaiss)";
        
        $stmtInsert = $pdo->prepare($sql);
        $stmtInsert->execute([
            'nom'       => $nom,
            'prenom'    => $prenom,
            'email'     => $email,
            'mdp'       => $dummyMdp,
            'tel'       => $dummyTel,
            'pays'      => $dummyPays,
            'dateNaiss' => $dummyDate
        ]);

        $_SESSION['user_id'] = $pdo->lastInsertId();
    }

    // Rediriger l'utilisateur connecté vers l'accueil
    header('Location: ../index.html');
    exit;

} catch (PDOException $e) {
    header('Location: ../login.html?error=' . urlencode('Erreur de base de données lors de la connexion Google : ' . $e->getMessage()));
    exit;
}

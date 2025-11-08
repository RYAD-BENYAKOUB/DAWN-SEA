<?php
    require_once __DIR__ . '/session_config.php';
// 1. Connexion à la base
$host     = 'localhost';
$dbname   = 'dawnsea4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    // En prod : loggez l'erreur, n'affichez pas la stack trace
    exit('Erreur de connexion à la base.');
}

// 2. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 2.1 Récupération & nettoyage
    $nom        = trim(filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING));
    $prenom     = trim(filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING));
    $email      = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
    $password   = $_POST['password']          ?? '';
    $password2  = $_POST['password_confirmation'] ?? '';
    $indicatif  = trim(filter_input(INPUT_POST, 'indicatif', FILTER_SANITIZE_STRING));
    $numero     = trim(filter_input(INPUT_POST, 'num_de_telephone', FILTER_SANITIZE_STRING));
    $pays       = trim(filter_input(INPUT_POST, 'pays_naissance', FILTER_SANITIZE_STRING));
    $dateNaiss  = trim(filter_input(INPUT_POST, 'date_naissance', FILTER_SANITIZE_STRING));

    $errors = [];

    // 2.2 Validation basique
    if (!$email) {
        $errors[] = 'Adresse email invalide.';
    }
    if (empty($nom) || empty($prenom)) {
        $errors[] = 'Nom et prénom sont requis.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Le mot de passe doit faire au moins 6 caractères.';
    }
    if ($password !== $password2) {
        $errors[] = 'Les mots de passe ne correspondent pas.';
    }
    if (!preg_match('/^\+\d{1,4}$/', $indicatif)) {
        $errors[] = 'Indicatif téléphonique invalide.';
    }
    if (!preg_match('/^\d{4,15}$/', $numero)) {
        $errors[] = 'Numéro de téléphone invalide.';
    }
    if (empty($dateNaiss) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateNaiss)) {
        $errors[] = 'Date de naissance invalide (YYYY‑MM‑DD).';
    }

    // 2.3 Si erreur, renvoyer vers le formulaire
    if ($errors) {
        // Vous pouvez stocker $errors en session ou les passer en GET
        header('Location: signup.php?error=' . urlencode(implode('|', $errors)));
        exit;
    }

    // 2.4 Vérifier l’unicité de l’email
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM utilisateur WHERE Email = :email');
    $stmt->execute(['email' => $email]);
    if ($stmt->fetchColumn() > 0) {
        header('Location: signup.php?error=' . urlencode('Cet email est déjà utilisé.'));
        exit;
    }

    // 2.5 Insertion en base
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $phone  = $indicatif . $numero;

    $sql = "
        INSERT INTO utilisateur
            (Nom, Prenom, Email, Mot_de_passe, Num_de_telephone, Pays_de_naissance, Date_de_naissance)
        VALUES
            (:nom, :prenom, :email, :mdp, :tel, :pays, :dateNaiss)
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom'       => $nom,
        'prenom'    => $prenom,
        'email'     => $email,
        'mdp'       => $hashed,
        'tel'       => $phone,
        'pays'      => $pays,
        'dateNaiss' => $dateNaiss,
    ]);

    // 3. Redirection vers login avec flag succès
    header('Location: index.php?inscription=ok');
    exit;
}

// 4. Si on arrive ici autrement qu’en POST
http_response_code(405);
echo 'Méthode non autorisée.';

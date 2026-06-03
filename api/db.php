<?php
// PHP/db.php
require_once __DIR__ . '/session_config.php';
require_once __DIR__ . '/load_env.php';

// Lire les variables d'environnement (configurées via .env en local, ou définies sur Vercel en production)
// Nous appliquons trim() pour éliminer les espaces ou retours à la ligne invisibles lors du copier-coller.
$host   = trim(getenv('SUPABASE_DB_HOST') ?: '');
$port   = '5432'; // Port de connexion Session du pooler Supabase
$dbname = 'postgres'; // Base de données par défaut de Supabase
$user   = trim(getenv('SUPABASE_DB_USER') ?: '');
$pass   = trim(getenv('SUPABASE_DB_PASSWORD') ?: '');

if (!$host || !$user || !$pass) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Configuration de la base de données manquante (variables d\'environnement non définies).'
    ]);
    exit;
}

try {
    // Connexion PostgreSQL pour Supabase
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de connexion à la base de données Supabase (PostgreSQL) : ' . $e->getMessage()
    ]);
    exit;
}

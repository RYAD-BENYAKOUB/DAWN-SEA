<?php
// PHP/db.php
require_once __DIR__ . '/session_config.php';

// Lire les variables d'environnement (idéal sur Vercel) ou utiliser les valeurs par défaut de Supabase
$host   = getenv('SUPABASE_DB_HOST') ?: 'db.votre-projet.supabase.co';
$port   = getenv('SUPABASE_DB_PORT') ?: '5432';
$dbname = getenv('SUPABASE_DB_NAME') ?: 'postgres';
$user   = getenv('SUPABASE_DB_USER') ?: 'postgres';
$pass   = getenv('SUPABASE_DB_PASSWORD') ?: 'votre_mot_de_passe_supabase';

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

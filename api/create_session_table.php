<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';

try {
    $pdo->exec('
        CREATE TABLE IF NOT EXISTS "session" (
            "id" VARCHAR(255) PRIMARY KEY,
            "data" TEXT NOT NULL,
            "timestamp" INTEGER NOT NULL
        )
    ');
    echo json_encode([
        'success' => true,
        'message' => 'Table "session" créée avec succès sur Supabase.'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de base de données : ' . $e->getMessage()
    ]);
}

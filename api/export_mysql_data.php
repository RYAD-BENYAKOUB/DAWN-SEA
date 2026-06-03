<?php
header('Content-Type: application/json; charset=utf-8');

try {
    // Connexion locale MySQL
    $mysql = new PDO("mysql:host=localhost;dbname=dawnsea4;charset=utf8", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Compter les lignes
    $counts = [
        'recommandation' => (int)$mysql->query('SELECT COUNT(*) FROM recommandation')->fetchColumn(),
        'tag' => (int)$mysql->query('SELECT COUNT(*) FROM tag')->fetchColumn(),
        'recommandation_tag' => (int)$mysql->query('SELECT COUNT(*) FROM recommandation_tag')->fetchColumn()
    ];
    
    echo json_encode([
        'success' => true,
        'message' => 'Connexion réussie à MySQL Local !',
        'counts' => $counts
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de connexion MySQL : ' . $e->getMessage()
    ]);
}

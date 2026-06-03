<?php
// PHP/destinations.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';

$ville  = $_GET['ville'] ?? '';
$tag    = $_GET['tag'] ?? '';
$search = $_GET['search'] ?? '';

// Helper pour récupérer tous les tags liés à une recommandation
function getRecommendationTags($pdo, $idRecommandation) {
    try {
        $stmt = $pdo->prepare("
            SELECT t.\"Nom\" FROM \"tag\" t
            JOIN \"recommandation_tag\" rt ON t.\"ID_Tag\" = rt.\"ID_Tag\"
            WHERE rt.\"ID_Recommandation\" = ?
        ");
        $stmt->execute([$idRecommandation]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        return [];
    }
}

try {
    $recommandations = [];
    $recs = [];

    if ($ville) {
        // Rechercher par ville
        $stmt = $pdo->prepare("
            SELECT r.*, l.\"Nom\" AS lieu_nom, l.\"Image\", l.\"Address\"
            FROM \"recommandation\" r
            JOIN \"lieu\" l ON r.\"ID_Lieu\" = l.\"ID_Lieu\"
            WHERE l.\"Address\" ILIKE :ville
        ");
        $stmt->execute(['ville' => "%$ville%"]);
        $recs = $stmt->fetchAll();
    } elseif ($tag) {
        // Rechercher par tag
        $stmt = $pdo->prepare("
            SELECT r.*, l.\"Nom\" AS lieu_nom, l.\"Image\", l.\"Address\"
            FROM \"recommandation\" r
            JOIN \"lieu\" l ON r.\"ID_Lieu\" = l.\"ID_Lieu\"
            JOIN \"recommandation_tag\" rt ON r.\"ID_Recommandation\" = rt.\"ID_Recommandation\"
            JOIN \"tag\" t ON rt.\"ID_Tag\" = t.\"ID_Tag\"
            WHERE t.\"Nom\" ILIKE :tag
        ");
        $stmt->execute(['tag' => $tag]);
        $recs = $stmt->fetchAll();
    } elseif ($search) {
        // Recherche globale (ville, tag, titre de recommandation, ou nom de lieu)
        $stmt = $pdo->prepare("
            SELECT DISTINCT r.*, l.\"Nom\" AS lieu_nom, l.\"Image\", l.\"Address\"
            FROM \"recommandation\" r
            JOIN \"lieu\" l ON r.\"ID_Lieu\" = l.\"ID_Lieu\"
            LEFT JOIN \"recommandation_tag\" rt ON r.\"ID_Recommandation\" = rt.\"ID_Recommandation\"
            LEFT JOIN \"tag\" t ON rt.\"ID_Tag\" = t.\"ID_Tag\"
            WHERE l.\"Address\" ILIKE :q
               OR t.\"Nom\" ILIKE :q
               OR r.\"Titre\" ILIKE :q
               OR l.\"Nom\" ILIKE :q
        ");
        $stmt->execute(['q' => "%$search%"]);
        $recs = $stmt->fetchAll();
    } else {
        echo json_encode(['success' => false, 'message' => 'Veuillez spécifier une ville, un tag ou une recherche.']);
        exit;
    }

    foreach ($recs as $rec) {
        // Encoder l'image BLOB en base64 pour l'intégrer au JSON
        $imageData = null;
        if (!empty($rec['Image'])) {
            $imageData = 'data:image/jpeg;base64,' . base64_encode($rec['Image']);
        }

        $recommandations[] = [
            'id'           => $rec['ID_Recommandation'],
            'titre'        => $rec['Titre'],
            'description'  => $rec['Description'],
            'note'         => $rec['Note_Generale'],
            'lieu_nom'     => $rec['lieu_nom'],
            'address'      => $rec['Address'],
            'image'        => $imageData,
            'tags'         => getRecommendationTags($pdo, $rec['ID_Recommandation'])
        ];
    }

    echo json_encode([
        'success' => true,
        'data'    => $recommandations
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de base de données : ' . $e->getMessage()
    ]);
}

<?php
// PHP/admin_action.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';

// Sécurité : vérifier que l'utilisateur est connecté et qu'il est admin ou superadmin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin', 'superadmin'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Accès refusé. Autorisation insuffisante.']);
    exit;
}

$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

switch ($action) {
    case 'stats':
        try {
            // Utilisateurs totaux
            $totalUsers = $pdo->query('SELECT COUNT(*) FROM "utilisateur"')->fetchColumn();
            // Admins totaux
            $totalAdmins = $pdo->query('SELECT COUNT(*) FROM "utilisateur" WHERE "Role" IN (\'admin\', \'superadmin\')')->fetchColumn();
            // Lieux totaux
            $totalLieux = $pdo->query('SELECT COUNT(*) FROM "lieu"')->fetchColumn();
            // Recommandations
            $totalRecs = $pdo->query('SELECT COUNT(*) FROM "recommandation"')->fetchColumn();

            echo json_encode([
                'success' => true,
                'stats' => [
                    'total_users' => $totalUsers,
                    'total_admins' => $totalAdmins,
                    'total_lieux' => $totalLieux,
                    'total_recs' => $totalRecs
                ]
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur stats : ' . $e->getMessage()]);
        }
        break;

    case 'get_users':
        try {
            // Récupérer la liste des utilisateurs sans le mot de passe
            $stmt = $pdo->query('SELECT "ID_Utilisateur", "Nom", "Prenom", "Email", "Num_de_telephone", "Pays_de_naissance", "Date_de_naissance", "Role" FROM "utilisateur" ORDER BY "ID_Utilisateur" ASC');
            $users = $stmt->fetchAll();
            echo json_encode(['success' => true, 'data' => $users]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
        }
        break;

    case 'update_role':
        // Seul le superadmin peut changer les rôles
        if ($_SESSION['user_role'] !== 'superadmin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Seul le superadmin principal peut modifier les rôles.']);
            exit;
        }

        $userId = intval($input['user_id'] ?? 0);
        $newRole = trim($input['role'] ?? '');

        if ($userId <= 0 || !in_array($newRole, ['user', 'admin'])) {
            echo json_encode(['success' => false, 'message' => 'Paramètres invalides.']);
            exit;
        }

        try {
            // Vérifier que le compte ciblé n'est pas le superadmin lui-même
            $stmtCheck = $pdo->prepare('SELECT "Email", "Role" FROM "utilisateur" WHERE "ID_Utilisateur" = :id');
            $stmtCheck->execute(['id' => $userId]);
            $targetUser = $stmtCheck->fetch();

            if (!$targetUser) {
                echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable.']);
                exit;
            }

            if ($targetUser['Email'] === 'ryadbenyakoub@gmail.com' || $targetUser['Role'] === 'superadmin') {
                echo json_encode(['success' => false, 'message' => 'Le rôle du superadmin principal ne peut pas être modifié.']);
                exit;
            }

            $stmtUpdate = $pdo->prepare('UPDATE "utilisateur" SET "Role" = :role WHERE "ID_Utilisateur" = :id');
            $stmtUpdate->execute(['role' => $newRole, 'id' => $userId]);

            echo json_encode(['success' => true, 'message' => 'Rôle mis à jour avec succès.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur de mise à jour : ' . $e->getMessage()]);
        }
        break;

    case 'delete_user':
        $userId = intval($input['user_id'] ?? 0);
        if ($userId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Identifiant utilisateur invalide.']);
            exit;
        }

        try {
            $stmtCheck = $pdo->prepare('SELECT "Email", "Role" FROM "utilisateur" WHERE "ID_Utilisateur" = :id');
            $stmtCheck->execute(['id' => $userId]);
            $targetUser = $stmtCheck->fetch();

            if (!$targetUser) {
                echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable.']);
                exit;
            }

            // Protéger le superadmin et les autres admins (si l'exécuteur n'est pas superadmin)
            if ($targetUser['Role'] === 'superadmin' || $targetUser['Email'] === 'ryadbenyakoub@gmail.com') {
                echo json_encode(['success' => false, 'message' => 'Le superadmin principal ne peut pas être supprimé.']);
                exit;
            }

            if ($targetUser['Role'] === 'admin' && $_SESSION['user_role'] !== 'superadmin') {
                echo json_encode(['success' => false, 'message' => 'Seul le superadmin peut supprimer un compte administrateur.']);
                exit;
            }

            $stmtDelete = $pdo->prepare('DELETE FROM "utilisateur" WHERE "ID_Utilisateur" = :id');
            $stmtDelete->execute(['id' => $userId]);

            echo json_encode(['success' => true, 'message' => 'Compte utilisateur supprimé avec succès.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
        break;

    case 'get_destinations':
        try {
            // Récupérer la liste complète des recommandations et des détails du lieu associé
            $stmt = $pdo->query('
                SELECT r."ID_Recommandation" AS id, r."Titre", r."Description" AS rec_desc, r."Note_Generale",
                       l."ID_Lieu" AS lieu_id, l."Nom" AS lieu_nom, l."Address", l."Description" AS lieu_desc
                FROM "recommandation" r
                JOIN "lieu" l ON r."ID_Lieu" = l."ID_Lieu"
                ORDER BY r."ID_Recommandation" DESC
            ');
            $destinations = $stmt->fetchAll();
            echo json_encode(['success' => true, 'data' => $destinations]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
        }
        break;

    case 'add_destination':
        $lieuNom   = trim($input['lieu_nom'] ?? '');
        $lieuDesc  = trim($input['lieu_description'] ?? '');
        $lieuAddr  = trim($input['lieu_address'] ?? '');
        $recTitre  = trim($input['rec_titre'] ?? '');
        $recDesc   = trim($input['rec_description'] ?? '');
        $recNote   = intval($input['rec_note'] ?? 5);
        $imageUrl  = $input['lieu_image'] ?? ''; // Chaîne Base64
        $tagsStr   = trim($input['tags'] ?? '');

        if (empty($lieuNom) || empty($lieuDesc) || empty($lieuAddr) || empty($recTitre) || empty($recDesc)) {
            echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires.']);
            exit;
        }

        // Décoder l'image Base64 si fournie
        $imageBinary = null;
        if (!empty($imageUrl)) {
            // Retirer le préfixe data:image/jpeg;base64,
            $base64Data = preg_replace('#^data:image/\w+;base64,#i', '', $imageUrl);
            $imageBinary = base64_decode($base64Data);
            if ($imageBinary === false) {
                $imageBinary = null;
            }
        }

        try {
            $pdo->beginTransaction();

            // 1. Insérer le lieu
            $stmtLieu = $pdo->prepare('
                INSERT INTO "lieu" ("Nom", "Description", "Address", "Image")
                VALUES (:nom, :desc, :addr, :img)
            ');
            $stmtLieu->bindParam(':nom', $lieuNom);
            $stmtLieu->bindParam(':desc', $lieuDesc);
            $stmtLieu->bindParam(':addr', $lieuAddr);
            $stmtLieu->bindParam(':img', $imageBinary, PDO::PARAM_LOB);
            $stmtLieu->execute();

            $lieuId = $pdo->lastInsertId();

            // 2. Insérer la recommandation
            $stmtRec = $pdo->prepare('
                INSERT INTO "recommandation" ("ID_Lieu", "Titre", "Description", "Note_Generale")
                VALUES (:lieu_id, :titre, :desc, :note)
            ');
            $stmtRec->execute([
                'lieu_id' => $lieuId,
                'titre'   => $recTitre,
                'desc'    => $recDesc,
                'note'    => $recNote
            ]);
            $recId = $pdo->lastInsertId();

            // 3. Associer les tags
            if (!empty($tagsStr)) {
                $tags = array_unique(array_filter(array_map('trim', explode(',', $tagsStr))));
                foreach ($tags as $tagName) {
                    if ($tagName === '') continue;

                    // Chercher ou créer le tag
                    $stmtTagCheck = $pdo->prepare('SELECT "ID_Tag" FROM "tag" WHERE "Nom" ILIKE :nom');
                    $stmtTagCheck->execute(['nom' => $tagName]);
                    $tagId = $stmtTagCheck->fetchColumn();

                    if (!$tagId) {
                        $stmtTagInsert = $pdo->prepare('INSERT INTO "tag" ("Nom") VALUES (:nom)');
                        $stmtTagInsert->execute(['nom' => $tagName]);
                        $tagId = $pdo->lastInsertId();
                    }

                    // Lier le tag à la recommandation
                    $stmtLink = $pdo->prepare('
                        INSERT INTO "recommandation_tag" ("ID_Recommandation", "ID_Tag")
                        VALUES (:rec_id, :tag_id)
                        ON CONFLICT DO NOTHING
                    ');
                    $stmtLink->execute([
                        'rec_id' => $recId,
                        'tag_id' => $tagId
                    ]);
                }
            }

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Destination et recommandation ajoutées avec succès !']);

        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout : ' . $e->getMessage()]);
        }
        break;

    case 'delete_destination':
        $recId = intval($input['rec_id'] ?? 0);
        if ($recId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Identifiant de recommandation invalide.']);
            exit;
        }

        try {
            $pdo->beginTransaction();

            // Trouver l'ID du lieu lié
            $stmtFind = $pdo->prepare('SELECT "ID_Lieu" FROM "recommandation" WHERE "ID_Recommandation" = :id');
            $stmtFind->execute(['id' => $recId]);
            $lieuId = $stmtFind->fetchColumn();

            if (!$lieuId) {
                echo json_encode(['success' => false, 'message' => 'Recommandation introuvable.']);
                exit;
            }

            // Supprimer la recommandation (cascade supprimera la liaison dans recommandation_tag)
            $stmtDeleteRec = $pdo->prepare('DELETE FROM "recommandation" WHERE "ID_Recommandation" = :id');
            $stmtDeleteRec->execute(['id' => $recId]);

            // Supprimer le lieu s'il n'y a pas d'autres recommandations rattachées à ce lieu
            $stmtCheckLieu = $pdo->prepare('SELECT COUNT(*) FROM "recommandation" WHERE "ID_Lieu" = :lieu_id');
            $stmtCheckLieu->execute(['lieu_id' => $lieuId]);
            $count = $stmtCheckLieu->fetchColumn();

            if ($count == 0) {
                $stmtDeleteLieu = $pdo->prepare('DELETE FROM "lieu" WHERE "ID_Lieu" = :lieu_id');
                $stmtDeleteLieu->execute(['lieu_id' => $lieuId]);
            }

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Destination supprimée avec succès.']);

        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Action administrative inconnue.']);
        break;
}

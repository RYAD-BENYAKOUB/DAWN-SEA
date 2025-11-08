<?php
    require_once __DIR__ . '/session_config.php';
    $pdo = new PDO("mysql:host=localhost;dbname=dawnsea4;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tag = $_GET['tag'] ?? '';

    $stmt = $pdo->prepare("
        SELECT r.*, l.Nom AS lieu_nom, l.Image
        FROM recommandation r
        JOIN lieu l ON r.ID_Lieu = l.ID_Lieu
        JOIN recommandation_tag rt ON r.ID_Recommandation = rt.ID_Recommandation
        JOIN tag t ON rt.ID_Tag = t.ID_Tag
        WHERE t.Nom = ?
    ");
    $stmt->execute([$tag]);
    $recommandations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/CSS/oran.css">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/CSS/bootstrap.min.css">
        <!-- Custom CSS -->
        <link rel="stylesheet" href="assets/CSS/profil.css">
    <title>DAWNSEA</title>
</head>
<body>
    <header>
        <div class="container-fluid px-0">
            <nav class="navbar navbar-expand-md navbar-dark bg-dark p-2">
                <img src="IMG/4-removebg-preview.png" alt="Mon Logo" class="navbar-brand logo-navbar">
                <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#x">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <script>
                    function confirmLogout() {
                        if (confirm("Voulez-vous vraiment vous déconnecter ?")) {
                            window.location.href = "logout.php";
                        }
                    }
                </script>

                <div id="x" class="collapse navbar-collapse w-100 justify-content-center">
                    <ul class="navbar-nav d-flex gap-4 align-items-center">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                        <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-info btn-sm" href="LOGIN.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-success btn-sm" href="SIGN_UP.php">Sign_Up</a>
                        </li>
                        <li class="nav-item">
                            <form id="SRCH" class="d-flex" role="search">
                                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                <button class="btn btn-outline-success" type="submit">Search</button>
                            </form>
                        </li>
                        <!-- Bienvenue -->
                    <span class="navbar-text text-white">
                        Bienvenue, <strong><?= htmlspecialchars($user['prenom'] ?? 'Utilisateur') ?></strong>
                    </span>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <div class="container mt-4">
        <h1 class="mb-4">Résultats pour le tag : <span class="text-warning"><?= htmlspecialchars($tag) ?></span></h1>

        <?php if (empty($recommandations)): ?>
            <p>Aucune recommandation trouvée pour le tag <strong><?= htmlspecialchars($tag) ?></strong>.</p>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach ($recommandations as $rec): ?>
                    <div class="col">
                        <div class="card h-100">
                           <?php if (!empty($rec['Image'])): ?>
                                    <?php
                                        //1)base64-encoder le binaire

                                        $base64=base64_encode($rec['Image']);
                                        
                                        //2)preparer le prefixe DATA URI
                                        $src='data:image/jpeg;base64,'.$base64; 
                                    ?>

                                    <img src="<?= $src ?>" class="card-img-top" alt="<?= htmlspecialchars($rec['lieu_nom'],ENT_QUOTES) ?>">
                            <?php endif; ?>
                            <div class="card-body bg-dark text-light d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title text-warning"><?= htmlspecialchars($rec['Titre']) ?> - <?= htmlspecialchars($rec['lieu_nom']) ?></h5>
                                    <p class="card-text text-light"><?= htmlspecialchars($rec['Description']) ?></p>
                                    <p class="card-text text-light fs-5">Note : <?= $rec['Note_Generale'] ?> ⭐</p>
                                </div>
                                <div class="gap-2 mt-3">
                                    <?php
                                    $tagStmt = $pdo->prepare("
                                        SELECT t.Nom FROM tag t
                                        JOIN recommandation_tag rt ON t.ID_Tag = rt.ID_Tag
                                        WHERE rt.ID_Recommandation = ?
                                    ");
                                    $tagStmt->execute([$rec['ID_Recommandation']]);
                                    $tags = $tagStmt->fetchAll(PDO::FETCH_COLUMN);
                                    foreach ($tags as $tagName): ?>
                                        <a href="tags.php?tag=<?= urlencode($tagName) ?>" class="btn btn-danger">
                                            <?= htmlspecialchars($tagName) ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <article id="contact" class="d-flex justify-content-center gap-5 flex-wrap">
            <div class="platform">
                <div class="logo-container">
                    <img id="FCB_LOGO" src="IMG/facebook-new (1).png" alt="Facebook Logo">
                    <h3 class="text-light bg-dark">facebook</h3>
                </div>
                <div class="links-container">
                    <a href="https://www.facebook.com/profile.php?id=100009239179515&locale=fr_FR">Benyakoub Mohammed Ryad</a>
                    <a href="https://www.facebook.com/lmojrim.hoiry?locale=fr_FR">Addou Houssem Edinne Abdel Ilah</a>
                </div>
            </div>

            <div class="platform">
                <div class="logo-container">
                    <img id="FCB_LOGO" src="IMG/insta-white-removebg-preview.png" alt="Instagram Logo">
                    <h3 class="text-light bg-dark">instagram</h3>
                </div>
                <div class="links-container">
                    <a href="https://www.instagram.com/ryad_benyakoub?igsh=N3ZjYTF0aDBneGJw">Benyakoub Mohammed Ryad</a>
                    <a href="https://www.instagram.com/a.ghy?igsh=MXR0bGIyd3BxNHU2dQ==">Addou Houssem Edinne Abdel Ilah</a>
                </div>
            </div>

            <div class="platform">
                <div class="logo-container">
                    <img id="FCB_LOGO" src="IMG/Gmail_icon-removebg-preview.png" alt="Email Logo">
                    <h3 class="text-light bg-dark">email</h3>
                </div>
                <div class="links-container">
                    <span>ryadbenyakoub@gmail.com</span>
                    <span>aghy3113@gmail.com</span>
                </div>
            </div>
        </article>

        <script src="assets/JS/bootstrap.bundle.min.js"></script>
        <p class="text-light bg-dark mt-3">&copy; 2025 Dawn & Sea. Tous droits réservés.</p>
    </footer>
</body>
</html>
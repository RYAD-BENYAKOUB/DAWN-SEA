<?php
    require_once __DIR__ . '/session_config.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../LOGIN.php");
        exit;
    }

    // Connexion à la base de données
    $host = 'localhost';
    $dbname = 'dawnsea4';
    $user_db = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user_db, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer les infos de l'utilisateur connecté
        $stmt = $pdo->prepare("SELECT prenom FROM utilisateur WHERE ID_Utilisateur = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        exit;
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dawn & Sea</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/CSS/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/CSS/about.css">
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

    <main>
        <div class="container">
            <h1>À propos dawn&sea</h1>
            <p class="para">
                <span id="DS">Dawn&Sea</span> est une plateforme web innovante dédiée à la recommandation touristique en Algérie,
                conçue pour mettre en valeur la richesse culturelle, naturelle et historique du pays. 
                Ce projet a été développé durant l’dannée universitaire 2024/2025, 
                dans le cadre d’un projet de fin d’étude par BENYAKOUB Mohammed Ryad et ADDOU Houssem Eddine Abdel Illah, 
                étudiants en licence d’ingénierie des systèmes d'information et logiciel.
                Encadré par Monsieur ZENNAKI.M, enseignant et expert dans le domaine, 
                ce projet vise à offrir aux utilisateurs une expérience personnalisée et intuitive pour découvrir les meilleures destinations, 
                attractions et activités à travers tout le territoire algérien.
             
            </p>
            <h1>ABOUT dawn&sea</h1>
            <p class="para">
                <span id="DS">Dawn&Sea</span> is an innovative web platform dedicated to tourism recommendation in Algeria,
                designed to highlight the country’s cultural, natural, and historical wealth. 
                This project was developed during the 2024/2025 academic year as part of a final year graduation project by BENYAKOUB Mohammed Ryad and ADDOU Houssem Eddine Abdel Illah, 
                students in the Bachelor's program in Information Systems and Software Engineering.
                Supervised by Professor ZENNAKI.M, 
                the project aims to provide users with a personalized and intuitive experience, 
                helping them discover top destinations, attractions, 
                and activities across Algeria.     
            </p>            
        </div>
    </main>
    <footer class="bg-dark text-white text-center py-3 mt-4">
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
        <p class="text-light bg-dark">&copy; 2025 Dawn & Sea. Tous droits réservés.</p>
    </footer>
</body>
</html>

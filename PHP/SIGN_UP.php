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
    <link rel="stylesheet" href="assets/CSS/sign_up.css">
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
            <div class="container d-flex justify-content-center align-items-center min-vh-100" id="form-container">
                <fieldset class=" bg-dark">
                    <legend>SIGN-UP</legend>
                    <form id="SIGN-UP" method="POST" action="PHP/SIGN_UP2.php">
                    
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom :</label>
                            <input type="text" class="form-control" id="nom" name="nom" required placeholder="votre nom ">
                        </div>
    
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom :</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required placeholder="votre prenom">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">email :</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="votre email ">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">mot de passe :</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="votre mot de passe ">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe :</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="confirme votre mot de passe">
                        </div>
                        
                        <div class="mb-3">
                            <label for="num_de_telephone" class="form-label">Numéro de téléphone :</label>
                            <div class="d-flex gap-2">
                                <select class="form-control w-25" id="indicatif" name="indicatif" required>
                                    <option value="">+Indicatif</option>
                                    <option value="+213">Algérie (+213)</option>
                                    <option value="+33">France (+33)</option>
                                    <option value="+1">États-Unis (+1)</option>
                                    <option value="+44">Royaume-Uni (+44)</option>
                                    <option value="+49">Allemagne (+49)</option>
                                    <option value="+39">Italie (+39)</option>
                                    <option value="+34">Espagne (+34)</option>
                                    <option value="+41">Suisse (+41)</option>
                                    <option value="+32">Belgique (+32)</option>
                                    <option value="+216">Tunisie (+216)</option>
                                    <option value="+212">Maroc (+212)</option>
                                    <option value="+351">Portugal (+351)</option>
                                    <option value="+90">Turquie (+90)</option>
                                    <option value="+7">Russie (+7)</option>
                                    <option value="+86">Chine (+86)</option>
                                    <option value="+81">Japon (+81)</option>
                                    <option value="+91">Inde (+91)</option>
                                    <option value="+61">Australie (+61)</option>
                                    <option value="+27">Afrique du Sud (+27)</option>
                                    <option value="+82">Corée du Sud (+82)</option>
                                    <option value="+46">Suède (+46)</option>
                                    <option value="+47">Norvège (+47)</option>
                                    <option value="+48">Pologne (+48)</option>
                                    <option value="+380">Ukraine (+380)</option>
                                    <option value="+966">Arabie Saoudite (+966)</option>
                                    <option value="+971">Émirats Arabes Unis (+971)</option>
                                    <option value="+964">Irak (+964)</option>
                                    <option value="+20">Égypte (+20)</option>
                                    <option value="+94">Sri Lanka (+94)</option>
                                </select>
                                <input type="tel" class="form-control" id="num_de_telephone" name="num_de_telephone" required placeholder="votre numero de telephone">
                            </div>
                        </div>
    
                        <div class="mb-3">
                            <label for="pays_naissance" class="form-label">Pays de naissance :</label>
                            <select class="form-control" id="pays_naissance" name="pays_naissance" required placeholder="votre date de naissance">
                                <option value="">-- Sélectionnez un pays --</option>
                                <option value="AF">🇦🇫 Afghanistan</option>
                                <option value="ZA">🇿🇦 Afrique du Sud</option>
                                <option value="AL">🇦🇱 Albanie</option>
                                <option value="DZ">🇩🇿 Algérie</option>
                                <option value="DE">🇩🇪 Allemagne</option>
                                <option value="AD">🇦🇩 Andorre</option>
                                <option value="AO">🇦🇴 Angola</option>
                                <option value="SA">🇸🇦 Arabie saoudite</option>
                                <option value="AR">🇦🇷 Argentine</option>
                                <option value="AM">🇦🇲 Arménie</option>
                                <option value="AU">🇦🇺 Australie</option>
                                <option value="AT">🇦🇹 Autriche</option>
                                <option value="AZ">🇦🇿 Azerbaïdjan</option>
                                <option value="BE">🇧🇪 Belgique</option>
                                <option value="BJ">🇧🇯 Bénin</option>
                                <option value="BO">🇧🇴 Bolivie</option>
                                <option value="BA">🇧🇦 Bosnie-Herzégovine</option>
                                <option value="BR">🇧🇷 Brésil</option>
                                <option value="BG">🇧🇬 Bulgarie</option>
                                <option value="BF">🇧🇫 Burkina Faso</option>
                                <option value="BI">🇧🇮 Burundi</option>
                                <option value="KH">🇰🇭 Cambodge</option>
                                <option value="CM">🇨🇲 Cameroun</option>
                                <option value="CA">🇨🇦 Canada</option>
                                <option value="CL">🇨🇱 Chili</option>
                                <option value="CN">🇨🇳 Chine</option>
                                <option value="CY">🇨🇾 Chypre</option>
                                <option value="CO">🇨🇴 Colombie</option>
                                <option value="KM">🇰🇲 Comores</option>
                                <option value="CG">🇨🇬 Congo-Brazzaville</option>
                                <option value="CD">🇨🇩 Congo-Kinshasa</option>
                                <option value="KR">🇰🇷 Corée du Sud</option>
                                <option value="CI">🇨🇮 Côte d'Ivoire</option>
                                <option value="HR">🇭🇷 Croatie</option>
                                <option value="DK">🇩🇰 Danemark</option>
                                <option value="DJ">🇩🇯 Djibouti</option>
                                <option value="EG">🇪🇬 Égypte</option>
                                <option value="AE">🇦🇪 Émirats arabes unis</option>
                                <option value="ES">🇪🇸 Espagne</option>
                                <option value="US">🇺🇸 États-Unis</option>
                                <option value="ET">🇪🇹 Éthiopie</option>
                                <option value="FI">🇫🇮 Finlande</option>
                                <option value="FR">🇫🇷 France</option>
                                <option value="GA">🇬🇦 Gabon</option>
                                <option value="GH">🇬🇭 Ghana</option>
                                <option value="GR">🇬🇷 Grèce</option>
                                <option value="HU">🇭🇺 Hongrie</option>
                                <option value="IN">🇮🇳 Inde</option>
                                <option value="ID">🇮🇩 Indonésie</option>
                                <option value="IE">🇮🇪 Irlande</option>
                                <option value="IS">🇮🇸 Islande</option>
                                <option value="IT">🇮🇹 Italie</option>
                                <option value="JP">🇯🇵 Japon</option>
                                <option value="KE">🇰🇪 Kenya</option>
                                <option value="KZ">🇰🇿 Kazakhstan</option>
                                <option value="LB">🇱🇧 Liban</option>
                                <option value="LY">🇱🇾 Libye</option>
                                <option value="LI">🇱🇮 Liechtenstein</option>
                                <option value="LT">🇱🇹 Lituanie</option>
                                <option value="LU">🇱🇺 Luxembourg</option>
                            </select>
                        </div>
    
                        <div class="mb-3">
                            <label class="form-label" for="date_naissance">Date de Naissance :</label>
                            <input class="form-control" id="date_naissance" name="date_naissance" type="date">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">Soumettre</button>

                            <button type="reset" class="btn btn-danger">Annuler</button>
                        </div>
                    </form>
                </fieldset>
            </div>
            
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
                    <span>ryadbenyakoub@gmail.com</span><br>
                    <span>aghy3113@gmail.com</span>
                </div>
            </div>
        </article>
        <script src="assets/JS/bootstrap.bundle.min.js"></script>
        <p class="text-light bg-dark">&copy; 2025 Dawn & Sea. Tous droits réservés.</p>
    </footer>
</body>
</html>

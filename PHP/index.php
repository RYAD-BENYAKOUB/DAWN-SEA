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
    <link rel="stylesheet" href="assets/CSS/style.css">
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

</body>
</html>

        <main>
            <div class="container text-center">
                <h1>Dawn & Sea</h1>
                <h2>Explore more, discover more</h2>
                <h3 class="text-center text-uppercase fw-bold text-decoration-underline">
                    dawn & sea est un site web de recommandation de tourisme en Algérie
                </h3>
                <br>
                <!-- Cartes touristiques -->
                <div class="container">
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <div class="col">
                        <div class="card">
                            <img src="IMG/oran2.JPG" class="card-img-top" alt="...">
                            <div class="card-body bg-dark">
                                <h5 class="card-title text-warning">ORAN</h5>
                                    <p class="card-text text-light">
                                        Oran est une ville portuaire au nord-ouest de l'Algérie. 
                                        Elle est considérée comme le berceau de la musique raï. 
                                        Le fort de Santa Cruz, une citadelle ottomane reconstruite par les Espagnols, 
                                        se trouve au sommet du mont Murdjadjo ; 
                                        il offre une vue sur la baie en contrebas. La chapelle Notre-Dame de Santa Cruz, 
                                        aux murs blanchis, se trouve à proximité. 
                                        Elle fut érigée en l'honneur de son homonyme après une épidémie de choléra. 
                                        À la Blanca, la vieille ville turque, se situe la mosquée Hassan Pacha. 
                                        Cette dernière est dotée d'un minaret octogonal.                          
                                    </p>
                                    <a class="btn btn-danger" href="Recommandations/Oran.php">See Details</a>
                            </div>
                        </div>
                        </div>
                        <div class="col">
                        <div class="card">
                            <img src="IMG/alger2.jpg" class="card-img-top" alt="...">
                            <div class="card-body bg-dark">
                            <h5 class="card-title text-warning">ALGER</h5>
                            <p class="card-text text-light">
                                    Alger est la capitale de l'Algérie.
                                    Elle se trouve sur la côte méditerranéenne du pays.
                                    Elle est connue pour les bâtiments blanchis à la chaux de la Casbah,
                                    une médina dotée de rues escarpées et sinueuses, 
                                    de palais ottomans et d'une citadelle en ruines. 
                                    La mosquée Ketchaoua, datant du XVIIe siècle, est flanquée de 2 immenses minarets. 
                                    La Grande Mosquée possède des colonnes et des arches en marbre. 
                                    La basilique catholique Notre-Dame d'Afrique, 
                                    qui se dresse sur une colline, 
                                    arbore un grand dôme argenté et des mosaïques.
                                
                                </p>
                                <a class="btn btn-danger" href="">See Details</a>
                            </div>
                        </div>
                        </div>
                        <div class="col">
                        <div class="card">
                            <img src="IMG/ANNABA_2.jpg" class="card-img-top" alt="...">
                            <div class="card-body bg-dark">
                            <h5 class="card-title text-warning">ANNABA</h5>
                                <p class="card-text text-light">
                                    Annaba est une ville portuaire au nord-est de l'Algérie.
                                    Sur le cours de la Révolution, 
                                    la rue principale dotée d'une vaste promenade centrale, 
                                    l'architecture reflète le passé colonial français de la ville. 
                                    La basilique Saint-Augustin, achevée en 1900, se dresse sur une colline au sud. 
                                    Sous cette dernière s'étirent les ruines de la ville d'Hippone (Hippo Regius), 
                                    dont des vestiges de villas et de bains romains. 
                                    Le musée d'Hippone expose des mosaïques et des objets provenant du site. 
                                    
                                </p>
                                <a class="btn btn-danger" href="">See Details</a>
                            </div>
                        </div>
                        </div>
                        <div class="col">
                        <div class="card">
                            <img src="IMG/Tlemcen.jpg" class="card-img-top" alt="...">
                            <div class="card-body bg-dark">
                            <h5 class="card-title text-warning">TLEMCEN</h5>
                                <p class="card-text text-light">
                                    Tlemcen est une ville située au nord de l'Algérie. 
                                    Elle est célèbre pour ses bâtiments mauresques, 
                                    tels que la Grande Mosquée du XIe siècle avec son haut minaret et son mihrab (niche indiquant la direction de La Mecque) élaboré. 
                                    Le mausolée de Sidi Boumediene, maître soufi du XIIe siècle, est un lieu de pèlerinage. 
                                    Sa mosquée adjacente est un exemple d'architecture almoravide, avec ses panneaux de stuc sculpté. 
                                    Dans le centre-ville, le palais El Mechouar datant du XIIe siècle, est protégé par de hauts murs. 
                                    
                                </p>
                                <a class="btn btn-danger" href="">See Details</a>
                            </div>
                        </div>
                        </div>
                    </div>
                    <br>
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <div class="col">
                        <div class="card">
                            <img src="IMG/constantine.jpg" class="card-img-top" alt="...">
                            <div class="card-body bg-dark">
                            <h5 class="card-title text-warning">CONSTANTINE</h5>
                                <p class="card-text text-light">
                                    Constantine est une commune du nord-est de l'Algérie, 
                                    chef-lieu de la wilaya de Constantine. 
                                    Elle est la capitale de l'Est de l'Algérie, 
                                    ses 475 510 habitants classent cette métropole au rang de la troisième 
                                    ville du pays après Alger et Oran.
                                </p>
                                <a class="btn btn-danger" href="">See Details</a>
                            </div>
                        </div>
                        </div>
                        <div class="col">
                        <div class="card">
                            <img src="IMG/bejaya3.jpg" class="card-img-top" alt="...">
                            <div class="card-body bg-dark">
                            <h5 class="card-title text-warning">BEJAIA</h5>
                            <p class="card-text text-light">
                                Béjaïa, Bougie pendant la période coloniale française, 
                                est une commune algérienne située en bordure de la mer Méditerranée, 
                                à 220 km à l'est d'Alger. 
                                Elle est le chef-lieu de la wilaya de Béjaïa et de la daïra de Béjaïa, 
                                en Kabylie. 
                                Le site de la ville est peuplé depuis le paléolithique.
                                
                                </p>
                                <a class="btn btn-danger" href="">See Details</a>
                            </div>
                        </div>
                        </div>
                        <div class="col">
                        <div class="card">
                            <img src="IMG/tizi-ouzou.jpg" class="card-img-top" alt="...">
                            <div class="card-body bg-dark">
                            <h5 class="card-title text-warning">TIZI-OUZOU</h5>
                                <p class="card-text text-light">
                                    Tizi Ouzou ou Thizi Wezzu
                                    est une commune algérienne située à 30 km au sud des côtes méditerranéennes
                                    et à 100 km à l'est de la capitale Alger. 
                                    Elle est le chef-lieu de la Wilaya de Tizi Ouzou et de la Daïra de Tizi Ouzou, en Kabylie. 
                                    
                                </p>
                                <a class="btn btn-danger" href="">See Details</a>
                            </div>
                        </div>
                        </div>
                        <div class="col">
                        <div class="card">
                            <img src="IMG/JIJEL.jpg" class="card-img-top" alt="...">
                            <div class="card-body bg-dark">
                            <h5 class="card-title text-warning">JIJEL</h5>
                                <p class="card-text text-light">
                                    Jijel [désignée sous le nom de Djidjelli du temps de la colonisation française], 
                                    est une ville et commune d'Algérie de la wilaya de Jijel située à l'est de la Petite Kabylie, 
                                    dont elle est le chef-lieu. 
                                    Elle est considérée comme la capitale de la confédération berbère des Kutamas. 
                                    
                                </p>
                                <a class="btn btn-danger" href="">See Details</a>
                            </div>
                        </div>
                        </div>
                    </div>
                    <br>
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <div class="col">
                        <div class="card">
                            <img src="IMG/bechar2.jpg" class="card-img-top" alt="...">
                            <div class="card-body bg-dark">
                            <h5 class="card-title text-warning">BECHAR</h5>
                                <p class="card-text text-light">
                                    Béchar, anciennement Colomb-Béchar pendant la colonisation française après 1902, 
                                    est une commune de la wilaya de Béchar, en Algérie, dont elle est le chef-lieu, 
                                    située à 1 150 km au sud-ouest de la capitale Alger, 
                                    à 852 km au nord-est de Tindouf et à environ 80 km à l'est de la frontière marocaine.
                                </p>
                                <a class="btn btn-danger" href="">See Details</a>
                            </div>
                        </div>
                        </div>
                        <div class="col">
                        <div class="card">
                            <img src="IMG/bejaya3.jpg" class="card-img-top" alt="...">
                            <div class="card-body bg-dark">
                            <h5 class="card-title text-warning">TAMANRASSET</h5>
                                <p class="card-text text-light">
                                    Tamanrasset ou Tamanghasset, 
                                    est une commune de la wilaya de Tamanrasset, 
                                    dont elle est le chef-lieu, 
                                    située dans le Sud de l'Algérie, à 1 900 km au sud d'Alger, 
                                    à 450 km à vol d'oiseau au sud-ouest de Djanet et à environ 400 km au nord de la frontière malienne. 
                                    Elle est la capitale des Touaregs algériens.      
                                </p>
                                <a class="btn btn-danger" href="">See Details</a>
                            </div>
                        </div>
                        </div>
                        <div class="col">
                        <div class="card">
                            <img src="IMG/licensed-image (1).jpg" class="card-img-top" alt="...">
                            <div class="card-body bg-dark">
                            <h5 class="card-title text-warning">ADRAR</h5>
                                <p class="card-text text-light">
                                    Adrar est une commune de la wilaya homonyme, 
                                    dont elle est le chef-lieu, 
                                    située à 1 400 km au sud-ouest d'Alger.
                                </p>
                                <a class="btn btn-danger" href="">See Details</a>
                            </div>
                        </div>
                        </div>
                        <div class="col">
                        <div class="card">
                            <img src="IMG/biskra.jpg" class="card-img-top" alt="...">
                            <div class="card-body bg-dark">
                            <h5 class="card-title text-warning">BISKRA</h5>
                                <p class="card-text text-light">
                                    Biskra est une commune du Nord-est du Sahara algérien, 
                                    chef-lieu de la wilaya de Biskra, 
                                    située à 400 km environ au sud-est d'Alger. 
                                    Capitale des Zibans et premier pôle urbain saharien, 
                                    la ville comptait 205 608 habitants en 2008 
                                    et se place au 10ᵉ rang au niveau national. 
                                    
                                </p>
                                <a class="btn btn-danger" href="">See Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                    <h1 style="color: black; text-decoration: underline;text-transform: uppercase; font-weight: bold;">SOME PICTURES ABOUT ALGERIA</h1>
                    <br>
                
                <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active" data-bs-interval="5000">
                                <img src="IMG/SLIDER/A.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item" data-bs-interval="1000">
                                <img src="IMG/SLIDER/B.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item" data-bs-interval="1000">
                                <img src="IMG/SLIDER/C.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item" data-bs-interval="1000">
                                <img src="IMG/SLIDER/D.jpg" class="d-block w-100" alt="...">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
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
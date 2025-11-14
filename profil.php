<?php
    require_once __DIR__ . '/PHP/session_config.php';

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
        <link rel="stylesheet" href="assets/CSS/profil.css">
        
    </head>
<body>
    <header>
        <div class="container-fluid px-0">
            
            <nav class="navbar navbar-expand-md navbar-dark bg-dark p-2">
                <img src="IMG/4-removebg-preview.png" alt="Mon Logo" class="navbar-brand logo-navbar">
                <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#x">
                    <span class="navbar-toggler-icon"></span>
                </button>

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
                    </ul>
                </div>
            </nav>
       
        </div>
    </header>
    <main>
        <div class="container">
            <div class="form-container">
                <fieldset class=" bg-dark">
                    <legend>TON PROFIL</legend>
                    <form id="SIGN-UP">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom :</label>
                            <input type="text" class="form-control" id="nom" name="nom">
                        </div>
    
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom :</label>
                            <input type="text" class="form-control" id="prenom" name="prenom">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">email :</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="num_tel" class="form-label">Numéro de téléphone :</label>
                            <div class="d-flex gap-2">
                                <input type="tel" class="form-control" id="num_tel" name="num_tel">
                            </div>
                        </div>
    
                        <div class="mb-3">
                            <label for="pays_naissance" class="form-label">Pays de naissance :</label>
                            <input type="text" class="form-control" id="pays_naissance" name="pays_naissance">

                        </div>
    
                        <div class="mb-3">
                            <label class="form-label" for="date_naissance">Date de Naissance :</label>
                            <input class="form-control" id="date_naissance" name="date_naissance" type="date">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">Modifier</button>
                            <button type="reset" class="btn btn-danger">Supprimer</button>
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

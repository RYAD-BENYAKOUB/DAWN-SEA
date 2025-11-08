<?php
    require_once __DIR__ . '/PHP/session_config.php';

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dawn & Sea</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/CSS/bootstrap.min.css">
        <!-- Custom CSS -->
        <link rel="stylesheet" href="assets/CSS/login.css">
        <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js"></script>
        <script>
            const supabaseUrl = 'https://cwxdorskmuteodiiixts.supabase.co'; // Remplacez par votre URL
            const supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImN3eGRvcnNrbXV0ZW9kaWlpeHRzIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDU0ODQ4NzAsImV4cCI6MjA2MTA2MDg3MH0.CcLms5Oh5HYpqVbsIe9WKZ8PN2jFGEPtzudJQ3liLA4'; // Remplacez par votre clé anonyme
            const supabase = supabase.createClient(supabaseUrl, supabaseKey);
          </script>
          
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
        <div class="container" >
            <fieldset class=" bg-dark">
                <legend>LOGIN</legend>
                <form id="LOGIN" method="POST" action="PHP/connexion.php">
                    <label for="email" class="form-label">email :</label>
                    <div class="mb-3 d-flex justify-content-center">
                        <input type="email" class="form-control" id="email" name="email" required placeholder="votre email ">
                    </div>
                    <label for="password" class="form-label">mot de passe :</label>
                    <div class="mb-3 d-flex justify-content-center">
                        <input type="password" class="form-control" id="password" name="password" required placeholder="votre mot de passe">
                    </div>
                    <div class="text-center">
                        <a id="compte" href="SIGN_UP.php">If you don't have an account</a>

                    </div>
                    
                    <button style="margin-top: 15px;" type="submit" class="btn btn-success">SUBMIT</button>
                    <button style="margin-top: 15px;" type="reset" class="btn btn-danger">CANCEL</button>
                </form>   
            </fieldset>
        
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

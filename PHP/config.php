<?php
// PHP/config.php

// Identifiants Google OAuth (à récupérer sur Google Cloud Console : https://console.cloud.google.com)
define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?: 'VOTRE_CLIENT_ID.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: 'VOTRE_CLIENT_SECRET');
define('GOOGLE_REDIRECT_URI', getenv('GOOGLE_REDIRECT_URI') ?: 'http://localhost/PFE2/PHP/google_callback.php');

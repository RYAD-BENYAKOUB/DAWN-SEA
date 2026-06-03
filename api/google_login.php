<?php
// PHP/google_login.php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/config.php';

// Générer un état de sécurité aléatoire pour contrer les failles CSRF
$state = bin2hex(random_bytes(16));
$_SESSION['oauth_state'] = $state;

// Construire les paramètres de la requête OAuth
$params = [
    'client_id'     => GOOGLE_CLIENT_ID,
    'redirect_uri'  => GOOGLE_REDIRECT_URI,
    'response_type' => 'code',
    'scope'         => 'openid email profile',
    'state'         => $state,
    'prompt'        => 'select_account' // Permet de toujours proposer le choix du compte
];

$authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);

// Rediriger vers Google
header('Location: ' . $authUrl);
exit;

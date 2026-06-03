<?php
// session_config.php

// Durée de la session en secondes (ici 1 heure)
$lifetime = 3600;

// Garbage‑collection : conserver les sessions $lifetime s, 1% de probabilité de GC
ini_set('session.gc_maxlifetime', $lifetime);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);

// Cookie de session
session_set_cookie_params([
    'lifetime' => $lifetime,
    'path'     => '/',       // disponible sur tout le site
    'secure'   => false,     // true si HTTPS
    'httponly' => true,      // empêche l’accès JS
    'samesite' => 'Lax'      // ou 'Strict'/'None'
]);

session_start();

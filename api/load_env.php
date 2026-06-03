<?php
// PHP/load_env.php

/**
 * Charge les variables d'environnement depuis le fichier .env
 */
function loadEnv($dir) {
    $path = rtrim($dir, '/') . '/.env';
    
    if (!file_exists($path)) {
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        
        // Ignorer les commentaires et les lignes vides
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        // Séparer la clé et la valeur par le premier '='
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Retirer les guillemets éventuels autour de la valeur
            if (preg_match('/^"([^"]*)"$/', $value, $matches) || preg_match('/^\'([^\']*)\'$/', $value, $matches)) {
                $value = $matches[1];
            }
            
            // Définir la variable d'environnement
            if (!getenv($key)) {
                putenv("$key=$value");
            }
            if (!isset($_ENV[$key])) {
                $_ENV[$key] = $value;
            }
            if (!isset($_SERVER[$key])) {
                $_SERVER[$key] = $value;
            }
        }
    }
    return true;
}

// Charger le .env depuis la racine du projet
loadEnv(__DIR__ . '/..');

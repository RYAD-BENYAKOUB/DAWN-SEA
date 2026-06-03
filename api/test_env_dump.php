<?php
header('Content-Type: application/json; charset=utf-8');

$host   = getenv('SUPABASE_DB_HOST');
$port   = getenv('SUPABASE_DB_PORT');
$dbname = getenv('SUPABASE_DB_NAME');
$user   = getenv('SUPABASE_DB_USER');
$pass   = getenv('SUPABASE_DB_PASSWORD');

echo json_encode([
    'host' => [
        'value' => $host,
        'length' => strlen($host),
        'hex' => bin2hex($host)
    ],
    'port' => [
        'value' => $port,
        'length' => strlen($port),
        'hex' => bin2hex($port)
    ],
    'dbname' => [
        'value' => $dbname,
        'length' => strlen($dbname),
        'hex' => bin2hex($dbname)
    ],
    'user' => [
        'value' => $user,
        'length' => strlen($user),
        'hex' => bin2hex($user)
    ],
    'pass' => [
        'length' => strlen($pass),
        'first' => $pass ? $pass[0] : null,
        'last' => $pass ? $pass[strlen($pass) - 1] : null
    ]
]);

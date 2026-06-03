<?php
// session_config.php

// Durée de la session en secondes (ici 1 heure)
$lifetime = 3600;

// Garbage‑collection : conserver les sessions $lifetime s, 1% de probabilité de GC
ini_set('session.gc_maxlifetime', $lifetime);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);

// Détecter si HTTPS est utilisé pour sécuriser les cookies de session (requis pour la prod Vercel, pas pour localhost HTTP)
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

// Cookie de session
session_set_cookie_params([
    'lifetime' => $lifetime,
    'path'     => '/',
    'secure'   => $isSecure,
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Si $pdo est disponible, on installe notre gestionnaire de session personnalisé basé sur la base de données
if (isset($pdo)) {
    if (!class_exists('DatabaseSessionHandler')) {
        class DatabaseSessionHandler implements SessionHandlerInterface {
            private $pdo;

            public function __construct($pdo) {
                $this->pdo = $pdo;
            }

            public function open($savePath, $sessionName): bool {
                return true;
            }

            public function close(): bool {
                return true;
            }

            public function read($sessionId): string {
                try {
                    $stmt = $this->pdo->prepare('SELECT "data" FROM "session" WHERE "id" = :id');
                    $stmt->execute([':id' => $sessionId]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    return $row ? $row['data'] : '';
                } catch (PDOException $e) {
                    return '';
                }
            }

            public function write($sessionId, $data): bool {
                try {
                    $timestamp = time();
                    $stmt = $this->pdo->prepare('
                        INSERT INTO "session" ("id", "data", "timestamp")
                        VALUES (:id, :data, :timestamp)
                        ON CONFLICT ("id") DO UPDATE
                        SET "data" = EXCLUDED."data", "timestamp" = EXCLUDED."timestamp"
                    ');
                    return $stmt->execute([
                        ':id' => $sessionId,
                        ':data' => $data,
                        ':timestamp' => $timestamp
                    ]);
                } catch (PDOException $e) {
                    return false;
                }
            }

            public function destroy($sessionId): bool {
                try {
                    $stmt = $this->pdo->prepare('DELETE FROM "session" WHERE "id" = :id');
                    return $stmt->execute([':id' => $sessionId]);
                } catch (PDOException $e) {
                    return false;
                }
            }

            public function gc($maxLifetime): int|false {
                try {
                    $old = time() - $maxLifetime;
                    $stmt = $this->pdo->prepare('DELETE FROM "session" WHERE "timestamp" < :old');
                    $stmt->execute([':old' => $old]);
                    return $stmt->rowCount();
                } catch (PDOException $e) {
                    return false;
                }
            }
        }
    }
    session_set_save_handler(new DatabaseSessionHandler($pdo), true);
}

session_start();

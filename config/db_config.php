<?php
/**
 * Database Configuration - Soori Travels
 * Provides PDO connection with security best practices
 */

// Prefer environment variables for CI/deployments; keep local-friendly fallbacks.
$isCi = getenv('CI') === 'true' || getenv('GITHUB_ACTIONS') === 'true';

define('DB_HOST', getenv('DB_HOST') ?: ($isCi ? '127.0.0.1' : 'localhost'));
define('DB_NAME', getenv('DB_NAME') ?: 'soori_travels');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: (getenv('DB_PASSWORD') ?: ($isCi ? 'root' : '')));
define('DB_CHARSET', 'utf8mb4');

/**
 * Get PDO database connection
 * Uses singleton pattern to avoid multiple connections
 */
function getDbConnection(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }
    return $pdo;
}

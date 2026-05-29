<?php
/**
 * API: Get all packages or single package
 * GET /api/get_packages.php
 * GET /api/get_packages.php?id=1
 */
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = getDbConnection();

    if (!empty($_GET['id']) && validateInt($_GET['id'])) {
        // Single package with images
        $stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
        $stmt->execute([(int)$_GET['id']]);
        $package = $stmt->fetch();

        if (!$package) {
            jsonResponse(['success' => false, 'message' => 'Package not found'], 404);
        }

        // Fetch package images
        $imgStmt = $pdo->prepare("SELECT id, image_url FROM package_images WHERE package_id = ?");
        $imgStmt->execute([(int)$_GET['id']]);
        $package['images'] = $imgStmt->fetchAll();

        jsonResponse(['success' => true, 'data' => $package]);
    } else {
        // All packages
        $stmt = $pdo->query("SELECT id, title, description, price, duration_days, location, thumbnail_url, status FROM packages WHERE status = 'active' ORDER BY id ASC");
        $packages = $stmt->fetchAll();

        jsonResponse(['success' => true, 'data' => $packages]);
    }
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Failed to fetch packages'], 500);
}

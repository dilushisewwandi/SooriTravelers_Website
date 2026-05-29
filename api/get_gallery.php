<?php
/**
 * API: Get gallery images
 * GET /api/get_gallery.php
 * Optional: ?category=travel+spot
 */
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = getDbConnection();

    if (!empty($_GET['category'])) {
        $stmt = $pdo->prepare("SELECT id, title, image_url, category FROM gallery WHERE category = ? ORDER BY id ASC");
        $stmt->execute([sanitizeInput($_GET['category'])]);
    } else {
        $stmt = $pdo->query("SELECT id, title, image_url, category FROM gallery ORDER BY id ASC");
    }

    $gallery = $stmt->fetchAll();
    jsonResponse(['success' => true, 'data' => $gallery]);
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Failed to fetch gallery'], 500);
}

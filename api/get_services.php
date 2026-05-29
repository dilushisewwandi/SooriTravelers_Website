<?php
/**
 * API: Get all services
 * GET /api/get_services.php
 */
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = getDbConnection();
    $stmt = $pdo->query("SELECT id, title, description, icon_class, image_url FROM services ORDER BY id ASC");
    $services = $stmt->fetchAll();

    jsonResponse(['success' => true, 'data' => $services]);
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Failed to fetch services'], 500);
}

<?php
/**
 * API: Get all destinations
 * GET /api/get_destinations.php
 */
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = getDbConnection();
    $stmt = $pdo->query("SELECT id, name, description, country, image_url FROM destinations ORDER BY id ASC");
    $destinations = $stmt->fetchAll();

    jsonResponse(['success' => true, 'data' => $destinations]);
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Failed to fetch destinations'], 500);
}

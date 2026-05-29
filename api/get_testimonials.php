<?php
/**
 * API: Get all testimonials
 * GET /api/get_testimonials.php
 */
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = getDbConnection();
    $stmt = $pdo->query("SELECT id, customer_name, review_text, rating, profile_image FROM testimonials ORDER BY id ASC");
    $testimonials = $stmt->fetchAll();

    jsonResponse(['success' => true, 'data' => $testimonials]);
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Failed to fetch testimonials'], 500);
}

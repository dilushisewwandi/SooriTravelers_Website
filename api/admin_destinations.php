<?php
/**
 * API: Admin - Manage Destinations (CRUD)
 * GET    - List all
 * POST   - Create (JSON)
 * PUT    - Update (JSON)
 * DELETE - Delete (JSON)
 */
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');
requireAdminAuth();

$pdo = getDbConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT * FROM destinations ORDER BY id ASC");
        jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
    }

    if ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int) ($data['id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM destinations WHERE id = ?");
        $stmt->execute([$id]);
        jsonResponse(['success' => true, 'message' => 'Destination deleted']);
    }

    if ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int) ($data['id'] ?? 0);
        $name = sanitizeInput($data['name'] ?? '');
        $country = sanitizeInput($data['country'] ?? '');
        $description = sanitizeInput($data['description'] ?? '');
        $imageUrl = sanitizeInput($data['image_url'] ?? '');

        $stmt = $pdo->prepare("UPDATE destinations SET name=?, country=?, description=?, image_url=? WHERE id=?");
        $stmt->execute([$name, $country, $description, $imageUrl, $id]);
        jsonResponse(['success' => true, 'message' => 'Destination updated']);
    }

    if ($method === 'POST') {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $data = json_decode(file_get_contents('php://input'), true);
        } else {
            $data = $_POST;
        }

        $name = sanitizeInput($data['name'] ?? '');
        $country = sanitizeInput($data['country'] ?? '');
        $description = sanitizeInput($data['description'] ?? '');
        $imageUrl = sanitizeInput($data['image_url'] ?? '');

        if (empty($name)) {
            jsonResponse(['success' => false, 'message' => 'Name is required'], 400);
        }

        $stmt = $pdo->prepare("INSERT INTO destinations (name, country, description, image_url) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $country, $description, $imageUrl]);
        jsonResponse(['success' => true, 'message' => 'Destination created', 'id' => $pdo->lastInsertId()], 201);
    }
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Operation failed'], 500);
}

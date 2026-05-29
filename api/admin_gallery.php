<?php
/**
 * API: Admin - Manage Gallery (CRUD)
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
        $stmt = $pdo->query("SELECT * FROM gallery ORDER BY id ASC");
        jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
    }

    if ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int) ($data['id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        jsonResponse(['success' => true, 'message' => 'Gallery item deleted']);
    }

    if ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int) ($data['id'] ?? 0);
        $title = sanitizeInput($data['title'] ?? '');
        $imageUrl = sanitizeInput($data['image_url'] ?? '');
        $category = sanitizeInput($data['category'] ?? '');

        $stmt = $pdo->prepare("UPDATE gallery SET title=?, image_url=?, category=? WHERE id=?");
        $stmt->execute([$title, $imageUrl, $category, $id]);
        jsonResponse(['success' => true, 'message' => 'Gallery item updated']);
    }

    if ($method === 'POST') {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $data = json_decode(file_get_contents('php://input'), true);
        } else {
            $data = $_POST;
        }

        $title = sanitizeInput($data['title'] ?? '');
        $imageUrl = sanitizeInput($data['image_url'] ?? '');
        $category = sanitizeInput($data['category'] ?? 'travel spot');

        if (empty($imageUrl)) {
            jsonResponse(['success' => false, 'message' => 'Image URL is required'], 400);
        }

        $stmt = $pdo->prepare("INSERT INTO gallery (title, image_url, category) VALUES (?, ?, ?)");
        $stmt->execute([$title, $imageUrl, $category]);
        jsonResponse(['success' => true, 'message' => 'Gallery image added', 'id' => $pdo->lastInsertId()], 201);
    }
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Operation failed'], 500);
}

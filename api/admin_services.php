<?php
/**
 * API: Admin - Manage Services (CRUD)
 * GET    - List all services
 * POST   - Create new service (JSON or form-data)
 * PUT    - Update existing service (JSON)
 * DELETE - Delete service (JSON)
 */
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');
requireAdminAuth();

$pdo = getDbConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT * FROM services ORDER BY id ASC");
        jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
    }

    if ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int) ($data['id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$id]);
        jsonResponse(['success' => true, 'message' => 'Service deleted']);
    }

    if ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int) ($data['id'] ?? 0);
        $title = sanitizeInput($data['title'] ?? '');
        $description = sanitizeInput($data['description'] ?? '');
        $iconClass = sanitizeInput($data['icon_class'] ?? '');

        $stmt = $pdo->prepare("UPDATE services SET title=?, description=?, icon_class=? WHERE id=?");
        $stmt->execute([$title, $description, $iconClass, $id]);
        jsonResponse(['success' => true, 'message' => 'Service updated']);
    }

    if ($method === 'POST') {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (strpos($contentType, 'application/json') !== false) {
            $data = json_decode(file_get_contents('php://input'), true);
            $title = sanitizeInput($data['title'] ?? '');
            $description = sanitizeInput($data['description'] ?? '');
            $iconClass = sanitizeInput($data['icon_class'] ?? '');
        } else {
            $title = sanitizeInput($_POST['title'] ?? '');
            $description = sanitizeInput($_POST['description'] ?? '');
            $iconClass = sanitizeInput($_POST['icon_class'] ?? '');
        }

        if (empty($title)) {
            jsonResponse(['success' => false, 'message' => 'Title is required'], 400);
        }

        $stmt = $pdo->prepare("INSERT INTO services (title, description, icon_class) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $iconClass]);
        jsonResponse(['success' => true, 'message' => 'Service created', 'id' => $pdo->lastInsertId()], 201);
    }
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Operation failed'], 500);
}

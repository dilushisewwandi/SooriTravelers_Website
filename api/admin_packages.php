<?php
/**
 * API: Admin - Manage Packages (CRUD)
 * GET    - List all packages
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
        $stmt = $pdo->query("SELECT * FROM packages ORDER BY id ASC");
        $packages = $stmt->fetchAll();

        foreach ($packages as &$pkg) {
            $imgStmt = $pdo->prepare("SELECT id, image_url FROM package_images WHERE package_id = ?");
            $imgStmt->execute([$pkg['id']]);
            $pkg['images'] = $imgStmt->fetchAll();
        }

        jsonResponse(['success' => true, 'data' => $packages]);
    }

    if ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int) ($data['id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM packages WHERE id = ?");
        $stmt->execute([$id]);
        jsonResponse(['success' => true, 'message' => 'Package deleted']);
    }

    if ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int) ($data['id'] ?? 0);
        $title = sanitizeInput($data['title'] ?? '');
        $description = sanitizeInput($data['description'] ?? '');
        $price = (float) ($data['price'] ?? 0);
        $durationDays = (int) ($data['duration_days'] ?? 1);
        $location = sanitizeInput($data['location'] ?? '');
        $thumbnailUrl = sanitizeInput($data['thumbnail_url'] ?? '');
        $status = sanitizeInput($data['status'] ?? 'active');

        $stmt = $pdo->prepare("UPDATE packages SET title=?, description=?, price=?, duration_days=?, location=?, thumbnail_url=?, status=? WHERE id=?");
        $stmt->execute([$title, $description, $price, $durationDays, $location, $thumbnailUrl, $status, $id]);
        jsonResponse(['success' => true, 'message' => 'Package updated']);
    }

    if ($method === 'POST') {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $data = json_decode(file_get_contents('php://input'), true);
        } else {
            $data = $_POST;
        }

        $title = sanitizeInput($data['title'] ?? '');
        $description = sanitizeInput($data['description'] ?? '');
        $price = (float) ($data['price'] ?? 0);
        $durationDays = (int) ($data['duration_days'] ?? 1);
        $location = sanitizeInput($data['location'] ?? '');
        $thumbnailUrl = sanitizeInput($data['thumbnail_url'] ?? '');
        $status = sanitizeInput($data['status'] ?? 'active');

        if (empty($title)) {
            jsonResponse(['success' => false, 'message' => 'Title is required'], 400);
        }

        $stmt = $pdo->prepare("INSERT INTO packages (title, description, price, duration_days, location, thumbnail_url, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $price, $durationDays, $location, $thumbnailUrl, $status]);
        jsonResponse(['success' => true, 'message' => 'Package created', 'id' => $pdo->lastInsertId()], 201);
    }
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Operation failed'], 500);
}

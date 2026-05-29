<?php
/**
 * API: Admin - Manage Testimonials (CRUD)
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
        $stmt = $pdo->query("SELECT * FROM testimonials ORDER BY id ASC");
        jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
    }

    if ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int) ($data['id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
        $stmt->execute([$id]);
        jsonResponse(['success' => true, 'message' => 'Testimonial deleted']);
    }

    if ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int) ($data['id'] ?? 0);
        $customerName = sanitizeInput($data['customer_name'] ?? '');
        $reviewText = sanitizeInput($data['review_text'] ?? '');
        $rating = (int) ($data['rating'] ?? 5);
        $profileImage = sanitizeInput($data['profile_image'] ?? '');

        $stmt = $pdo->prepare("UPDATE testimonials SET customer_name=?, review_text=?, rating=?, profile_image=? WHERE id=?");
        $stmt->execute([$customerName, $reviewText, $rating, $profileImage, $id]);
        jsonResponse(['success' => true, 'message' => 'Testimonial updated']);
    }

    if ($method === 'POST') {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $data = json_decode(file_get_contents('php://input'), true);
        } else {
            $data = $_POST;
        }

        $customerName = sanitizeInput($data['customer_name'] ?? '');
        $reviewText = sanitizeInput($data['review_text'] ?? '');
        $rating = (int) ($data['rating'] ?? 5);
        $profileImage = sanitizeInput($data['profile_image'] ?? '');

        if (empty($customerName) || empty($reviewText)) {
            jsonResponse(['success' => false, 'message' => 'Customer name and review text are required'], 400);
        }

        $stmt = $pdo->prepare("INSERT INTO testimonials (customer_name, review_text, rating, profile_image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$customerName, $reviewText, $rating, $profileImage]);
        jsonResponse(['success' => true, 'message' => 'Testimonial created', 'id' => $pdo->lastInsertId()], 201);
    }
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Operation failed'], 500);
}

<?php
/**
 * API: Admin - Manage Vehicles (CRUD)
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
        $stmt = $pdo->query("SELECT * FROM vehicles ORDER BY id ASC");
        jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
    }

    if ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int) ($data['id'] ?? 0);

        // Check if vehicle has active bookings
        $checkStmt = $pdo->prepare("SELECT COUNT(*) as count FROM bookings WHERE vehicle_id = ? AND booking_status != 'cancelled'");
        $checkStmt->execute([$id]);
        $result = $checkStmt->fetch();

        if ($result['count'] > 0) {
            jsonResponse(['success' => false, 'message' => 'Cannot delete vehicle with active bookings'], 400);
        }

        $stmt = $pdo->prepare("DELETE FROM vehicles WHERE id = ?");
        $stmt->execute([$id]);
        jsonResponse(['success' => true, 'message' => 'Vehicle deleted']);
    }

    if ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int) ($data['id'] ?? 0);
        $vehicleName = sanitizeInput($data['vehicle_name'] ?? '');
        $vehicleType = sanitizeInput($data['vehicle_type'] ?? '');
        $seatCapacity = (int) ($data['seat_capacity'] ?? 4);
        $imageUrl = sanitizeInput($data['image_url'] ?? '');
        $status = sanitizeInput($data['status'] ?? 'available');

        $allowedStatuses = ['available', 'maintenance'];
        if (!in_array($status, $allowedStatuses)) {
            jsonResponse(['success' => false, 'message' => 'Invalid status'], 400);
        }

        $stmt = $pdo->prepare("UPDATE vehicles SET vehicle_name=?, vehicle_type=?, seat_capacity=?, vehicle_image=?, status=? WHERE id=?");
        $stmt->execute([$vehicleName, $vehicleType, $seatCapacity, $imageUrl, $status, $id]);
        jsonResponse(['success' => true, 'message' => 'Vehicle updated']);
    }

    if ($method === 'POST') {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $data = json_decode(file_get_contents('php://input'), true);
        } else {
            $data = $_POST;
        }

        $vehicleName = sanitizeInput($data['vehicle_name'] ?? '');
        $vehicleType = sanitizeInput($data['vehicle_type'] ?? '');
        $seatCapacity = (int) ($data['seat_capacity'] ?? 4);
        $imageUrl = sanitizeInput($data['image_url'] ?? '');
        $status = sanitizeInput($data['status'] ?? 'available');

        if (empty($vehicleName) || empty($vehicleType)) {
            jsonResponse(['success' => false, 'message' => 'Vehicle name and type are required'], 400);
        }

        $stmt = $pdo->prepare("INSERT INTO vehicles (vehicle_name, vehicle_type, seat_capacity, vehicle_image, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$vehicleName, $vehicleType, $seatCapacity, $imageUrl, $status]);
        jsonResponse(['success' => true, 'message' => 'Vehicle created', 'id' => $pdo->lastInsertId()], 201);
    }
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Operation failed'], 500);
}

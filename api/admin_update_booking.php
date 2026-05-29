<?php
/**
 * API: Admin - Update booking status
 * POST /api/admin_update_booking.php
 * 
 * JSON body:
 * {
 *   "booking_id": 1,
 *   "status": "confirmed"   // confirmed | cancelled
 * }
 */
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');
requireAdminAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    jsonResponse(['success' => false, 'message' => 'Invalid JSON input'], 400);
}

$error = validateRequired(['booking_id', 'status'], $input);
if ($error) {
    jsonResponse(['success' => false, 'message' => $error], 400);
}

$bookingId = (int) $input['booking_id'];
$status = sanitizeInput($input['status']);
$allowedStatuses = ['pending', 'confirmed', 'cancelled'];

if (!in_array($status, $allowedStatuses)) {
    jsonResponse(['success' => false, 'message' => 'Invalid status. Allowed: pending, confirmed, cancelled'], 400);
}

try {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("UPDATE bookings SET booking_status = ? WHERE id = ?");
    $stmt->execute([$status, $bookingId]);

    if ($stmt->rowCount() === 0) {
        jsonResponse(['success' => false, 'message' => 'Booking not found'], 404);
    }

    jsonResponse(['success' => true, 'message' => "Booking status updated to '{$status}'"]);
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Failed to update booking'], 500);
}

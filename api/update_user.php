<?php
/**
 * API: Update user profile
 * POST /api/update_user.php
 * 
 * JSON body:
 * {
 *   "firebase_uid": "...",
 *   "name": "Updated Name",
 *   "phone": "0771234567",
 *   "country": "Sri Lanka"
 * }
 */
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    jsonResponse(['success' => false, 'message' => 'Invalid JSON input'], 400);
}

$error = validateRequired(['firebase_uid'], $input);
if ($error) {
    jsonResponse(['success' => false, 'message' => $error], 400);
}

$firebaseUid = sanitizeInput($input['firebase_uid']);
$name = isset($input['name']) ? sanitizeInput($input['name']) : null;
$phone = isset($input['phone']) ? sanitizeInput($input['phone']) : null;
$country = isset($input['country']) ? sanitizeInput($input['country']) : null;

try {
    $pdo = getDbConnection();

    $fields = [];
    $params = [];

    if ($name !== null) { $fields[] = "name = ?"; $params[] = $name; }
    if ($phone !== null) { $fields[] = "phone = ?"; $params[] = $phone; }
    if ($country !== null) { $fields[] = "country = ?"; $params[] = $country; }

    if (empty($fields)) {
        jsonResponse(['success' => false, 'message' => 'No fields to update'], 400);
    }

    $params[] = $firebaseUid;
    $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE firebase_uid = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    if ($stmt->rowCount() === 0) {
        jsonResponse(['success' => false, 'message' => 'User not found or no changes made'], 404);
    }

    jsonResponse(['success' => true, 'message' => 'Profile updated successfully']);
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Failed to update profile'], 500);
}

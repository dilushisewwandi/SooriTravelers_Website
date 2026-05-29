<?php
/**
 * API: Register/sync Firebase user to MySQL
 * POST /api/register_user.php
 * 
 * JSON body:
 * {
 *   "firebase_uid": "...",
 *   "name": "John Doe",
 *   "email": "john@example.com",
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

$error = validateRequired(['firebase_uid', 'name', 'email'], $input);
if ($error) {
    jsonResponse(['success' => false, 'message' => $error], 400);
}

$firebaseUid = sanitizeInput($input['firebase_uid']);
$name = sanitizeInput($input['name']);
$email = sanitizeInput($input['email']);
$phone = isset($input['phone']) ? sanitizeInput($input['phone']) : null;
$country = isset($input['country']) ? sanitizeInput($input['country']) : null;

if (!validateEmail($email)) {
    jsonResponse(['success' => false, 'message' => 'Invalid email address'], 400);
}

try {
    $pdo = getDbConnection();

    // Check if user already exists
    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE firebase_uid = ? OR email = ?");
    $checkStmt->execute([$firebaseUid, $email]);

    if ($checkStmt->fetch()) {
        // User already exists, update info
        $updateStmt = $pdo->prepare("
            UPDATE users SET name = ?, phone = ?, country = ? WHERE firebase_uid = ?
        ");
        $updateStmt->execute([$name, $phone, $country, $firebaseUid]);
        jsonResponse(['success' => true, 'message' => 'User profile updated']);
    } else {
        // Create new user
        $insertStmt = $pdo->prepare("
            INSERT INTO users (firebase_uid, name, email, phone, country)
            VALUES (?, ?, ?, ?, ?)
        ");
        $insertStmt->execute([$firebaseUid, $name, $email, $phone, $country]);
        jsonResponse(['success' => true, 'message' => 'User registered successfully'], 201);
    }
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Registration failed'], 500);
}

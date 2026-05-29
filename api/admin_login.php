<?php
/**
 * API: Admin Login
 * POST /api/admin_login.php
 * 
 * JSON body:
 * {
 *   "email": "admin@sooritravels.com",
 *   "password": "password"
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

$error = validateRequired(['email', 'password'], $input);
if ($error) {
    jsonResponse(['success' => false, 'message' => $error], 400);
}

$email = sanitizeInput($input['email']);
$password = $input['password'];

if (!validateEmail($email)) {
    jsonResponse(['success' => false, 'message' => 'Invalid email format'], 400);
}

try {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("SELECT id, name, email, password_hash FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        jsonResponse(['success' => false, 'message' => 'Invalid email or password'], 401);
    }

    initSession();
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];
    $_SESSION['admin_email'] = $admin['email'];

    jsonResponse([
        'success' => true,
        'message' => 'Login successful',
        'data' => [
            'name' => $admin['name'],
            'email' => $admin['email']
        ]
    ]);
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Login failed'], 500);
}

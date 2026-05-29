<?php
/**
 * API: Get user profile
 * GET /api/get_user.php?firebase_uid=xxx
 */
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_GET['firebase_uid'])) {
    jsonResponse(['success' => false, 'message' => 'firebase_uid is required'], 400);
}

$firebaseUid = sanitizeInput($_GET['firebase_uid']);

try {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("SELECT id, firebase_uid, name, email, phone, country, created_at FROM users WHERE firebase_uid = ?");
    $stmt->execute([$firebaseUid]);
    $user = $stmt->fetch();

    if (!$user) {
        jsonResponse(['success' => false, 'message' => 'User not found'], 404);
    }

    // Get user's bookings
    $bookingsStmt = $pdo->prepare("
        SELECT b.id, b.travel_date, b.number_of_people, b.booking_status, b.created_at,
               p.title as package_title, p.price as package_price,
               v.vehicle_name, v.vehicle_type
        FROM bookings b
        JOIN packages p ON b.package_id = p.id
        JOIN vehicles v ON b.vehicle_id = v.id
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC
    ");
    $bookingsStmt->execute([$user['id']]);
    $user['bookings'] = $bookingsStmt->fetchAll();

    jsonResponse(['success' => true, 'data' => $user]);
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Failed to fetch user profile'], 500);
}

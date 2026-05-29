<?php
/**
 * API: Admin - Get all bookings
 * GET /api/admin_get_bookings.php
 * Optional filters: ?date=2026-04-01&package_id=1&vehicle_id=1&status=pending&search=john
 */
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');
requireAdminAuth();

try {
    $pdo = getDbConnection();

    $where = [];
    $params = [];

    if (!empty($_GET['date'])) {
        $where[] = "b.travel_date = ?";
        $params[] = sanitizeInput($_GET['date']);
    }
    if (!empty($_GET['package_id']) && validateInt($_GET['package_id'])) {
        $where[] = "b.package_id = ?";
        $params[] = (int)$_GET['package_id'];
    }
    if (!empty($_GET['vehicle_id']) && validateInt($_GET['vehicle_id'])) {
        $where[] = "b.vehicle_id = ?";
        $params[] = (int)$_GET['vehicle_id'];
    }
    if (!empty($_GET['status'])) {
        $where[] = "b.booking_status = ?";
        $params[] = sanitizeInput($_GET['status']);
    }
    if (!empty($_GET['search'])) {
        $where[] = "(u.name LIKE ? OR u.email LIKE ?)";
        $searchTerm = '%' . sanitizeInput($_GET['search']) . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    $sql = "
        SELECT b.id, b.travel_date, b.number_of_people, b.booking_status, b.created_at,
               u.name as customer_name, u.email as customer_email, u.phone as customer_phone,
               p.title as package_title, p.price as package_price, p.duration_days,
               v.vehicle_name, v.vehicle_type, v.seat_capacity
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN packages p ON b.package_id = p.id
        JOIN vehicles v ON b.vehicle_id = v.id
    ";

    if (!empty($where)) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }
    $sql .= " ORDER BY b.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $bookings = $stmt->fetchAll();

    jsonResponse(['success' => true, 'data' => $bookings]);
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Failed to fetch bookings'], 500);
}

<?php
/**
 * API: Get vehicles or check availability
 * GET /api/get_vehicles.php                    - All available vehicles
 * GET /api/get_vehicles.php?date=2026-04-01    - Vehicles available on that date
 */
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = getDbConnection();

    if (!empty($_GET['date'])) {
        $date = sanitizeInput($_GET['date']);
        if (!validateDate($date)) {
            jsonResponse(['success' => false, 'message' => 'Invalid date format. Use YYYY-MM-DD'], 400);
        }

        // Get vehicles that are NOT booked on the given date
        $stmt = $pdo->prepare("
            SELECT v.* FROM vehicles v
            WHERE v.status = 'available'
            AND v.id NOT IN (
                SELECT b.vehicle_id FROM bookings b
                WHERE b.travel_date = ?
                AND b.booking_status != 'cancelled'
            )
            ORDER BY v.id ASC
        ");
        $stmt->execute([$date]);
    } else {
        $stmt = $pdo->query("SELECT * FROM vehicles WHERE status = 'available' ORDER BY id ASC");
    }

    $vehicles = $stmt->fetchAll();
    jsonResponse(['success' => true, 'data' => $vehicles]);
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Failed to fetch vehicles'], 500);
}

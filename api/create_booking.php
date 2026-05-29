<?php
/**
 * API: Create a new booking
 * POST /api/create_booking.php
 * 
 * Required JSON body:
 * {
 *   "firebase_uid": "...",
 *   "package_id": 1,
 *   "vehicle_id": 1,
 *   "travel_date": "2026-04-01",
 *   "number_of_people": 4
 * }
 */
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

// Parse JSON input
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    jsonResponse(['success' => false, 'message' => 'Invalid JSON input'], 400);
}

// Validate required fields
$error = validateRequired(['firebase_uid', 'package_id', 'vehicle_id', 'travel_date', 'number_of_people'], $input);
if ($error) {
    jsonResponse(['success' => false, 'message' => $error], 400);
}

$firebaseUid = sanitizeInput($input['firebase_uid']);
$packageId = (int) $input['package_id'];
$vehicleId = (int) $input['vehicle_id'];
$travelDate = sanitizeInput($input['travel_date']);
$numberOfPeople = (int) $input['number_of_people'];

// Validate types
if (!validateInt($packageId) || !validateInt($vehicleId) || !validateInt($numberOfPeople)) {
    jsonResponse(['success' => false, 'message' => 'Invalid numeric values'], 400);
}

if (!validateDate($travelDate)) {
    jsonResponse(['success' => false, 'message' => 'Invalid date format. Use YYYY-MM-DD'], 400);
}

if ($numberOfPeople < 1) {
    jsonResponse(['success' => false, 'message' => 'Number of people must be at least 1'], 400);
}

// Ensure travel date is in the future
if (strtotime($travelDate) <= strtotime('today')) {
    jsonResponse(['success' => false, 'message' => 'Travel date must be in the future'], 400);
}

try {
    $pdo = getDbConnection();

    // 1. Validate user exists
    $userStmt = $pdo->prepare("SELECT id FROM users WHERE firebase_uid = ?");
    $userStmt->execute([$firebaseUid]);
    $user = $userStmt->fetch();

    if (!$user) {
        jsonResponse(['success' => false, 'message' => 'User not found. Please register first.'], 404);
    }
    $userId = $user['id'];

    // 2. Validate package exists
    $pkgStmt = $pdo->prepare("SELECT id, title FROM packages WHERE id = ?");
    $pkgStmt->execute([$packageId]);
    $package = $pkgStmt->fetch();

    if (!$package) {
        jsonResponse(['success' => false, 'message' => 'Package not found'], 404);
    }

    // 3. Validate vehicle exists and is available (not under maintenance)
    $vehStmt = $pdo->prepare("SELECT id, vehicle_name, seat_capacity, status FROM vehicles WHERE id = ?");
    $vehStmt->execute([$vehicleId]);
    $vehicle = $vehStmt->fetch();

    if (!$vehicle) {
        jsonResponse(['success' => false, 'message' => 'Vehicle not found'], 404);
    }

    if ($vehicle['status'] !== 'available') {
        jsonResponse(['success' => false, 'message' => 'Vehicle is currently under maintenance'], 400);
    }

    // 4. Check seat capacity
    if ($numberOfPeople > $vehicle['seat_capacity']) {
        jsonResponse([
            'success' => false,
            'message' => "Vehicle '{$vehicle['vehicle_name']}' only seats {$vehicle['seat_capacity']} people"
        ], 400);
    }

    // 5. Check vehicle availability for that date
    $availStmt = $pdo->prepare("
        SELECT COUNT(*) as booked FROM bookings
        WHERE vehicle_id = ?
        AND travel_date = ?
        AND booking_status != 'cancelled'
    ");
    $availStmt->execute([$vehicleId, $travelDate]);
    $result = $availStmt->fetch();

    if ($result['booked'] > 0) {
        jsonResponse([
            'success' => false,
            'message' => "Vehicle '{$vehicle['vehicle_name']}' is already booked on {$travelDate}. Please choose another vehicle or date."
        ], 409);
    }

    // 6. Create booking
    $bookStmt = $pdo->prepare("
        INSERT INTO bookings (user_id, package_id, vehicle_id, travel_date, number_of_people, booking_status)
        VALUES (?, ?, ?, ?, ?, 'pending')
    ");
    $bookStmt->execute([$userId, $packageId, $vehicleId, $travelDate, $numberOfPeople]);

    $bookingId = $pdo->lastInsertId();

    jsonResponse([
        'success' => true,
        'message' => 'Booking created successfully! Status: pending',
        'data' => [
            'booking_id' => $bookingId,
            'package' => $package['title'],
            'vehicle' => $vehicle['vehicle_name'],
            'travel_date' => $travelDate,
            'people' => $numberOfPeople,
            'status' => 'pending'
        ]
    ], 201);

} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Failed to create booking'], 500);
}

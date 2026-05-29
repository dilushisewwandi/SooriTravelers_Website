<?php
/**
 * API: Check admin session
 * GET /api/admin_check_session.php
 */
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');

initSession();

if (!empty($_SESSION['admin_id'])) {
    jsonResponse([
        'success' => true,
        'data' => [
            'name' => $_SESSION['admin_name'],
            'email' => $_SESSION['admin_email']
        ]
    ]);
} else {
    jsonResponse(['success' => false, 'message' => 'Not authenticated'], 401);
}

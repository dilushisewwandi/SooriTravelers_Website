<?php
/**
 * API: Admin Logout
 * POST /api/admin_logout.php
 */
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');

initSession();
session_destroy();

jsonResponse(['success' => true, 'message' => 'Logged out successfully']);

<?php
/**
 * Security & Helper Functions - Soori Travels
 */

// Start session with secure settings
function initSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_httponly' => true,
            'cookie_samesite' => 'Strict',
            'use_strict_mode' => true,
        ]);
    }
}

// Generate CSRF token
function generateCsrfToken(): string {
    initSession();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Validate CSRF token
function validateCsrfToken(?string $token): bool {
    initSession();
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// Sanitize input for XSS prevention
function sanitizeInput(string $input): string {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Send JSON response
function jsonResponse(array $data, int $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Validate required fields
function validateRequired(array $fields, array $data): ?string {
    foreach ($fields as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            return "Missing required field: {$field}";
        }
    }
    return null;
}

// Validate email format
function validateEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Validate integer
function validateInt($value): bool {
    return filter_var($value, FILTER_VALIDATE_INT) !== false;
}

// Validate date format (YYYY-MM-DD)
function validateDate(string $date): bool {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

// Check admin session
function requireAdminAuth(): void {
    initSession();
    if (empty($_SESSION['admin_id'])) {
        jsonResponse(['success' => false, 'message' => 'Admin authentication required'], 401);
    }
}

// Get uploaded file path
function handleFileUpload(string $inputName, string $uploadDir): ?string {
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $file = $_FILES[$inputName];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'video/mp4'];
    $maxSize = 10 * 1024 * 1024; // 10MB

    // Validate file type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    if (!in_array($mimeType, $allowedTypes)) {
        return null;
    }

    // Validate file size
    if ($file['size'] > $maxSize) {
        return null;
    }

    // Create upload directory if not exists
    $fullUploadDir = __DIR__ . '/../uploads/' . $uploadDir;
    if (!is_dir($fullUploadDir)) {
        mkdir($fullUploadDir, 0755, true);
    }

    // Generate safe filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $safeName = bin2hex(random_bytes(16)) . '.' . strtolower($extension);
    $destination = $fullUploadDir . '/' . $safeName;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return 'uploads/' . $uploadDir . '/' . $safeName;
    }

    return null;
}

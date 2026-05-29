<?php
/**
 * API: Admin - Media Manager
 * GET    /api/admin_media.php - List all media
 * POST   /api/admin_media.php - Upload media (multipart/form-data)
 * DELETE /api/admin_media.php - Delete media (JSON body with id)
 */
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json; charset=utf-8');
requireAdminAuth();

$pdo = getDbConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $where = "";
        $params = [];

        if (!empty($_GET['type'])) {
            $where = " WHERE file_type = ?";
            $params[] = sanitizeInput($_GET['type']);
        }

        $stmt = $pdo->prepare("SELECT * FROM media_files" . $where . " ORDER BY created_at DESC");
        $stmt->execute($params);
        jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
    }

    if ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int) ($data['id'] ?? 0);

        // Get file path to delete from disk
        $stmt = $pdo->prepare("SELECT file_path FROM media_files WHERE id = ?");
        $stmt->execute([$id]);
        $media = $stmt->fetch();

        if ($media) {
            $diskPath = __DIR__ . '/../' . $media['file_path'];
            if (file_exists($diskPath)) {
                unlink($diskPath);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM media_files WHERE id = ?");
        $stmt->execute([$id]);
        jsonResponse(['success' => true, 'message' => 'Media deleted']);
    }

    if ($method === 'POST') {
        // Upload
        $filePath = handleFileUpload('file', 'media');

        if (!$filePath) {
            jsonResponse(['success' => false, 'message' => 'File upload failed. Check file type and size.'], 400);
        }

        $fileName = sanitizeInput($_FILES['file']['name']);
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($_FILES['file']['tmp_name']);

        initSession();
        $uploadedBy = $_SESSION['admin_name'] ?? 'admin';

        $stmt = $pdo->prepare("INSERT INTO media_files (original_name, file_path, file_type, uploaded_by) VALUES (?, ?, ?, ?)");
        $stmt->execute([$fileName, $filePath, $mimeType, $uploadedBy]);

        jsonResponse([
            'success' => true,
            'message' => 'Media uploaded successfully',
            'data' => [
                'id' => $pdo->lastInsertId(),
                'file_path' => $filePath,
                'original_name' => $fileName
            ]
        ], 201);
    }
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Operation failed'], 500);
}

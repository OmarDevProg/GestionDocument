<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit;
}

$userId = $_SESSION['user_id'];

$data = json_decode(file_get_contents('php://input'), true);
$folderId = $data['folder_id'] ?? null;
$newPassword = $data['password'] ?? null;
$removePassword = !empty($data['remove_password']); // boolean

if (!$folderId) {
    echo json_encode(['success' => false, 'message' => 'Folder ID missing']);
    exit;
}

// Check folder ownership
$stmt = $pdo->prepare("SELECT id FROM files WHERE id = ? AND user_id = ? AND filetype = 'folder'");
$stmt->execute([$folderId, $userId]);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Folder not found or access denied']);
    exit;
}

if ($removePassword) {
    // Remove password (set NULL)
    $stmt = $pdo->prepare("UPDATE files SET password = NULL WHERE id = ?");
    $stmt->execute([$folderId]);
    echo json_encode(['success' => true, 'message' => 'Password removed']);
    exit;
}

if ($newPassword !== null) {
    if (trim($newPassword) === '') {
        // If password empty string and not removePassword, treat as no update
        echo json_encode(['success' => false, 'message' => 'Password is empty']);
        exit;
    }
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE files SET password = ? WHERE id = ?");
    $stmt->execute([$passwordHash, $folderId]);
    echo json_encode(['success' => true, 'message' => 'Password updated']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Nothing to update']);

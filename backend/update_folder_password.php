<?php
require_once 'connect.php';

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'] ?? null;
$currentPassword = $data['current_password'] ?? '';
$newPassword = $data['new_password'] ?? '';

if (!$id || !$currentPassword || !$newPassword) {
    echo json_encode(['success' => false, 'message' => 'Champs manquants']);
    exit;
}

// Get current hashed password
$stmt = $pdo->prepare("SELECT password FROM files WHERE id = ? AND filetype = 'folder'");
$stmt->execute([$id]);
$folder = $stmt->fetch();

if (!$folder) {
    echo json_encode(['success' => false, 'message' => 'Dossier introuvable']);
    exit;
}

// Verify current password
if (!empty($folder['password']) && !password_verify($currentPassword, $folder['password'])) {
    echo json_encode(['success' => false, 'message' => 'Mot de passe actuel incorrect']);
    exit;
}

// Hash and update new password
$newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$update = $pdo->prepare("UPDATE files SET password = ? WHERE id = ?");
$update->execute([$newHashedPassword, $id]);

echo json_encode(['success' => true]);

<?php
session_start();
require 'connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non authentifié']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$folderId = $data['folder_id'] ?? null;
$password = $data['password'] ?? '';

$userId = $_SESSION['user_id'];

if (!$folderId) {
    echo json_encode(['success' => false, 'message' => 'ID du dossier requis']);
    exit;
}

$stmt = $pdo->prepare("SELECT password FROM files WHERE id = ? AND user_id = ?");
$stmt->execute([$folderId, $userId]);
$folder = $stmt->fetch();

if (!$folder) {
    echo json_encode(['success' => false, 'message' => 'Dossier introuvable']);
    exit;
}

if (empty($folder['password'])) {
    // No password set, allow access
    echo json_encode(['success' => true]);
    exit;
}

if (password_verify($password, $folder['password'])) {
    // Password correct
    // Save access in session
    if (!isset($_SESSION['folder_access'])) {
        $_SESSION['folder_access'] = [];
    }
    $_SESSION['folder_access'][$folderId] = true;

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect']);
}

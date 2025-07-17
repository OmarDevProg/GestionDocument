<?php
require_once 'connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;
$password = $data['password'] ?? '';

if (!$id || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    exit;
}

// Fetch folder
$stmt = $pdo->prepare("SELECT password FROM files WHERE id = ? AND filetype = 'folder'");
$stmt->execute([$id]);
$folder = $stmt->fetch();

if (!$folder) {
    echo json_encode(['success' => false, 'message' => 'Dossier introuvable']);
    exit;
}

// Verify current password
if (!password_verify($password, $folder['password'])) {
    echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect']);
    exit;
}

// Set password to NULL
$update = $pdo->prepare("UPDATE files SET password = NULL WHERE id = ?");
$update->execute([$id]);
require_once 'log_function.php'; // adjust the path if needed
session_start(); // make sure session is started if not already
$userId = $_SESSION['user_id'] ?? null;
if ($userId) {
    logAction($pdo, $userId, 'remove_password', $id, 'Suppression mot de passe sur dossier ID: ' . $id);
}

echo json_encode(['success' => true]);

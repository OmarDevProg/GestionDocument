<?php
session_start();
require 'connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Non authentifié']);
    exit;
}

$folderId = $_GET['folder_id'] ?? null;
$userId = $_SESSION['user_id'];

if (!$folderId) {
    http_response_code(400);
    echo json_encode(['error' => 'ID du dossier requis']);
    exit;
}

$stmt = $pdo->prepare("SELECT password FROM files WHERE id = ? AND user_id = ?");
$stmt->execute([$folderId, $userId]);
$folder = $stmt->fetch();

if (!$folder) {
    http_response_code(404);
    echo json_encode(['error' => 'Dossier introuvable']);
    exit;
}

echo json_encode(['password_protected' => !empty($folder['password'])]);

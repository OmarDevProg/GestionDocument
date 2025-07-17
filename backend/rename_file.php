<?php
session_start();
require '../connect.php';

header('Content-Type: application/json');

$userId = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$userId) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = (int)($data['id'] ?? 0);
$newName = trim($data['new_name'] ?? '');

if (!$id || !$newName) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    exit;
}

// Fetch file
$stmt = $pdo->prepare("SELECT * FROM files WHERE id = ? ");
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) {
    echo json_encode(['success' => false, 'message' => 'Fichier non trouvé']);
    exit;
}

// Prepare new path
$oldPath = '../' . $file['filepath'];
$pathParts = pathinfo($file['filepath']);
$newPath = $pathParts['dirname'] . '/' . $newName;
$fullNewPath = '../' . $newPath;

// Rename in filesystem
if ($file['filetype'] === 'folder') {
    if (!rename($oldPath, $fullNewPath)) {
        echo json_encode(['success' => false, 'message' => 'Échec du renommage du dossier']);
        exit;
    }
} else {
    $ext = pathinfo($file['filename'], PATHINFO_EXTENSION);
    $newPath .= $ext ? '.' . $ext : '';
    $fullNewPath .= $ext ? '.' . $ext : '';
    if (!rename($oldPath, $fullNewPath)) {
        echo json_encode(['success' => false, 'message' => 'Échec du renommage du fichier']);
        exit;
    }
}

// Update DB
$stmt = $pdo->prepare("UPDATE files SET filename = ?, filepath = ? WHERE id = ?");
$stmt->execute([$newName, $newPath, $id]);

// Log the action
require_once 'log_function.php';
logAction($pdo, $userId, 'rename_' . $file['filetype'], $id, 'Renommé en: ' . $newName);

echo json_encode(['success' => true]);

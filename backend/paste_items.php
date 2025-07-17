<?php
session_start();
require 'connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Non authentifié']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$itemId = $data['item_id'] ?? null;
$targetFolderId = $data['target_folder_id'] ?? null;
$type = $data['item_type'] ?? null;

if (!$itemId || !$targetFolderId || !$type) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    exit;
}

try {
    // Fetch original file/folder
    $stmt = $pdo->prepare("SELECT * FROM files WHERE id = ?");
    $stmt->execute([$itemId]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$file) {
        throw new Exception("Élément introuvable");
    }

    // Generate new name
    $originalName = $file['filename'];
    $extension = '';

    if ($file['filetype'] !== 'folder' && str_contains($originalName, '.')) {
        $pos = strrpos($originalName, '.');
        $extension = substr($originalName, $pos);
        $originalName = substr($originalName, 0, $pos);
    }

    $newName = $originalName . '_copie' . $extension;

    // Get target folder path
    $stmt = $pdo->prepare("SELECT filepath FROM files WHERE id = ?");
    $stmt->execute([$targetFolderId]);
    $targetFolder = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$targetFolder) {
        throw new Exception("Dossier cible introuvable");
    }

    $targetPath = rtrim($targetFolder['filepath'], '/') . '/' . $newName;

    // Create physical copy if it's a file
    if ($file['filetype'] !== 'folder') {
        $originalPath = '../' . $file['filepath'];
        $copyPath = '../' . $targetPath;

        if (!file_exists($originalPath)) {
            throw new Exception("Fichier source introuvable");
        }

        copy($originalPath, $copyPath);
    } else {
        // Create new folder if it's a folder
        $newFolderPath = '../' . $targetPath;
        if (!is_dir($newFolderPath)) {
            mkdir($newFolderPath, 0755, true);
        }
    }

    // Insert new row in DB
    $stmt = $pdo->prepare("INSERT INTO files (user_id, filename, filepath, filetype, filesize, uploaded_at, parent_id, password) 
        VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)");

    $stmt->execute([
        $file['user_id'],
        $newName,
        $targetPath,
        $file['filetype'],
        $file['filesize'],
        $targetFolderId,
        $file['password']
    ]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

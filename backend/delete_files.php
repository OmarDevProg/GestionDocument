<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['ids']) || !is_array($data['ids'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Paramètres invalides']);
    exit;
}

require_once 'connect.php';

$ids = $data['ids'];

// Préparer une requête SQL sécurisée pour récupérer les fichiers
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT id, filepath, filetype FROM files WHERE id IN ($placeholders)");
$stmt->execute($ids);
$files = $stmt->fetchAll();

if (!$files) {
    echo json_encode(['success' => false, 'message' => 'Aucun fichier trouvé']);
    exit;
}

// Supprimer fichiers/dossiers du disque et base de données
foreach ($files as $file) {
    $filepath = __DIR__ . '/../' . $file['filepath'];

    // Supprimer fichier ou dossier (fonction à créer selon ton besoin)
    if ($file['filetype'] === 'folder') {
        // Supprimer dossier récursivement
        deleteFolderRecursive($filepath);
    } else {
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
}

// Supprimer de la base
$deleteStmt = $pdo->prepare("DELETE FROM files WHERE id IN ($placeholders)");
$deleteStmt->execute($ids);

echo json_encode(['success' => true]);

// Fonction récursive pour supprimer un dossier et tout son contenu
function deleteFolderRecursive($folder) {
    if (!is_dir($folder)) return;
    $files = array_diff(scandir($folder), ['.', '..']);
    foreach ($files as $file) {
        $path = "$folder/$file";
        if (is_dir($path)) {
            deleteFolderRecursive($path);
        } else {
            unlink($path);
        }
    }
    rmdir($folder);
}

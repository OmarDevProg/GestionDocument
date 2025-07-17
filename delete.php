<?php
session_start(); // Required to access $_SESSION
header('Content-Type: application/json');

require_once 'connect.php';
require_once 'log_function.php'; // Make sure this file contains the logAction() function

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['items']) || !is_array($data['items'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Paramètres invalides']);
    exit;
}

$items = $data['items'];
$idsToDelete = [];
$userId = $_SESSION['user_id'] ?? null;

foreach ($items as $item) {
    $id = $item['id'];
    $filename = $item['filename'];
    $filetype = $item['filetype'];
    $passwordInput = trim($item['password'] ?? '');

    // Récupérer l'entrée dans la BDD
    $stmt = $pdo->prepare("SELECT id, filepath, filetype, password, filename FROM files WHERE id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetch();

    if (!$file) {
        echo json_encode(['success' => false, 'message' => "Fichier introuvable (ID: $id)"]);
        exit;
    }

    // Vérification du mot de passe si dossier protégé
    if (!empty($file['password']) && $file['filetype'] === 'folder') {
        $storedPassword = $file['password'];

        if (preg_match('/^\$2[aby]\$/', $storedPassword)) {
            if (!password_verify($passwordInput, $storedPassword)) {
                echo json_encode(['success' => false, 'message' => "Mot de passe incorrect pour le dossier $filename"]);
                exit;
            }
        } else {
            if ($passwordInput !== $storedPassword) {
                echo json_encode(['success' => false, 'message' => "Mot de passe incorrect pour le dossier $filename"]);
                exit;
            }
        }
    }

    // Supprimer physiquement le fichier/dossier
    $filepath = __DIR__ . '/../' . $file['filepath'];
    if ($file['filetype'] === 'folder') {
        deleteFolderRecursive($filepath);
    } else {
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    // ✅ Log individual delete
    if ($userId) {
        logAction($pdo, $userId, 'delete_' . $file['filetype'], $file['id'], 'Suppression de: ' . $file['filename']);
    }

    // Ajouter à la liste pour suppression en base
    $idsToDelete[] = $id;
}

// Supprimer de la base
if (!empty($idsToDelete)) {
    $placeholders = implode(',', array_fill(0, count($idsToDelete), '?'));
    $deleteStmt = $pdo->prepare("DELETE FROM files WHERE id IN ($placeholders)");
    $deleteStmt->execute($idsToDelete);

    // ✅ Log bulk deletion summary
    if ($userId) {
        logAction($pdo, $userId, 'bulk_delete', null, count($idsToDelete) . ' fichiers supprimés.');
    }
}

echo json_encode(['success' => true]);

// Fonction récursive de suppression de dossier
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

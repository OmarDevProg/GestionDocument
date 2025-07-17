<?php
session_start();
header('Content-Type: application/json');

require_once 'connect.php';
require_once 'log_function.php';

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

    // 🔍 Récupérer fichier avec user_id
    $stmt = $pdo->prepare("SELECT * FROM files WHERE id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetch();

    if (!$file) {
        echo json_encode(['success' => false, 'message' => "Fichier introuvable (ID: $id)"]);
        exit;
    }

    if ($file['user_id'] != $userId) {
        echo json_encode(['success' => false, 'message' => "Vous n'avez pas la permission de supprimer $filename"]);
        exit;
    }

    // 🔐 Vérification du mot de passe si protégé
    if (!empty($file['password']) && $file['filetype'] === 'folder') {
        $storedPassword = $file['password'];
        if (preg_match('/^\$2[aby]\$/', $storedPassword)) {
            if (!password_verify($passwordInput, $storedPassword)) {
                echo json_encode(['success' => false, 'message' => "Mot de passe incorrect pour $filename"]);
                exit;
            }
        } else {
            if ($passwordInput !== $storedPassword) {
                echo json_encode(['success' => false, 'message' => "Mot de passe incorrect pour $filename"]);
                exit;
            }
        }
    }

    // 🗃️ Sauvegarder dans la corbeille
    $insert = $pdo->prepare("INSERT INTO files_corbeille 
        (user_id, filename, filepath, filetype, filesize, uploaded_at, parent_id, password, deleted_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    $insert->execute([
        $file['user_id'],
        $file['filename'],
        $file['filepath'],
        $file['filetype'],
        $file['filesize'],
        $file['uploaded_at'],
        $file['parent_id'],
        $file['password']
    ]);

    // 📝 Log
    if ($userId) {
        logAction($pdo, $userId, 'move_to_corbeille_' . $file['filetype'], $file['id'], 'Déplacé dans la corbeille: ' . $file['filename']);
    }

    // ✅ On ne supprime plus physiquement les fichiers ici, juste en base
    $idsToDelete[] = $id;
}

// ❌ Supprimer les entrées de `files`
if (!empty($idsToDelete)) {
    $placeholders = implode(',', array_fill(0, count($idsToDelete), '?'));
    $deleteStmt = $pdo->prepare("DELETE FROM files WHERE id IN ($placeholders) AND user_id = ?");
    $deleteStmt->execute([...$idsToDelete, $userId]);
}

echo json_encode(['success' => true]);
?>

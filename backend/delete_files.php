<?php
header('Content-Type: application/json');

// Lire et décoder les données JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['items']) || !is_array($data['items'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Paramètres invalides']);
    exit;
}

require_once 'connect.php';

$items = $data['items'];
$idsToDelete = [];

foreach ($items as $item) {
    $id = $item['id'];
    $filename= $item['filename'];

    $filetype = $item['filetype'];
    $passwordInput = trim($item['password'] ?? '');

    // Récupérer l'entrée dans la BDD
    $stmt = $pdo->prepare("SELECT id, filepath, filetype, password,filename FROM files WHERE id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetch();

    if (!$file) {
        echo json_encode(['success' => false, 'message' => "Fichier introuvable (ID: $id)"]);
        exit;
    }

    // Vérification du mot de passe si dossier protégé
    if (!empty($file['password']) && $file['filetype'] === 'folder') {
        $storedPassword = $file['password'];

        // Vérifier si le mot de passe est haché (commence par $2y$, etc.)
        if (preg_match('/^\$2[aby]\$/', $storedPassword)) {
            // Haché
            if (!password_verify($passwordInput, $storedPassword)) {
                echo json_encode(['success' => false, 'message' => "Mot de passe incorrect pour le dossier  $filename"]);
                exit;
            }
        } else {
            // Non haché (texte clair)
            if ($passwordInput !== $storedPassword) {
                echo json_encode(['success' => false, 'message' => "Mot de passe incorrect pour le dossier  $filename"]);
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

    // Ajouter à la liste à supprimer de la base
    $idsToDelete[] = $id;
}

// Supprimer de la base
if (!empty($idsToDelete)) {
    $placeholders = implode(',', array_fill(0, count($idsToDelete), '?'));
    $deleteStmt = $pdo->prepare("DELETE FROM files WHERE id IN ($placeholders)");
    $deleteStmt->execute($idsToDelete);
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

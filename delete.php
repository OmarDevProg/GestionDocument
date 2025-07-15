<?php
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($data['items']) && is_array($data['items'])) {
    $pdo->beginTransaction();

    try {
        foreach ($data['items'] as $item) {
            $fileId = intval($item['id']);
            $filename = basename($item['filename']);
            $filetype = $item['filetype'];
            $password = $item['password'] ?? null;

            if ($filetype !== 'folder') {
                // Delete file directly
                $path = UPLOAD_DIR . '/' . $filename;
                if (file_exists($path)) {
                    unlink($path);
                } else {
                    throw new Exception("Fichier introuvable: $filename");
                }
            } else {
                // Folder: check password
                $stmt = $pdo->prepare("SELECT password FROM folders WHERE id = ?");
                $stmt->execute([$fileId]);
                $folder = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$folder) {
                    throw new Exception("Dossier introuvable: $filename");
                }

                if (!empty($folder['password'])) {
                    if (empty($password) || !password_verify($password, $folder['password'])) {
                        throw new Exception("Mot de passe incorrect pour le dossier: $filename");
                    }
                }

                // Delete folder and contents (your existing logic)
                $folderPath = UPLOAD_DIR . '/' . $filename;

                function deleteFolder($folderPath) {
                    if (!is_dir($folderPath)) return false;
                    $files = array_diff(scandir($folderPath), ['.', '..']);
                    foreach ($files as $file) {
                        $fullPath = $folderPath . DIRECTORY_SEPARATOR . $file;
                        if (is_dir($fullPath)) {
                            deleteFolder($fullPath);
                        } else {
                            unlink($fullPath);
                        }
                    }
                    return rmdir($folderPath);
                }

                if (!deleteFolder($folderPath)) {
                    throw new Exception("Erreur lors de la suppression du dossier: $filename");
                }

                $delStmt = $pdo->prepare("DELETE FROM folders WHERE id = ?");
                $delStmt->execute([$fileId]);
            }
        }

        $pdo->commit();
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Requête invalide']);
}
?>

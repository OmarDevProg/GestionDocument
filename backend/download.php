<?php
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit('ID manquant.');
}

$id = $_GET['id'];

require_once 'connect.php';

$stmt = $pdo->prepare('SELECT filename, filepath FROM files WHERE id = ?');
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) {
    http_response_code(404);
    exit('Fichier introuvable.');
}

$filepath = __DIR__ . '/../' . $file['filepath'];

if (!file_exists($filepath)) {
    http_response_code(404);
    exit('Fichier introuvable sur le serveur.');
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file['filename']) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));

readfile($filepath);
exit;

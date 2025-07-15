<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filename'])) {
    $file = basename($_POST['filename']);
    $path = UPLOAD_DIR . '/' . $file;

    if (file_exists($path)) {
        unlink($path);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'File not found']);
    }
}

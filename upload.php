<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $filename = basename($file['name']);
    $target = UPLOAD_DIR . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        echo json_encode(['success' => true, 'filename' => $filename]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Upload failed']);
    }
}

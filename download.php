<?php
require 'config.php';

if (isset($_GET['filename'])) {
    $file = basename($_GET['filename']);
    $path = UPLOAD_DIR . '/' . $file;

    if (file_exists($path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    } else {
        echo "File not found.";
    }
}

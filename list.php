<?php
require 'config.php';

$files = array_diff(scandir(UPLOAD_DIR), ['.', '..']);

$result = [];

foreach ($files as $file) {
    $path = UPLOAD_DIR . '/' . $file;
    $size = filesize($path);
    $modified = date("Y-m-d H:i:s", filemtime($path));
    $type = mime_content_type($path);

    $result[] = [
        'name' => $file,
        'size' => $size,
        'modified' => $modified,
        'type' => $type,
        'url' => BASE_URL . '/' . $file
    ];
}

header('Content-Type: application/json');
echo json_encode($result);

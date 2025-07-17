<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filename'])) {
    $file = basename($_POST['filename']);
    $url = BASE_URL . '/' . $file;

    echo json_encode(['success' => true, 'url' => $url]);
}

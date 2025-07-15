<?php
require 'db.php';

$stmt = $pdo->query("SELECT id, name, size, type, uploaded_at FROM files ORDER BY uploaded_at DESC");
$files = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($files);

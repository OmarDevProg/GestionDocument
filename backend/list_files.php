<?php
$pdo = new PDO('mysql:host=localhost;dbname=gestion_documents;charset=utf8', 'root', '');
$parentId = isset($_GET['parent_id']) ? (int)$_GET['parent_id'] : null;

$stmt = $pdo->prepare("SELECT * FROM files WHERE parent_id " . ($parentId === null ? "IS NULL" : "= ?"));
$stmt->execute($parentId === null ? [] : [$parentId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($items);

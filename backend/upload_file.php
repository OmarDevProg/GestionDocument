<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploaded_file'])) {
    $userId = $_SESSION['user_id'];
    $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

    // Get parent path if any
    $parentPath = '';
    if ($parentId) {
        $stmt = $pdo->prepare("SELECT filepath FROM files WHERE id = ? AND user_id = ?");
        $stmt->execute([$parentId, $userId]);
        $parentFile = $stmt->fetch();
        if ($parentFile) {
            $parentPath = rtrim($parentFile['filepath'], '/') . '/';
        } else {
            $parentPath = '';
            $parentId = null;
        }
    }

    $file = $_FILES['uploaded_file'];
    $filename = basename($file['name']);
    $uploadDir = '../uploads/' . $userId . '/' . $parentPath;
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $filepath = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $filetype = mime_content_type($filepath);
        $filesize = filesize($filepath);

        // Store relative path from project root, e.g. "uploads/1/folder/filename.ext"
        $relativeFilePath = 'uploads/' . $userId . '/' . $parentPath . $filename;

        $stmt = $pdo->prepare("INSERT INTO files (user_id, filename, filepath, filetype, filesize, parent_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $filename, $relativeFilePath, $filetype, $filesize, $parentId]);
    }

    header("Location: ../index.php" . ($parentId ? "?folder_id=$parentId" : ''));
    exit;
}

header("Location: ../index.php");
exit;

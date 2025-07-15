<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['folder_name'])) {
    $folderName = trim($_POST['folder_name']);
    $userId = $_SESSION['user_id'];
    $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    $passwordRaw = $_POST['password'] ?? '';

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

    // Compose new folder path
    $folderPath = 'uploads/' . $userId . '/' . $parentPath . $folderName;

    // Create directory recursively
    if (!is_dir('../' . $folderPath)) {
        mkdir('../' . $folderPath, 0755, true);
    }

    // Hash password if provided
    $passwordHash = null;
    if (!empty($passwordRaw)) {
        $passwordHash = password_hash($passwordRaw, PASSWORD_DEFAULT);
    }

    // Insert folder in DB with password hash
    $stmt = $pdo->prepare("INSERT INTO files (user_id, filename, filepath, filetype, filesize, parent_id, password) VALUES (?, ?, ?, 'folder', 0, ?, ?)");
    $stmt->execute([$userId, $folderName, $folderPath, $parentId, $passwordHash]);

    header("Location: ../index.php" . ($parentId ? "?folder_id=$parentId" : ''));
    exit;
}
header("Location: ../index.php");
exit;

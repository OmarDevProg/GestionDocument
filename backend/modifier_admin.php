<?php
require_once '../connect.php'; // inclure sans l’assigner à $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE users SET firstname=?, lastname=?, email=?, role=? WHERE id=?");
    $stmt->execute([
        $_POST['firstname'],
        $_POST['lastname'],
        $_POST['email'],
        $_POST['role'],
        $_POST['id']
    ]);

    header('Location: ../adminList.php?updated=1');
    exit();
}

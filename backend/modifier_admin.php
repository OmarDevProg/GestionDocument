<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = new PDO('mysql:host=localhost;dbname=gestion_documents;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("UPDATE users SET firstname=?, lastname=?, email=?, role=? WHERE id=?");
    $stmt->execute([
        $_POST['firstname'],
        $_POST['lastname'],
        $_POST['email'],
        $_POST['role'],
        $_POST['id']
    ]);

    header('Location: ../adminList.php'); // Adjust path as needed
    exit();
}

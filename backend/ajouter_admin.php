<?php
$pdo = new PDO('mysql:host=localhost;dbname=gestion_documents;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
        $_POST['firstname'],
        $_POST['lastname'],
        $_POST['email'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        $_POST['role']
    ]);
    header("Location: ../adminList.php");
    exit();
}

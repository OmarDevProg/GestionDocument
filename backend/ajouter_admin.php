<?php
require_once '../connect.php'; // ou le bon chemin relatif : ../includes/db.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $_POST['firstname'],
            $_POST['lastname'],
            $_POST['email'],
            password_hash($_POST['password'], PASSWORD_DEFAULT),
            $_POST['role']
        ]);
        header("Location: ../adminList.php?success=1");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            // Email en double
            header("Location: ../adminList.php?error=email");
        } else {
            // Autre erreur SQL
            header("Location: ../adminList.php?error=unknown");
        }
        exit();
    }
}

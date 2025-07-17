<?php
session_start();
$pdo = require_once 'connect.php'; // ajuste le chemin si nécessaire

$error = ''; // ➤ variable pour stocker un message d’erreur

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];

        $_SESSION['username'] = $user['firstname']." ".$user['lastname'];

        header("Location: ../index.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Email ou mot de passe incorrect.";
        header("Location: ../login.php"); // Redirige vers la page de login
        exit;
    }
}

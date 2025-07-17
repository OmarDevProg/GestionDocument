<?php
session_start();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Gestion de Fichiers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 50%, #7209b7 100%);
        }
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
        }
        .input-focus:focus {
            border-color: #7209b7;
            box-shadow: 0 0 0 2px rgba(114, 9, 183, 0.2);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-xl card-shadow w-full max-w-md overflow-hidden">
    <!-- Header avec logo -->
    <div class="bg-indigo-600 py-6 px-8 text-center">
        <div class="flex items-center justify-center space-x-3">
            <i class="fas fa-folder-open text-white text-3xl"></i>
            <h1 class="text-2xl font-bold text-white">FileManager Pro</h1>
        </div>
        <p class="text-indigo-200 mt-2">Gestion sécurisée de vos fichiers</p>
    </div>

    <!-- Formulaire de connexion -->
    <div class="px-8 py-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Connexion</h2>

        <form id="loginForm" action="backend/verif_login.php" method="POST" class="space-y-4">
            <?php
            if (isset($_SESSION['login_error'])): ?>
                <div class="mt-4 text-red-600 text-sm text-center">
                    <?php
                    echo $_SESSION['login_error'];
                    unset($_SESSION['login_error']); // Supprime l'erreur après affichage
                    ?>
                </div>
            <?php endif; ?>


            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        required
                        placeholder="votre@email.com"
                        class="pl-10 input-focus w-full rounded-lg border-gray-300 border py-2 px-4 focus:outline-none transition duration-200"
                    >
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        placeholder="••••••••"
                        class="pl-10 input-focus w-full rounded-lg border-gray-300 border py-2 px-4 focus:outline-none transition duration-200"
                    >

                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-eye text-gray-400 hover:text-indigo-600 cursor-pointer"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="rememberMe"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    >
                    <label for="rememberMe" class="ml-2 block text-sm text-gray-700">Se souvenir de moi</label>

                </div>
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800">Mot de passe oublié?</a>
            </div>

            <button
                type="submit"
                class="w-full gradient-bg text-white py-2 px-4 rounded-lg hover:opacity-90 transition duration-200 font-medium flex items-center justify-center"
            >
                <span id="loginText">Se connecter</span>
                <i id="loginSpinner" class="fas fa-spinner fa-spin ml-2 hidden"></i>
            </button>


        </form>


    </div>
</div>

<script>
</script>
</body>
</html>
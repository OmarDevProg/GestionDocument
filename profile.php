<?php
session_start();

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupération des infos de session
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAS | Gestionnaire de fichiers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Barre de défilement personnalisée */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
<div class="flex h-screen overflow-hidden">
    <!-- Barre latérale -->
    <div class="hidden md:flex md:flex-shrink-0">
        <div class="flex flex-col w-64 bg-gray-900 text-white">
            <div class="flex items-center justify-center h-14 px-4 bg-gray-800">
                <div class="flex items-center">
                    <i class="fas fa-folder-open text-blue-400 mr-2 text-xl"></i>
                    <span class="text-xl font-bold">Dasinformatique</span>
                </div>
            </div>
            <div class="flex flex-col flex-grow overflow-y-auto">
                <div class="px-4 py-6">
                    <button class="flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-2"></i> Nouveau fichier
                    </button>
                </div>
                <nav class="flex-1 px-2 space-y-1">
                    <a href="#" class="flex items-center px-2 py-2 text-sm font-medium rounded-md bg-gray-700 group">
                        <i class="fas fa-home mr-3 text-gray-300 group-hover:text-white"></i>
                        Mes fichiers
                    </a>

                    <!-- Gestion des utilisateurs avec menu déroulant -->
                    <!-- Gestion des utilisateurs -->
                    <div class="relative">
                        <button id="usersManagementBtn" class="w-full flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-gray-700 group">
                            <i class="fas fa-users mr-3 text-gray-300 group-hover:text-white"></i>
                            Gestion des utilisateurs
                            <i class="fas fa-chevron-down ml-auto text-xs text-gray-400 transition-transform duration-200"></i>
                        </button>
                        <div id="usersSubmenu" class="submenu ml-6 mt-1">
                            <a href="#" class="admin-link flex items-center px-2 py-2 text-sm rounded-md hover:bg-gray-700 text-gray-300 hover:text-white">
                                <i class="fas fa-user-shield mr-3"></i>
                                Administrateurs
                            </a>
                            <a href="#" class="user-link flex items-center px-2 py-2 text-sm rounded-md hover:bg-gray-700 text-gray-300 hover:text-white">
                                <i class="fas fa-user mr-3"></i>
                                Utilisateurs
                            </a>
                            <a href="#" class="flex items-center px-2 py-2 text-sm rounded-md hover:bg-gray-700 text-gray-300 hover:text-white">
                                <i class="fas fa-user-plus mr-3"></i>
                                Ajouter un utilisateur
                            </a>
                            <a href="#" class="flex items-center px-2 py-2 text-sm rounded-md hover:bg-gray-700 text-gray-300 hover:text-white">
                                <i class="fas fa-cog mr-3"></i>
                                Paramètres
                            </a>
                        </div>
                    </div>

                    <!-- Historique -->
                    <div class="relative">
                        <button id="historyBtn" class="w-full flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-gray-700 group">
                            <i class="fas fa-history mr-3 text-gray-300 group-hover:text-white"></i>
                            Historique
                            <i class="fas fa-chevron-down ml-auto text-xs text-gray-400 transition-transform duration-200"></i>
                        </button>
                        <div id="historySubmenu" class="submenu ml-6 mt-1">
                            <a href="#" class="login-history-link flex items-center px-2 py-2 text-sm rounded-md hover:bg-gray-700 text-gray-300 hover:text-white">
                                <i class="fas fa-sign-in-alt mr-3"></i>
                                Historique de connexion
                            </a>
                            <a href="#" class="actions-history-link flex items-center px-2 py-2 text-sm rounded-md hover:bg-gray-700 text-gray-300 hover:text-white">
                                <i class="fas fa-tasks mr-3"></i>
                                Historique des actions
                            </a>
                        </div>
                    </div>

                    <a href="#" class="flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-gray-700 group">
                        <i class="fas fa-star mr-3 text-yellow-400 group-hover:text-yellow-300"></i>
                        Favoris
                    </a>
                    <a href="#" class="flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-gray-700 group">
                        <i class="fas fa-history mr-3 text-gray-300 group-hover:text-white"></i>
                        Récents
                    </a>
                    <a href="#" class="flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-gray-700 group">
                        <i class="fas fa-trash-alt mr-3 text-gray-300 group-hover:text-white"></i>
                        Corbeille
                    </a>
                </nav>

                <div class="px-4 py-4">
                    <div class="text-xs uppercase font-semibold text-gray-400 tracking-wider mb-2">Étiquettes</div>
                    <div class="space-y-2">
                        <a href="#" class="flex items-center px-2 py-1 text-sm rounded-md hover:bg-gray-700 group">
                            <span class="w-3 h-3 rounded-full bg-red-500 mr-3"></span>
                            Important
                        </a>
                        <a href="#" class="flex items-center px-2 py-1 text-sm rounded-md hover:bg-gray-700 group">
                            <span class="w-3 h-3 rounded-full bg-blue-500 mr-3"></span>
                            Travail
                        </a>
                        <a href="#" class="flex items-center px-2 py-1 text-sm rounded-md hover:bg-gray-700 group">
                            <span class="w-3 h-3 rounded-full bg-green-500 mr-3"></span>
                            Personnel
                        </a>
                    </div>
                </div>

                <div class="px-4 py-4 mt-auto">
                    <div class="bg-gray-800 rounded-lg p-3">
                        <div class="text-xs font-semibold mb-1">Espace de stockage</div>
                        <div class="w-full bg-gray-700 rounded-full h-2 mb-1">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 37%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-400">
                            <span>12,5 Go utilisés</span>
                            <span>34,2 Go total</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 flex flex-col overflow-hidden bg-white">
        <!-- Barre d'outils -->
        <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-b border-gray-200">
            <div class="flex items-center">
                <span class="text-lg font-semibold text-gray-800">Profil utilisateur</span>
            </div>

            <!-- User menu -->
            <div class="flex items-center relative" id="userDropdownContainer">
                <button id="userDropdownBtn" class="flex items-center text-sm text-gray-700 hover:text-gray-900 focus:outline-none">
                        <span class="ml-2 hidden md:block">
                            Bonjour, <?php echo htmlspecialchars($username) ?>  👋
                        </span>
                    <i class="fas fa-chevron-down ml-1 text-xs hidden md:block"></i>
                </button>

                <!-- Sous-menu -->
                <div id="userDropdownMenu" class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg border hidden z-50">
                    <a href="profil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2"></i> Profil
                    </a>
                    <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                    </a>
                </div>
            </div>
        </div>

        <!-- Contenu du profil -->
        <div class="flex-1 overflow-y-auto p-6">
            <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Informations du profil</h2>
                </div>
                <div class="px-6 py-4">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/3 flex justify-center mb-6 md:mb-0">
                            <div class="relative">
                                <img class="h-32 w-32 rounded-full object-cover" src="https://ui-avatars.com/api/?name=<?php echo urlencode($username) ?>&background=3b82f6&color=fff" alt="Avatar">
                                <button class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 hover:bg-blue-700 focus:outline-none">
                                    <i class="fas fa-camera text-sm"></i>
                                </button>
                            </div>
                        </div>
                        <div class="md:w-2/3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom d'utilisateur</label>
                                    <div class="bg-gray-50 px-3 py-2 rounded border border-gray-200">
                                        <?php echo htmlspecialchars($username); ?>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <div class="bg-gray-50 px-3 py-2 rounded border border-gray-200">
                                        <?php echo htmlspecialchars($email); ?>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                                    <div class="bg-gray-50 px-3 py-2 rounded border border-gray-200">
                                        <?php echo htmlspecialchars($role); ?>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date d'inscription</label>
                                    <div class="bg-gray-50 px-3 py-2 rounded border border-gray-200">
                                        <?php echo date('d/m/Y'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-right">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-edit mr-2"></i> Modifier le profil
                    </button>
                </div>
            </div>

            <div class="max-w-3xl mx-auto mt-6 bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Sécurité</h2>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-medium text-gray-800">Mot de passe</h3>
                                <p class="text-sm text-gray-500">Dernière modification il y a 3 mois</p>
                            </div>
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-edit mr-1"></i> Modifier
                            </button>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-medium text-gray-800">Authentification à deux facteurs</h3>
                                <p class="text-sm text-gray-500">Non activée</p>
                            </div>
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-plus mr-1"></i> Activer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('userDropdownBtn');
        const menu = document.getElementById('userDropdownMenu');

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            menu.classList.toggle('hidden');
        });

        document.addEventListener('click', function () {
            menu.classList.add('hidden');
        });
    });
</script>
</body>
</html>

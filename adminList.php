<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
        exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAS | Gestionnaire de fichiers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        /* Animation de surbrillance des fichiers */
        @keyframes fileHighlight {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .file-highlight {
            animation: fileHighlight 0.3s ease-in-out;
        }

        /* Case à cocher personnalisée */
        .custom-checkbox {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #3b82f6;
            border-radius: 4px;
            outline: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .custom-checkbox:checked {
            background-color: #3b82f6;
        }

        .custom-checkbox:checked::after {
            content: '\2713';
            display: block;
            color: white;
            position: relative;
            left: 2px;
            top: -2px;
            font-size: 12px;
            font-weight: bold;
        }

        /* Menu contextuel */
        .context-menu {
            opacity: 0;
            transform: scale(0);
            transform-origin: top left;
            transition: all 0.15s ease;
        }

        .context-menu.active {
            opacity: 1;
            transform: scale(1);
        }

        /* Animation pour les sous-menus */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .submenu.open {
            max-height: 500px;
        }

        .rotate-90 {
            transform: rotate(90deg);
        }

        /* Tableau de gestion */

        .management-table.active {
            display: block;
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
                    <a href="index.php" class="flex items-center px-2 py-2 text-sm font-medium rounded-md  group">
                        <i class="fas fa-home mr-3 text-gray-300 group-hover:text-white"></i>
                        Mes fichiers
                    </a>

                    <!-- Gestion des utilisateurs avec menu déroulant -->
                    <!-- Gestion des utilisateurs -->
                    <a href="adminList.php" class="flex items-center px-2 py-2 text-sm font-medium rounded-md bg-gray-700 group">
                        <i class="fas fa-users mr-3 text-gray-300 group-hover:text-white"></i>
                        Gestion des utilisateurs
                    </a>

                    <!-- Historique -->
                    <div class="relative">
                        <button id="historyBtn" class="w-full flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-gray-700 group">
                            <i class="fas fa-history mr-3 text-gray-300 group-hover:text-white"></i>
                            Historique
                            <i class="fas fa-chevron-down ml-auto text-xs text-gray-400 transition-transform duration-200"></i>
                        </button>
                        <div id="historySubmenu" class="submenu ml-6 mt-1">
                            <a href="cHis.php" class="login-history-link flex items-center px-2 py-2 text-sm rounded-md hover:bg-gray-700 text-gray-300 hover:text-white">
                                <i class="fas fa-sign-in-alt mr-3"></i>
                                Historique de connexion
                            </a>
                            <a href="aHis.php" class="actions-history-link flex items-center px-2 py-2 text-sm rounded-md hover:bg-gray-700 text-gray-300 hover:text-white">
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


            </div>

            <div class="flex items-center">


            </div>

            <!-- User menu -->
            <div class="flex items-center relative" id="userDropdownContainer">
                <button id="userDropdownBtn" class="flex items-center text-sm text-gray-700 hover:text-gray-900 focus:outline-none">
        <span class="ml-2 hidden md:block">
            Bonjour, <?php echo htmlspecialchars($_SESSION['username']) ?> 👋
        </span>
                    <i class="fas fa-chevron-down ml-1 text-xs hidden md:block"></i>
                </button>

                <!-- Sous-menu -->
                <div id="userDropdownMenu" class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg border hidden z-50">
                    <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2"></i> Profil
                    </a>
                    <a href="backend/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                    </a>
                </div>
            </div>

        </div>

        <!-- Fil d'Ariane -->
        <div class="px-4 py-3 border-b border-gray-200">
            <nav class="flex" aria-label="Fil d'Ariane">
                <ol class="flex items-center space-x-4">
                    <li>
                        <div>
                            <a href="#" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-home"></i>
                                <span class="sr-only">Accueil</span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
                            <a href="#" class="ml-2 text-sm font-medium text-gray-500 hover:text-gray-700">Documents</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
                            <a href="#" class="ml-2 text-sm font-medium text-gray-500 hover:text-gray-700">Liste des admins</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
                            <span class="ml-2 text-sm font-medium text-gray-700">Actuel</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <?php
        // Fetch admins

        require 'backend/connect.php';
        $stmt = $pdo->query("SELECT * FROM users WHERE role LIKE '%admin%' OR role LIKE '%user%' ORDER BY created_at DESC");
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <!-- Tableau des administrateurs -->
        <div id="adminTable" class="management-table p-4 bg-white shadow-md rounded-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Liste des administrateurs</h2>
                <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-plus mr-2"></i> Ajouter un administrateur
                </button>
            </div>

            <!-- 🔽 MODAL -->
            <div id="adminModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Ajouter un administrateur</h3>
                        <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                    </div>

                    <form action="backend/ajouter_admin.php" method="POST" class="space-y-4">
                        <input name="firstname" required type="text" placeholder="Prénom" class="border w-full p-2 rounded">
                        <input name="lastname" required type="text" placeholder="Nom" class="border w-full p-2 rounded">
                        <input name="email" required type="email" placeholder="Email" class="border w-full p-2 rounded">
                        <input name="password" required type="password" placeholder="Mot de passe" class="border w-full p-2 rounded">
                        <select name="role" class="border w-full p-2 rounded">
                            <option value="admin">Administrateur</option>
                            <option value="user">utilisateur</option>
                        </select>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ADMIN TABLE -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Créé le</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($admins as $admin): ?>
                        <?php
                        $fullname = htmlspecialchars($admin['firstname'] . ' ' . $admin['lastname']);
                        $email = htmlspecialchars($admin['email']);
                        $role = htmlspecialchars($admin['role']);
                        $id = $admin['id'];

                        ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?= $fullname ?></div>
                                        <div class="text-sm text-gray-500"><?= $role ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?= $email ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?= $admin['created_at'] ?></td>

                            <td class="px-6 py-4 text-sm font-medium">
                                <button type="button"
                                        onclick='openEditModal(<?= json_encode([
                                            'id' => $id,
                                            'firstname' => $admin["firstname"],
                                            'lastname' => $admin["lastname"],
                                            'email' => $admin["email"],
                                            'role' => $admin["role"]
                                        ]) ?>)'
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i> Modifier
                                </button>
                                <form action="backend/supprimer_admin.php" method="POST" class="inline delete-form">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- JS to toggle modal -->
        <script>
            function openModal() {
                document.getElementById("adminModal").classList.remove("hidden");
            }
            function closeModal() {
                document.getElementById("adminModal").classList.add("hidden");
            }
        </script>




    </div>
</div>


<!-- Modal pour modifier -->
<div id="editAdminModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Modifier un administrateur</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>

        <form action="backend/modifier_admin.php" method="POST" class="space-y-4">
            <input type="hidden" id="edit-id" name="id">
            <input type="text" id="edit-firstname" name="firstname" class="border w-full p-2 rounded" required>
            <input type="text" id="edit-lastname" name="lastname" class="border w-full p-2 rounded" required>
            <input type="email" id="edit-email" name="email" class="border w-full p-2 rounded" required>
            <select id="edit-role" name="role" class="border w-full p-2 rounded" required>
                <option value="admin">Administrateur</option>
                <option value="user">utilisateur</option>
            </select>
            <div class="flex justify-end">
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>



<!-- script pour modifier -->
<script>
    function openModal() {
        document.getElementById("adminModal").classList.remove("hidden");
    }

    function closeModal() {
        document.getElementById("adminModal").classList.add("hidden");
    }

    function openEditModal(admin) {
        document.getElementById("edit-id").value = admin.id;
        document.getElementById("edit-firstname").value = admin.firstname;
        document.getElementById("edit-lastname").value = admin.lastname;
        document.getElementById("edit-email").value = admin.email;
        document.getElementById("edit-role").value = admin.role;
        document.getElementById("editAdminModal").classList.remove("hidden");
    }

    function closeEditModal() {
        document.getElementById("editAdminModal").classList.add("hidden");
    }
</script>


<!-- Modal d'import (caché par défaut) -->
<div id="uploadModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-cloud-upload-alt text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Importer des fichiers</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Sélectionnez des fichiers depuis votre ordinateur ou glissez-déposez les dans la zone ci-dessous.</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                    <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-3"></i>
                    <p class="text-sm text-gray-600 mb-2">Glissez-déposez des fichiers ici</p>
                    <p class="text-xs text-gray-500 mb-4">ou</p>
                    <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-folder-open mr-2"></i> Parcourir
                    </button>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Importer
                </button>
                <button id="cancelUpload" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Fonctionnalité de sélection de fichiers
    document.addEventListener('DOMContentLoaded', function() {
        const fileItems = document.querySelectorAll('.file-item');
        const fileCheckboxes = document.querySelectorAll('.file-checkbox');
        const downloadBtn = document.getElementById('downloadBtn');
        const shareBtn = document.getElementById('shareBtn');
        const deleteBtn = document.getElementById('deleteBtn');
        const contextMenu = document.getElementById('contextMenu');
        const filesGrid = document.getElementById('filesGrid');
        const uploadModal = document.getElementById('uploadModal');
        const cancelUpload = document.getElementById('cancelUpload');
        const adminLink = document.querySelector('.admin-link');
        const userLink = document.querySelector('.user-link');
        const adminTable = document.getElementById('adminTable');
        const userTable = document.getElementById('userTable');
        const loginHistoryLink = document.querySelector('.login-history-link');
        const actionsHistoryLink = document.querySelector('.actions-history-link');
        const loginHistoryTable = document.getElementById('loginHistoryTable');
        const actionsHistoryTable = document.getElementById('actionsHistoryTable');

        let selectedFiles = [];

        // Gestion de la sélection de fichiers
        fileCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const fileItem = this.closest('.file-item');

                if (this.checked) {
                    fileItem.classList.add('bg-blue-50', 'border-blue-200');
                    selectedFiles.push(fileItem);
                } else {
                    fileItem.classList.remove('bg-blue-50', 'border-blue-200');
                    selectedFiles = selectedFiles.filter(item => item !== fileItem);
                }

                // Mise à jour de l'état des boutons
                updateActionButtons();

                // Ajout de l'animation de surbrillance
                fileItem.classList.add('file-highlight');
                setTimeout(() => {
                    fileItem.classList.remove('file-highlight');
                }, 300);
            });
        });

        // Gestion du clic sur un fichier (pour les clics hors case à cocher)
        fileItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // Ignorer les clics sur les cases à cocher ou leurs labels
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'LABEL') {
                    return;
                }

                // Trouver la case à cocher dans cet élément de fichier
                const checkbox = this.querySelector('.file-checkbox');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });
        });

        // Gestion du menu contextuel
        filesGrid.addEventListener('contextmenu', function(e) {
            e.preventDefault();

            // Fermer tout menu contextuel existant
            contextMenu.classList.remove('active');

            // Positionner le menu contextuel
            const { clientX: mouseX, clientY: mouseY } = e;
            contextMenu.style.left = `${mouseX}px`;
            contextMenu.style.top = `${mouseY}px`;

            // Afficher le menu contextuel
            contextMenu.classList.add('active');
        });

        // Fermer le menu contextuel en cliquant ailleurs
        document.addEventListener('click', function() {
            contextMenu.classList.remove('active');
        });

        // Gestion du sous-menu de gestion des utilisateurs
        const usersManagementBtn = document.getElementById('usersManagementBtn');
        const usersSubmenu = document.getElementById('usersSubmenu');
        const chevronIcon = usersManagementBtn.querySelector('.fa-chevron-down');

        usersManagementBtn.addEventListener('click', function() {
            usersSubmenu.classList.toggle('open');
            chevronIcon.classList.toggle('rotate-90');
        });

        const historyBtn = document.getElementByI



        d('historyBtn');
        const historySubmenu = document.getElementById('historySubmenu');
        const historyChevron = historyBtn.querySelector('.fa-chevron-down');

        historyBtn.addEventListener('click', function () {
            historySubmenu.classList.toggle('open');
            historyChevron.classList.toggle('rotate-90');
        });

        // Afficher le tableau des administrateurs
        adminLink.addEventListener('click', function(e) {
            e.preventDefault();
            hideAllTables();
            adminTable.classList.add('active');
        });

        // Afficher le tableau des utilisateurs
        userLink.addEventListener('click', function(e) {
            e.preventDefault();
            hideAllTables();
            userTable.classList.add('active');
        });

        // Afficher le tableau des connexions
        loginHistoryLink.addEventListener('click', function(e) {
            e.preventDefault();
            hideAllTables();
            loginHistoryTable.classList.add('active');
        });

        // Afficher le tableau des actions
        actionsHistoryLink.addEventListener('click', function(e) {
            e.preventDefault();
            hideAllTables();
            actionsHistoryTable.classList.add('active');
        });

        // Masquer tous les tableaux
        function hideAllTables() {
            filesGrid.style.display = 'block';
            adminTable.classList.remove('active');
            userTable.classList.remove('active');
            loginHistoryTable.classList.remove('active');
            actionsHistoryTable.classList.remove('active');
        }

        // Mettre à jour les boutons de la barre d'outils en fonction de la sélection
        function updateActionButtons() {
            const anySelected = selectedFiles.length > 0;
            downloadBtn.disabled = !anySelected;
            shareBtn.disabled = !anySelected;
            deleteBtn.disabled = !anySelected;

            // Changer les styles des boutons lorsqu'ils sont activés
            if (anySelected) {
                downloadBtn.classList.remove('text-gray-700');
                shareBtn.classList.remove('text-gray-700');
                deleteBtn.classList.remove('text-gray-700');

                downloadBtn.querySelector('i').classList.remove('text-gray-500');
                shareBtn.querySelector('i').classList.remove('text-gray-500');
                deleteBtn.querySelector('i').classList.remove('text-gray-500');

                downloadBtn.classList.add('text-blue-700');
                shareBtn.classList.add('text-blue-700');
                deleteBtn.classList.add('text-red-700');

                downloadBtn.querySelector('i').classList.add('text-blue-500');
                shareBtn.querySelector('i').classList.add('text-blue-500');
                deleteBtn.querySelector('i').classList.add('text-red-500');
            } else {
                downloadBtn.classList.add('text-gray-700');
                shareBtn.classList.add('text-gray-700');
                deleteBtn.classList.add('text-gray-700');

                downloadBtn.querySelector('i').classList.add('text-gray-500');
                shareBtn.querySelector('i').classList.add('text-gray-500');
                deleteBtn.querySelector('i').classList.add('text-gray-500');

                downloadBtn.classList.remove('text-blue-700');
                shareBtn.classList.remove('text-blue-700');
                deleteBtn.classList.remove('text-red-700');

                downloadBtn.querySelector('i').classList.remove('text-blue-500');
                shareBtn.querySelector('i').classList.remove('text-blue-500');
                deleteBtn.querySelector('i').classList.remove('text-red-500');
            }
        }

        // Gestion de la modal d'import
        document.querySelector('button.bg-blue-600').addEventListener('click', function() {
            uploadModal.classList.remove('hidden');
        });

        cancelUpload.addEventListener('click', function() {
            uploadModal.classList.add('hidden');
        });

        // Fermer la modal en cliquant à l'extérieur
        uploadModal.addEventListener('click', function(e) {
            if (e.target === uploadModal) {
                uploadModal.classList.add('hidden');
            }
        });
    });

    // Fonction pour simuler l'ouverture d'un dossier
    function openFolder(folderName) {
        alert(`Ouverture du dossier : ${folderName}`);
        // Dans une application réelle, cela naviguerait vers le dossier ou mettrait à jour l'interface pour afficher son contenu
    }
    // Télécharger les fichiers sélectionnés
    downloadBtn.addEventListener('click', () => {
        selectedFiles.forEach(fileItem => {
            const filename = fileItem.dataset.filename;
            window.open(`backend/download.php?filename=${encodeURIComponent(filename)}`, '_blank');
        });
    });

    // Partager les fichiers sélectionnés (génère un lien)
    shareBtn.addEventListener('click', async () => {
        for (const fileItem of selectedFiles) {
            const filename = fileItem.dataset.filename;

            const response = await fetch('backend/share.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `filename=${encodeURIComponent(filename)}`
            });

            const data = await response.json();
            if (data.success) {
                prompt(`Lien de partage pour ${filename}:`, data.url);
            } else {
                alert(`Erreur de partage : ${data.error}`);
            }
        }
    });

    // Supprimer les fichiers sélectionnés
    deleteBtn.addEventListener('click', async () => {
        if (!confirm('Êtes-vous sûr de vouloir supprimer les fichiers sélectionnés ?')) return;

        for (const fileItem of selectedFiles) {
            const filename = fileItem.dataset.filename;

            const response = await fetch('backend/delete.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `filename=${encodeURIComponent(filename)}`
            });

            const data = await response.json();
            if (data.success) {
                // Supprimer l’élément de la grille
                fileItem.remove();
            } else {
                alert(`Erreur lors de la suppression de ${filename}`);
            }
        }

        // Vider la sélection et mettre à jour l’interface
        selectedFiles = [];
        updateActionButtons();
    });


</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('userDropdownBtn');
        const menu = document.getElementById('userDropdownMenu');

        btn.addEventListener('click', function (e) {
            e.stopPropagation(); // Ne pas propager pour ne pas fermer tout de suite
            menu.classList.toggle('hidden');
        });

        // Fermer le menu si clic à l’extérieur
        document.addEventListener('click', function () {
            menu.classList.add('hidden');
        });
    });
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Empêche la soumission immédiate

            Swal.fire({
                title: 'Confirmer la suppression ?',
                text: "Cette action est irréversible.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Soumission manuelle après confirmation
                }
            });
        });
    });

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.get('success') === '1') {
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: 'Administrateur ajouté avec succès.',
            timer: 2000,
            showConfirmButton: false
        });
    }

    if (urlParams.get('error') === 'email') {
        Swal.fire({
            icon: 'error',
            title: 'Adresse e-mail déjà utilisée',
            text: 'Un compte avec cet e-mail existe déjà.',
        });
    }

    if (urlParams.get('error') === 'unknown') {
        Swal.fire({
            icon: 'error',
            title: 'Erreur inconnue',
            text: 'Une erreur inattendue est survenue.',
        });
    }

    // Nettoyer l'URL après affichage
    if (urlParams.has('success') || urlParams.has('error')) {
        const cleanUrl = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.get('updated') === '1') {
            Swal.fire({
                icon: 'success',
                title: 'Mise à jour réussie',
                text: 'Les informations de l\'administrateur ont été modifiées.',
                timer: 2000,
                showConfirmButton: false
            });

            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }
    });
</script>
<script>
    const historyBtn = document.getElementById('historyBtn');
    const historySubmenu = document.getElementById('historySubmenu');
    const chevron = document.getElementById('chevron');

    historyBtn.addEventListener('click', () => {
        historySubmenu.classList.toggle('open');
        chevron.classList.toggle('rotate-90');
    });
</script>
</body>
</html>
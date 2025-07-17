
<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Get current folder id from URL or null (root)
$currentFolderId = isset($_GET['folder_id']) ? (int)$_GET['folder_id'] : null;

// Function to build breadcrumbs
function buildBreadcrumb($pdo, $folderId) {
    $crumbs = [];
    while ($folderId) {
        $stmt = $pdo->prepare("SELECT id, filename, parent_id FROM files WHERE id = ?");
        $stmt->execute([$folderId]);
        $folder = $stmt->fetch();
        if ($folder) {
            $crumbs[] = $folder;
            $folderId = $folder['parent_id'];
        } else {
            break;
        }
    }
    return array_reverse($crumbs);
}

// ✅ Fetch all files and folders (for all users)
if ($currentFolderId) {
    $stmt = $pdo->prepare("
        SELECT f.*, u.firstname AS created_by_user
        FROM files f
        LEFT JOIN users u ON f.user_id = u.id
        WHERE f.parent_id = ?
        ORDER BY f.filetype DESC, f.filename ASC
    ");
    $stmt->execute([$currentFolderId]);
} else {
    $stmt = $pdo->prepare("
        SELECT f.*, u.firstname AS created_by_user
        FROM files f
        LEFT JOIN users u ON f.user_id = u.id
        WHERE f.parent_id IS NULL
        ORDER BY f.filetype DESC, f.filename ASC
    ");
    $stmt->execute();
}

$files = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Breadcrumbs
$breadcrumbs = buildBreadcrumb($pdo, $currentFolderId);

// Count items inside a folder
function countFolderItems($folderId, $pdo) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM files WHERE parent_id = ?");
    $stmt->execute([$folderId]);
    $count = $stmt->fetchColumn();
    return $count ?: 0;
}

// Format date
function formatDate($dateString) {
    if (!$dateString) return '';
    $timestamp = strtotime($dateString);
    if (!$timestamp) return '';
    return date('d F Y', $timestamp);
}
$userId = $_SESSION['user_id'] ?? null;
$folderId = isset($_GET['folder_id']) ? (int)$_GET['folder_id'] : null;

if ($folderId) {
    $stmt = $pdo->prepare("SELECT password FROM files WHERE id = ? AND user_id = ?");
    $stmt->execute([$folderId, $userId]);
    $folder = $stmt->fetch();

    if ($folder && !empty($folder['password'])) {
        // Check if user has access in session
        if (empty($_SESSION['folder_access'][$folderId])) {
            // Redirect back to same folder with flag to open password modal
            header("Location: index.php?folder_id=$folderId&need_password=1");
            exit;
        }
    }
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
    <style>

            /* Simple styles for modals */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
        }
        .modal.active {
            display: flex;
        }

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
        .management-table {
            display: none;
        }

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
                    <a href="#" class="flex items-center px-2 py-2 text-sm font-medium rounded-md bg-gray-700 group">
                        <i class="fas fa-home mr-3 text-gray-300 group-hover:text-white"></i>
                        Mes fichiers
                    </a>

                    <!-- Gestion des utilisateurs avec menu déroulant -->
                    <!-- Gestion des utilisateurs -->
                    <a href="adminList.php" class="flex items-center px-2 py-2 text-sm font-medium rounded-md  group">
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
                <div class="mr-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" class="custom-checkbox">
                    </label>
                </div>
                <div class="space-x-3">
                    <button id="downloadBtn" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50" disabled>
                        <i class="fas fa-download mr-2 text-gray-500"></i> Télécharger
                    </button>
                    <button id="copyBtn" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50" disabled  >
                        <i class="fas fa-copy mr-2 text-gray-500"></i> Copier
                    </button>
                    <button id="pasteBtn" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50" disabled>
                        <i class="fas fa-paste mr-2 text-gray-500"></i> Coller
                    </button>
                    <button id="deleteBtn" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50" disabled>
                        <i class="fas fa-trash-alt mr-2 text-gray-500"></i> Supprimer
                    </button>
                </div>
            </div>

            <div class="flex items-center">
                <div class="mr-4">
                    <select class="block w-full pl-3 pr-10 py-1 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option>Trier par nom</option>
                        <option>Trier par date de modification</option>
                        <option>Trier par taille</option>
                    </select>
                </div>
                <div class="flex">
                    <button class="p-1 rounded-md hover:bg-gray-200 text-gray-500">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button class="p-1 rounded-md hover:bg-gray-200 text-gray-500 ml-1">
                        <i class="fas fa-list-ul"></i>
                    </button>
                </div>
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
                            <a href="#" class="ml-2 text-sm font-medium text-gray-500 hover:text-gray-700">Projets</a>
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


        <div class="max-w-7xl mx-auto p-4 bg-white rounded shadow mt-6">

            <!-- Breadcrumb -->
            <nav class="mb-4 text-sm text-gray-700" aria-label="breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li>
                        <a href="index.php" class="hover:underline"><i class="fas fa-home"></i> Accueil</a>
                    </li>
                    <?php foreach ($breadcrumbs as $crumb): ?>
                        <li>
                            <span class="mx-2 text-gray-400">/</span>
                            <a href="index.php?folder_id=<?= $crumb['id'] ?>" class="hover:underline"><?= htmlspecialchars($crumb['filename']) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>

            <!-- Toolbar -->
            <div class="flex justify-between mb-4">
                <div>
                    <button onclick="openFolderModal()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        <i class="fas fa-folder-plus mr-2"></i> Nouveau dossier
                    </button>
                    <form action="backend/upload_file.php" method="POST" enctype="multipart/form-data" id="uploadForm" class="inline-block ml-2">
                        <input type="hidden" name="parent_id" value="<?= $currentFolderId ?? '' ?>">
                        <input type="file" name="uploaded_file" id="fileInput" style="display:none;" required />
                        <button
                                type="button"
                                onclick="document.getElementById('fileInput').click()"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center"
                        >
                            <i class="fas fa-upload mr-2"></i> Importer fichier
                        </button>
                    </form>
                    <script>
                        document.getElementById('fileInput').addEventListener('change', function () {
                            if (this.files.length > 0) {
                                document.getElementById('uploadForm').submit();
                            }
                        });
                    </script>
                </div>
            </div>

            <!-- Files Grid -->
            <div id="filesContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                <?php if (empty($files)): ?>
                    <p class="text-gray-500 col-span-full text-center py-12">Ce dossier est vide.</p>
                <?php else: ?>
                    <?php foreach ($files as $file): ?>
                        <div
                                class="relative file-item group"
                                data-id="<?= $file['id'] ?>"
                                data-filename="<?= htmlspecialchars($file['filename']) ?>"
                                data-filetype="<?= $file['filetype'] ?>"
                                data-password-protected="<?= !empty($file['password']) ? '1' : '0' ?>"
                        >                            <!-- Checkbox -->
                            <label class="absolute top-3 left-3 z-10 cursor-pointer">
                                <input
                                        type="checkbox"
                                        name="selected_files[]"
                                        value="<?= $file['id'] ?>"
                                        class="custom-checkbox opacity-0 group-hover:opacity-100 file-checkbox absolute top-0 left-0 w-5 h-5"
                                />
                            </label>

                            <!-- File / Folder Card -->
                            <div
                                    class="relative h-32 flex flex-col items-center justify-center rounded-lg border border-gray-200 bg-white overflow-hidden cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-all duration-200"
                                    ondblclick="<?= $file['filetype'] === 'folder' ? "openFolder({$file['id']})" : "" ?>"
                                    title="<?= htmlspecialchars($file['filename']) ?>"
                            >
                                <div class="p-4 text-center select-none">
                                    <?php if ($file['filetype'] === 'folder'): ?>

                                        <i class="fas fa-folder text-yellow-400 text-4xl mb-2"></i>
                                        <?php if (!empty($file['password'])): ?>
                                            <i class="fas fa-lock absolute bottom-30 right-10 -mr-1 -mb-1 text-gray-400 text-s" title="Dossier protégé"></i>
                                        <?php endif; ?>

                                        <div class="text-sm font-medium text-gray-900 truncate w-full"><?= htmlspecialchars($file['filename']) ?></div>
                                        <div class="text-xs text-gray-500"><?= countFolderItems($file['id'], $pdo) ?> élément(s)</div>
                                        <div class="text-xs text-gray-400 mt-1">Créé par: <?= htmlspecialchars($file['created_by_user'] ?? 'Inconnu') ?></div>
                                        <div class="text-xs text-gray-400">Modifié: <?= formatDate($file['modified_at'] ?? $file['uploaded_at']) ?></div>
                                    <?php else: ?>
                                        <!-- File icons by type (example for pdf, docx, image...) -->
                                        <?php
                                        $ext = strtolower(pathinfo($file['filename'], PATHINFO_EXTENSION));
                                        $iconClass = "fa-file-alt text-gray-400";
                                        if (in_array($ext, ['pdf'])) $iconClass = "fa-file-pdf text-red-500";
                                        elseif (in_array($ext, ['doc', 'docx'])) $iconClass = "fa-file-word text-blue-500";
                                        elseif (in_array($ext, ['xls', 'xlsx'])) $iconClass = "fa-file-excel text-green-600";
                                        elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) $iconClass = "fa-file-image text-green-500";
                                        elseif (in_array($ext, ['zip', 'rar', '7z'])) $iconClass = "fa-file-archive text-yellow-600";
                                        ?>
                                        <i class="fas <?= $iconClass ?> text-4xl mb-2"></i>
                                        <div class="text-sm font-medium text-gray-900 truncate w-full"><?= htmlspecialchars($file['filename']) ?></div>
                                        <div class="text-xs text-gray-500"><?= number_format($file['filesize'] / 1024 / 1024, 2) ?> Mo</div>
                                        <div class="text-xs text-gray-400 mt-1">Créé par: <?= htmlspecialchars($file['created_by_user'] ?? 'Inconnu') ?></div>
                                        <div class="text-xs text-gray-400">Modifié: <?= formatDate($file['modified_at'] ?? $file['uploaded_at']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <!-- Hover action buttons -->
                                <!-- Hover action buttons -->
                                <div
                                        class="absolute bottom-0 left-0 right-0 h-8 bg-white bg-opacity-90 flex items-center justify-between px-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                >

                                    <!-- Rename button -->
                                    <button
                                                class="text-blue-600 hover:text-blue-800 text-xs focus:outline-none"
                                                title="Renommer"
                                                onclick="event.stopPropagation(); openRenameModal(<?= $file['id'] ?>, '<?= htmlspecialchars($file['filename'], ENT_QUOTES) ?>');"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    <button
                                            class="text-blue-600 hover:text-blue-800 text-xs focus:outline-none"
                                            title="Infos"
                                            aria-label="Infos"
                                            onclick="event.stopPropagation(); alert('Infos: <?= addslashes(htmlspecialchars($file['filename'])) ?>');"
                                    >
                                        <i class="fas fa-info-circle"></i>
                                    </button>

                                    <?php if ($file['filetype'] === 'folder'): ?>
                                        <button
                                                class="text-yellow-600 hover:text-yellow-800 text-xs focus:outline-none"
                                                title="Modifier le mot de passe"
                                                onclick="event.stopPropagation(); openUpdatePasswordModal(<?= $file['id'] ?>);"
                                        >
                                            <i class="fas fa-lock"></i>
                                        </button>
                                        <!-- Supprimer mot de passe -->
                                        <button
                                                class="text-red-600 hover:text-red-800 text-xs focus:outline-none"
                                                title="Supprimer le mot de passe"
                                                onclick="event.stopPropagation(); confirmDeletePassword(<?= $file['id'] ?>);">
                                            <i class="fas fa-lock-open"></i>
                                        </button>
                                        <!-- Renommer -->
                                        <button
                                                class="text-blue-600 hover:text-blue-800 text-xs focus:outline-none"
                                                title="Renommer"
                                                onclick="event.stopPropagation(); openRenameModal(<?= $file['id'] ?>, '<?= htmlspecialchars($file['filename'], ENT_QUOTES) ?>');"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>

                                    <?php endif; ?>

                                    <button
                                            class="text-blue-600 hover:text-blue-800 text-xs focus:outline-none"
                                            title="Plus d'options"
                                            aria-label="Plus d'options"
                                            onclick="event.stopPropagation(); alert('Options: <?= addslashes(htmlspecialchars($file['filename'])) ?>');"
                                    >
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Folder Password Modal -->
        <div id="folderPasswordModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow w-96">
                <h2 class="text-xl mb-4 font-semibold">Entrez le mot de passe du dossier</h2>
                <form id="folderPasswordForm" class="space-y-4">
                    <input type="password" id="folderPasswordInput" placeholder="Mot de passe" required
                           class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-green-600" />
                    <div class="flex justify-end space-x-2">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Valider</button>
                        <button type="button" onclick="closeFolderPasswordModal()"
                                class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100 transition">Annuler</button>
                    </div>
                </form>
                <p id="folderPasswordError" class="text-red-600 mt-2 hidden">Mot de passe incorrect</p>
            </div>
        </div>

        <script>
            function openFolderPasswordModal(folderId) {
                document.getElementById('folderPasswordModal').classList.remove('hidden');
                document.getElementById('folderPasswordModal').classList.add('flex');
                document.getElementById('folderPasswordForm').dataset.folderId = folderId;
                document.getElementById('folderPasswordError').classList.add('hidden');
                document.getElementById('folderPasswordInput').value = '';
                document.getElementById('folderPasswordInput').focus();
            }

            function closeFolderPasswordModal() {
                document.getElementById('folderPasswordModal').classList.remove('flex');
                document.getElementById('folderPasswordModal').classList.add('hidden');
            }

            document.getElementById('folderPasswordForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const folderId = this.dataset.folderId;
                const password = document.getElementById('folderPasswordInput').value;

                const response = await fetch('backend/verify_folder_password.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({folder_id: folderId, password: password})
                });

                const result = await response.json();

                if (result.success) {
                    // Password correct, redirect to folder with access granted flag
                    window.location.href = 'index.php?folder_id=' + folderId + '&access_granted=1';
                } else {
                    // Show error message
                    document.getElementById('folderPasswordError').classList.remove('hidden');
                }
            });
        </script>
        <!-- update folder password -->

        <div id="updatePasswordModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow w-96">
                <h2 class="text-xl font-semibold mb-4">Modifier le mot de passe</h2>
                <form id="updatePasswordForm" class="space-y-4">
                    <input type="password" id="newPasswordInput" placeholder="Nouveau mot de passe"
                           class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-600" required />
                    <input type="hidden" id="updateFolderId" />
                    <div class="flex justify-end space-x-2">
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Modifier</button>
                        <button type="button" onclick="closeUpdatePasswordModal()"
                                class="px-4 py-2 rounded border hover:bg-gray-100 transition">Annuler</button>
                    </div>
                </form>
            </div>
        </div>



        <!-- Modal Nouveau Dossier -->
        <div id="folderModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow w-96">
                <h2 class="text-xl mb-4 font-semibold">Créer un nouveau dossier</h2>
                <form action="backend/create_folder.php" method="POST" class="space-y-4">
                    <input type="text" name="folder_name" placeholder="Nom du dossier" required
                           class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-green-600" />

                    <!-- Password Field -->
                    <input type="password" name="password" placeholder="Mot de passe (facultatif)"
                           class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-green-600" />

                    <input type="hidden" name="parent_id" value="<?= $currentFolderId ?? '' ?>">
                    <div class="flex justify-end space-x-2">
                        <button type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Créer</button>
                        <button type="button" onclick="closeFolderModal()"
                                class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100 transition">Annuler</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Modal Importer fichier -->
        <div id="uploadModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow w-96">
                <h2 class="text-xl mb-4 font-semibold">Importer un fichier</h2>
                <form action="backend/upload_file.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="file" name="uploaded_file" required
                           class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-600" />
                    <input type="hidden" name="parent_id" value="<?= $currentFolderId ?? '' ?>">
                    <div class="flex justify-end space-x-2">
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Importer</button>
                        <button type="button" onclick="closeUploadModal()"
                                class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100 transition">Annuler</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openFolderModal() {
                document.getElementById('folderModal').classList.remove('hidden');
                document.getElementById('folderModal').classList.add('flex');
            }
            function closeFolderModal() {
                document.getElementById('folderModal').classList.remove('flex');
                document.getElementById('folderModal').classList.add('hidden');
            }
            function openUploadModal() {
                document.getElementById('uploadModal').classList.remove('hidden');
                document.getElementById('uploadModal').classList.add('flex');
            }
            function closeUploadModal() {
                document.getElementById('uploadModal').classList.remove('flex');
                document.getElementById('uploadModal').classList.add('hidden');
            }
            function openUpdatePasswordModal(folderId) {
                Swal.fire({
                    title: 'Modifier le mot de passe',
                    html:
                        '<input id="current-password" type="password" class="swal2-input" placeholder="Mot de passe actuel">' +
                        '<input id="new-password" type="password" class="swal2-input" placeholder="Nouveau mot de passe">',
                    confirmButtonText: 'Mettre à jour',
                    showCancelButton: true,
                    preConfirm: () => {
                        const currentPassword = document.getElementById('current-password').value;
                        const newPassword = document.getElementById('new-password').value;

                        if (!currentPassword || !newPassword) {
                            Swal.showValidationMessage('Tous les champs sont requis');
                            return false;
                        }

                        return fetch('backend/update_folder_password.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                id: folderId,
                                current_password: currentPassword,
                                new_password: newPassword
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (!data.success) throw new Error(data.message);
                                return data;
                            })
                            .catch(err => {
                                Swal.showValidationMessage(`Erreur : ${err.message}`);
                            });
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        Swal.fire('Succès', 'Mot de passe mis à jour.', 'success');
                    }
                });
            }

        </script>

        <script>
    // Fonctionnalité de sélection de fichiers


    document.addEventListener('DOMContentLoaded', function() {
        let selectedFiles = [];
        let copiedItem = null;
        let currentFolderId = null;
        const urlParams = new URLSearchParams(window.location.search);
        currentFolderId = urlParams.get('folder_id') || null;

        console.log('Current folder ID:', currentFolderId);
        const fileItems = document.querySelectorAll('.file-item');
        const fileCheckboxes = document.querySelectorAll('.file-checkbox');
        const downloadBtn = document.getElementById('downloadBtn');
        const deleteBtn = document.getElementById('deleteBtn');
        const copyBtn = document.getElementById('copyBtn');
        const pasteBtn = document.getElementById('pasteBtn');

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



        copyBtn.addEventListener('click', () => {
            const checked = document.querySelector('.file-checkbox:checked');
            if (!checked) {
                console.log('No file selected for copying');
                return Swal.fire('Aucun élément sélectionné');
            }

            const fileItem = checked.closest('.file-item');
            copiedItem = {
                id: checked.value,
                type: fileItem.dataset.filetype,
                name: fileItem.dataset.filename
            };
            console.log('Copied item:', copiedItem);

            Swal.fire('Élément copié', `"${copiedItem.name}" a été copié.`, 'success');

            pasteBtn.disabled = false;
            pasteBtn.classList.remove('text-gray-700');
            pasteBtn.classList.add('text-green-700');
            pasteBtn.querySelector('i').classList.remove('text-gray-500');
            pasteBtn.querySelector('i').classList.add('text-green-500');
        });
        pasteBtn.addEventListener('click', async () => {
            console.log('Paste button clicked');
            copiedItem = JSON.parse(localStorage.getItem('copiedItem'));

            if (!copiedItem) {
                console.log('No copied item');
                return Swal.fire('Erreur', 'Aucun élément copié.', 'error');
            }

            // Get selected folder checkbox (we assume folders have filetype === "folder")
            const checkedFolder = Array.from(document.querySelectorAll('.file-checkbox:checked'))
                .map(cb => cb.closest('.file-item'))
                .find(item => item.dataset.filetype === 'folder');

            const targetFolderId = checkedFolder
                ? checkedFolder.querySelector('.file-checkbox').value
                : currentFolderId;

            if (!targetFolderId) {
                console.log('No target folder selected');
                return Swal.fire('Erreur', 'Aucun dossier cible sélectionné.', 'error');
            }

            const confirmResult = await Swal.fire({
                title: 'Coller l\'élément',
                text: `Voulez-vous coller "${copiedItem.name}" dans ce dossier ?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, coller',
                cancelButtonText: 'Annuler'
            });

            if (!confirmResult.isConfirmed) return;

            try {
                const res = await fetch('backend/paste_items.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        item_id: copiedItem.id,
                        item_type: copiedItem.type,
                        target_folder_id: targetFolderId
                    })
                });

                const data = await res.json();
                if (data.success) {
                    await Swal.fire('Collage réussi', '', 'success');
                    window.location.reload();
                } else {
                    await Swal.fire('Erreur', data.message || 'Erreur lors du collage.', 'error');
                }
            } catch (err) {
                await Swal.fire('Erreur réseau', err.message, 'error');
            }
        });


// Get current folder from URL (for paste target)



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
            deleteBtn.disabled = !anySelected;
            copyBtn.disabled = !anySelected;
            pasteBtn.disabled = !anySelected;



            // Changer les styles des boutons lorsqu'ils sont activés
            if (anySelected) {
                downloadBtn.classList.remove('text-gray-700');
                deleteBtn.classList.remove('text-gray-700');

                downloadBtn.querySelector('i').classList.remove('text-gray-500');
                deleteBtn.querySelector('i').classList.remove('text-gray-500');

                downloadBtn.classList.add('text-blue-700');
                deleteBtn.classList.add('text-red-700');

                downloadBtn.querySelector('i').classList.add('text-blue-500');
                deleteBtn.querySelector('i').classList.add('text-red-500');
            } else {
                downloadBtn.classList.add('text-gray-700');
                deleteBtn.classList.add('text-gray-700');

                downloadBtn.querySelector('i').classList.add('text-gray-500');
                deleteBtn.querySelector('i').classList.add('text-gray-500');

                downloadBtn.classList.remove('text-blue-700');
                deleteBtn.classList.remove('text-red-700');

                downloadBtn.querySelector('i').classList.remove('text-blue-500');
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
    function openFolder(folderId) {
        fetch(`backend/check_folder_password.php?folder_id=${folderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.password_protected) {
                    // Folder protected — show password modal
                    openFolderPasswordModal(folderId);
                } else {
                    // Not protected — open folder directly
                    window.location.href = `index.php?folder_id=${folderId}`;
                }
            })
            .catch(() => alert('Erreur lors de la vérification du dossier'));
    }

    // Télécharger les fichiers sélectionnés
    document.addEventListener('DOMContentLoaded', function() {
        const fileCheckboxes = document.querySelectorAll('.file-checkbox');
        const downloadBtn = document.getElementById('downloadBtn');
        let selectedFileId = null;

        // Allow only one checkbox to be checked at a time
        fileCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    // Uncheck all others
                    fileCheckboxes.forEach(cb => {
                        if (cb !== this) cb.checked = false;
                    });
                    selectedFileId = this.value;
                    downloadBtn.disabled = false;
                } else {
                    selectedFileId = null;
                    downloadBtn.disabled = true;
                }
            });
        });

        downloadBtn.addEventListener('click', function() {
            if (!selectedFileId) return;

            // Redirect to your backend download script with the file ID
            window.location.href = 'backend/download.php?id=' + encodeURIComponent(selectedFileId);
        });
    });



    // Partager les fichiers sélectionnés (génère un lien)

    // Supprimer les fichiers sélectionnés


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
</script>


        <script>
            document.getElementById('deleteBtn').addEventListener('click', async function () {
                const selectedCheckboxes = Array.from(document.querySelectorAll('.file-checkbox:checked'));
                if (selectedCheckboxes.length === 0) return;

                const confirmResult = await Swal.fire({
                    title: 'Confirmer la suppression',
                    text: `Voulez-vous vraiment supprimer ${selectedCheckboxes.length} élément(s) ?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                });

                if (!confirmResult.isConfirmed) return;

                const itemsToDelete = [];

                for (const checkbox of selectedCheckboxes) {
                    const fileItem = checkbox.closest('.file-item');
                    const id = checkbox.value;
                    const filename = fileItem.dataset.filename;
                    const filetype = fileItem.dataset.filetype;
                    const isPasswordProtected = fileItem.dataset.passwordProtected === '1';

                    let password = null;

                    if (filetype === 'folder' && isPasswordProtected) {
                        const { value: userPassword } = await Swal.fire({
                            title: `Mot de passe requis`,
                            text: `Le dossier "${filename}" est protégé. Entrez le mot de passe pour le supprimer :`,
                            input: 'password',
                            inputPlaceholder: 'Mot de passe',
                            inputAttributes: { autocapitalize: 'off', autocorrect: 'off' },
                            showCancelButton: true,
                            confirmButtonText: 'Valider',
                            cancelButtonText: 'Annuler'
                        });

                        if (!userPassword) {
                            await Swal.fire('Suppression annulée', '', 'info');
                            return;
                        }

                        password = userPassword.trim();
                    }

                    itemsToDelete.push({ id, filename, filetype, password });
                }

                // Envoyer la requête de suppression
                try {
                    const response = await fetch('backend/delete_files.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ items: itemsToDelete })
                    });

                    const data = await response.json();

                    if (data.success) {
                        await Swal.fire('Suppression réussie', '', 'success');
                        window.location.reload();
                    } else {
                        await Swal.fire('Erreur', data.message || 'Erreur lors de la suppression.', 'error');
                    }
                } catch (error) {
                    await Swal.fire('Erreur réseau', error.message, 'error');
                }
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- effacer le mot de passe de dossier -->
        <script>
                function confirmDeletePassword(folderId) {
                Swal.fire({
                    title: 'Supprimer le mot de passe',
                    html: '<input type="password" id="delete-password" class="swal2-input" placeholder="Mot de passe actuel">',
                    showCancelButton: true,
                    confirmButtonText: 'Confirmer',
                    preConfirm: () => {
                        const password = document.getElementById('delete-password').value;

                        if (!password) {
                            Swal.showValidationMessage('Veuillez saisir le mot de passe');
                            return false;
                        }

                        return fetch('backend/delete_folder_password.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                id: folderId,
                                password: password
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (!data.success) throw new Error(data.message);
                                return data;
                            })
                            .catch(err => {
                                Swal.showValidationMessage(`Erreur : ${err.message}`);
                            });
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        Swal.fire('Mot de passe supprimé', '', 'success').then(() => {
                            location.reload();
                        });
                    }
                });
            }

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

        <!-- renomer files and folders -->
        <script>
            function openRenameModal(fileId, currentName) {
                Swal.fire({
                    title: 'Renommer',
                    input: 'text',
                    inputLabel: 'Nouveau nom',
                    inputValue: currentName,
                    showCancelButton: true,
                    confirmButtonText: 'Renommer',
                    cancelButtonText: 'Annuler',
                    inputValidator: (value) => {
                        if (!value.trim()) {
                            return 'Le nom ne peut pas être vide';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('backend/rename_file.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: fileId, new_name: result.value.trim() })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Renommé', '', 'success').then(() => location.reload());
                                } else {
                                    Swal.fire('Erreur', data.message || 'Une erreur est survenue', 'error');
                                }
                            })
                            .catch(err => {
                                Swal.fire('Erreur réseau', err.message, 'error');
                            });
                    }
                });
            }
        </script>


</body>
</html>
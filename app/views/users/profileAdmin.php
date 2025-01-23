<?php
session_start();
// header('Content-Type: application/json');
// Redirect to login page if user is not logged in
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit();
}

$user = $_SESSION['user'];

// Check the user's role
if ($user['role'] !== 'Admin') {
    header('Location: /unauthorized');
    exit();
}
// Include necessary dependencies
require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../models/Admin.php';
require_once __DIR__ . '/../../controllers/AdminController.php';


// Create instance for Database
$database = Database::getInstance();

// Get the PDO object
$pdo = $database->getPdo();

// Create instance for AdminController
$adminController = new AdminController($pdo);

// Get datas
try {
    $statistics = $adminController->getGlobalStatistics();
    $recentUsers = $adminController->getRecentUsers();
    $recentCourses = $adminController->getRecentCourses();
    $users = $adminController->getAllUsers();
    $courses = $adminController->getAllCourses();
    $tags = $adminController->getAllTags();
    $categories = $adminController->getAllCategories();
} catch (Exception $e) {
    // require __DIR__ . '/../views/errors/500.php';
    exit;
}

// Get the form data
$userId = $_POST['userId'] ?? null;
$newRole = $_POST['newRole'] ?? null;
$newStatus = $_POST['newStatus'] ?? null;

if ($userId && $newRole) {
    // Update the user's role
    $result = $adminController->updateUserRole($userId, $newRole);
} elseif ($userId && $newStatus) {
    // Update user status
    $result = $adminController->updateUserStatus($userId, $newStatus);
} else {
    $result = ['success' => false, 'message' => 'Invalid request.'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="/" class="flex text-3xl font-bold">
                <span class="bg-red-500 text-white px-2 rounded-l-md">You</span>
                <span class="bg-gray-100 px-2 rounded-r-md">demy</span>
            </a>
            <!-- User Menu -->
            <div class="flex items-center space-x-6">
                <span class="text-gray-600">
                    <i class="fas fa-user mr-2"></i>
                    <?= htmlspecialchars($user['email']) ?>
                </span>
                <a href="/logout" class="flex items-center text-red-500 hover:text-red-600 transition-colors">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Logout
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8">
        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Total Users</h3>
                <p class="text-3xl font-bold text-red-500"><?= $statistics['total_users'] ?? 'N/A' ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Total Courses</h3>
                <p class="text-3xl font-bold text-red-500"><?= $statistics['total_courses'] ?? 'N/A' ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Active Students</h3>
                <p class="text-3xl font-bold text-red-500"><?= $statistics['active_students'] ?? 'N/A' ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Total Categories</h3>
                <p class="text-3xl font-bold text-red-500"><?= $statistics['total_categories'] ?? 'N/A' ?></p>
            </div>
        </div>

        <!-- Recent Users and Courses -->
        <div class="grid grid-cols-1 lg:grid-cols-6 gap-6">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <nav class="space-y-2">
                        <a href="#" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                        <button onclick="openModal()"class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-users mr-2"></i> Users
                        </button>
                        <button onclick="openCourseModal()" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-book mr-2"></i> Courses
                        </button>
                        <button onclick="openCategoriesModal()" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-tags mr-2"></i> Categories
                        </button>
                        <button onclick="openTagsModal()" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-hashtag mr-2"></i> Tags
                        </button>
                        <a href="#" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-question-circle mr-2"></i> Quizzes
                        </a>
                        <a href="#" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-chart-line mr-2"></i> Statistics
                        </a>
                    </nav>
                </div>
            </div>
            <!-- Modale pour afficher toutes les catégories -->
<div id="categoriesModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Fond sombre -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Contenu de la modale -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg font-bold mb-4">All Categories</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Updated At</th>
                            </tr>
                        </thead>
                        <tbody id="modalCategoriesList" class="divide-y divide-gray-200">
    <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $category): ?>
            <tr>
                <!-- Nom de la catégorie -->
                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($category['name']) ?></td>

                <!-- Date de création -->
                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($category['created_at']) ?></td>

                <!-- Date de mise à jour -->
                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($category['updated_at']) ?></td>

                <!-- Actions (Modifier et Supprimer) -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <!-- Formulaire pour modifier la catégorie -->
                    <form action="update-category.php" method="POST" class="inline">
                        <input type="hidden" name="categoryId" value="<?= $category['id'] ?>">
                        <input 
                            type="text" 
                            name="newCategoryName" 
                            value="<?= htmlspecialchars($category['name']) ?>" 
                            class="px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800"
                        >
                        <button type="submit" class="text-blue-500 hover:text-blue-700 ml-2">
                            <i class="fas fa-edit"></i> <!-- Icône de modification -->
                        </button>
                    </form>

                    <!-- Bouton pour supprimer la catégorie -->
                    <form action="delete-category.php" method="POST" class="inline">
                        <input type="hidden" name="categoryId" value="<?= $category['id'] ?>">
                        <button type="submit" class="text-red-500 hover:text-red-700 ml-2">
                            <i class="fas fa-trash"></i> <!-- Icône de suppression -->
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No categories found.</td>
        </tr>
    <?php endif; ?>
</tbody>
                    </table>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button onclick="closeCategoriesModal()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-500 text-base font-medium text-white hover:bg-red-600 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modale pour afficher tous les tags -->
<div id="tagsModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Fond sombre -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Contenu de la modale -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg font-bold mb-4">All Tags</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Updated At</th>
                            </tr>
                        </thead>
                        <tbody id="modalTagsList" class="divide-y divide-gray-200">
    <?php if (!empty($tags)): ?>
        <?php foreach ($tags as $tag): ?>
            <tr>
                <!-- Nom du tag -->
                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($tag['name']) ?></td>

                <!-- Date de création -->
                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($tag['created_at']) ?></td>

                <!-- Date de mise à jour -->
                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($tag['updated_at']) ?></td>

                <!-- Actions (Modifier et Supprimer) -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <!-- Formulaire pour modifier le tag -->
                    <form action="update-tag.php" method="POST" class="inline">
                        <input type="hidden" name="tagId" value="<?= $tag['id'] ?>">
                        <input 
                            type="text" 
                            name="newTagName" 
                            value="<?= htmlspecialchars($tag['name']) ?>" 
                            class="px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800"
                        >
                        <button type="submit" class="text-blue-500 hover:text-blue-700 ml-2">
                            <i class="fas fa-edit"></i> <!-- Icône de modification -->
                        </button>
                    </form>

                    <!-- Bouton pour supprimer le tag -->
                    <form action="delete-tag.php" method="POST" class="inline">
                        <input type="hidden" name="tagId" value="<?= $tag['id'] ?>">
                        <button type="submit" class="text-red-500 hover:text-red-700 ml-2">
                            <i class="fas fa-trash"></i> <!-- Icône de suppression -->
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No tags found.</td>
        </tr>
    <?php endif; ?>
</tbody>
                    </table>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button onclick="closeTagsModal()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-500 text-base font-medium text-white hover:bg-red-600 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

            <!-- Content Area -->
            <div class="lg:col-span-5">
                <!-- Recent Users Table -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
<div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-bold">Recent Users</h2>
    <button onclick="openModal()" class="text-red-500 hover:text-red-600">View All</button>
</div>
<!-- Modale pour afficher tous les utilisateurs -->
<div id="userModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Fond sombre -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>


        

        <!-- Contenu de la modale -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg font-bold mb-4">All Users</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($user['name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <!-- Formulaire pour mettre à jour le rôle -->
                                <form action="profileAdmin" method="POST" class="inline">
                                    <input type="hidden" name="userId" value="<?= $user['id'] ?>">
                                    <select 
                                        name="newRole" 
                                        class="px-2 py-1 text-xs leading-5 font-semibold rounded-full <?= $user['role'] === 'Admin' ? 'bg-purple-100 text-purple-800' : ($user['role'] === 'Instructor' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') ?>"
                                        onchange="this.form.submit()"
                                    >
                                       t
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <!-- Formulaire pour mettre à jour le statut -->
                                <form action="profileAdmin" method="POST" class="inline">
                                    <input type="hidden" name="userId" value="<?= $user['id'] ?>">
                                    <select 
                                        name="newStatus" 
                                        class="px-2 py-1 text-xs leading-5 font-semibold rounded-full <?= $user['status'] === 'Active' ? 'bg-green-100 text-green-800' : ($user['status'] === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>"
                                        onchange="this.form.submit()"
                                    >
                                        <option value="Active" <?= $user['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                                        <option value="Pending" <?= $user['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Suspended" <?= $user['status'] === 'Suspended' ? 'selected' : '' ?>>Suspended</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No recent users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button onclick="closeModal()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-500 text-base font-medium text-white hover:bg-red-600 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (!empty($recentUsers)): ?>
                    <?php foreach ($recentUsers as $user): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($user['name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <!-- Formulaire pour mettre à jour le rôle -->
                                <form action="profileAdmin" method="POST" class="inline">
                                    <input type="hidden" name="userId" value="<?= $user['id'] ?>">
                                    <select 
                                        name="newRole" 
                                        class="px-2 py-1 text-xs leading-5 font-semibold rounded-full <?= $user['role'] === 'Admin' ? 'bg-purple-100 text-purple-800' : ($user['role'] === 'Instructor' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') ?>"
                                        onchange="this.form.submit()"
                                    >
                                        <option value="Student" <?= $user['role'] === 'Student' ? 'selected' : '' ?>>Student</option>
                                        <option value="Instructor" <?= $user['role'] === 'Instructor' ? 'selected' : '' ?>>Instructor</option>
                                        <option value="Admin" <?= $user['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <!-- Formulaire pour mettre à jour le statut -->
                                <form action="profileAdmin" method="POST" class="inline">
                                    <input type="hidden" name="userId" value="<?= $user['id'] ?>">
                                    <select 
                                        name="newStatus" 
                                        class="px-2 py-1 text-xs leading-5 font-semibold rounded-full <?= $user['status'] === 'Active' ? 'bg-green-100 text-green-800' : ($user['status'] === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>"
                                        onchange="this.form.submit()"
                                    >
                                        <option value="Active" <?= $user['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                                        <option value="Pending" <?= $user['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Suspended" <?= $user['status'] === 'Suspended' ? 'selected' : '' ?>>Suspended</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No recent users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

                <!-- Recent Courses Table -->
                <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Recent Courses</h2>
                    <button onclick="openCourseModal()" class="text-red-500 hover:text-red-600">View All</button>
                </div>
                <!-- Modale pour afficher tous les cours -->
<div id="courseModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Fond sombre -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Contenu de la modale -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg font-bold mb-4">All Courses</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instructor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Students</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                                <?php if (!empty($courses)): ?>
                                    <?php foreach ($courses as $course): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($course['title']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($course['category']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($course['instructor']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($course['students']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                
                                                <button class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No recent courses found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button onclick="closeCourseModal()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-500 text-base font-medium text-white hover:bg-red-600 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instructor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Students</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php if (!empty($recentCourses)): ?>
                                    <?php foreach ($recentCourses as $course): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($course['title']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($course['category']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($course['instructor']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($course['students']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                
                                                <button class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No recent courses found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white rounded-md shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-12 md:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Contact Info -->
            <div class="space-y-4">
                <h3 class="text-xl font-bold mb-4">Contact</h3>
                <div class="flex items-center space-x-3">
                    <i data-lucide="mail" class="w-5 h-5 text-red-500"></i>
                    <a href="mailto:hello@Youdemy.com" class="hover:text-red-500 transition-colors">
                        hello@Youdemy.com
                    </a>
                </div>
            </div>

            <!-- Quick Links 1 -->
            <div>
                <h3 class="text-xl font-bold mb-4">Training</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="hover:text-red-500 transition-colors">Our Courses</a></li>
                    <li><a href="#" class="hover:text-red-500 transition-colors">Hackerspaces</a></li>
                    <li><a href="#" class="hover:text-red-500 transition-colors">Youdemy</a></li>
                </ul>
            </div>

            <!-- Quick Links 2 -->
            <div>
                <h3 class="text-xl font-bold mb-4">About</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="hover:text-red-500 transition-colors">Discover Youdemy</a></li>
                    <li><a href="#" class="hover:text-red-500 transition-colors">Careers</a></li>
                    <li><a href="#" class="hover:text-red-500 transition-colors">Youdemy Policies</a></li>
                </ul>
            </div>

            <!-- Social Media -->
            <div>
                <h3 class="text-xl font-bold mb-4">Follow Us</h3>
                <div class="flex space-x-4">
                    <a href="#" class="hover:text-red-500 transition-colors">
                        <i data-lucide="facebook" class="w-6 h-6"></i>
                    </a>
                    <a href="#" class="hover:text-red-500 transition-colors">
                        <i data-lucide="instagram" class="w-6 h-6"></i>
                    </a>
                    <a href="#" class="hover:text-red-500 transition-colors">
                        <i data-lucide="linkedin" class="w-6 h-6"></i>
                    </a>
                    <a href="#" class="hover:text-red-500 transition-colors">
                        <i data-lucide="twitter" class="w-6 h-6"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 mt-12 pt-8 text-center text-sm">
            <p> &copy; 2025 Youdemy. All rights reserved.</p>
        </div>
    </div>
</footer>
<script>
    // Fonction pour ouvrir la modale
    function openModal() {
        document.getElementById('userModal').classList.remove('hidden');
        loadAllUsers();
    }

    // Fonction pour fermer la modale
    function closeModal() {
        document.getElementById('userModal').classList.add('hidden');
    }

    // Fonction pour charger tous les utilisateurs
    function loadAllUsers() {
        fetch('/get-all-users.php') // Endpoint pour récupérer tous les utilisateurs
            .then(response => response.json())
            .then(data => {
                const userList = document.getElementById('modalUserList');
                userList.innerHTML = ''; // Vider la liste actuelle

                data.forEach(user => {
                    const row = `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">${user.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${user.email}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${user.role}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${user.status}</td>
                        </tr>
                    `;
                    userList.insertAdjacentHTML('beforeend', row);
                });
            })
            .catch(error => console.error('Error loading users:', error));
    }
</script>
<script>
    // Fonction pour ouvrir la modale des cours
    function openCourseModal() {
        document.getElementById('courseModal').classList.remove('hidden');
        loadAllCourses();
    }

    // Fonction pour fermer la modale des cours
    function closeCourseModal() {
        document.getElementById('courseModal').classList.add('hidden');
    }

    // Fonction pour charger tous les cours
    function loadAllCourses() {
        fetch('/get-all-courses.php') // Endpoint pour récupérer tous les cours
            .then(response => response.json())
            .then(data => {
                const courseList = document.getElementById('modalCourseList');
                courseList.innerHTML = ''; // Vider la liste actuelle

                data.forEach(course => {
                    const row = `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">${course.title}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${course.category}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${course.instructor}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${course.students}</td>
                        </tr>
                    `;
                    courseList.insertAdjacentHTML('beforeend', row);
                });
            })
            .catch(error => console.error('Error loading courses:', error));
    }
</script>
<script>
    // Fonction pour ouvrir la modale des catégories
    function openCategoriesModal() {
        document.getElementById('categoriesModal').classList.remove('hidden');
        loadAllCategories();
    }

    // Fonction pour fermer la modale des catégories
    function closeCategoriesModal() {
        document.getElementById('categoriesModal').classList.add('hidden');
    }

    // Fonction pour charger toutes les catégories
    function loadAllCategories() {
        fetch('/get-all-categories.php') // Endpoint pour récupérer toutes les catégories
            .then(response => response.json())
            .then(data => {
                const categoriesList = document.getElementById('modalCategoriesList');
                categoriesList.innerHTML = ''; // Vider la liste actuelle

                data.forEach(category => {
                    const row = `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">${category.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${category.created_at}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${category.updated_at}</td>
                        </tr>
                    `;
                    categoriesList.insertAdjacentHTML('beforeend', row);
                });
            })
            .catch(error => console.error('Error loading categories:', error));
    }

    // Fonction pour ouvrir la modale des tags
    function openTagsModal() {
        document.getElementById('tagsModal').classList.remove('hidden');
        loadAllTags();
    }

    // Fonction pour fermer la modale des tags
    function closeTagsModal() {
        document.getElementById('tagsModal').classList.add('hidden');
    }

    // Fonction pour charger tous les tags
    function loadAllTags() {
        fetch('/get-all-tags.php') // Endpoint pour récupérer tous les tags
            .then(response => response.json())
            .then(data => {
                const tagsList = document.getElementById('modalTagsList');
                tagsList.innerHTML = ''; // Vider la liste actuelle

                data.forEach(tag => {
                    const row = `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">${tag.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${tag.created_at}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${tag.updated_at}</td>
                        </tr>
                    `;
                    tagsList.insertAdjacentHTML('beforeend', row);
                });
            })
            .catch(error => console.error('Error loading tags:', error));
    }
</script>
</body>
</html>
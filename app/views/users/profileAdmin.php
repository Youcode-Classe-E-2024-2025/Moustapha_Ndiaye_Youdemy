<?php
// Inclut les dépendances nécessaires
require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../models/Admin.php';
require_once __DIR__ . '/../../controllers/AdminController.php';


// Create instance for Database
$database = Database::getInstance();

// Récupère l'objet PDO
$pdo = $database->getPdo();

// Create instance for AdminController
$adminController = new AdminController($pdo);

// Get datas
try {
    $statistics = $adminController->getGlobalStatistics();
    $recentUsers = $adminController->getRecentUsers();
    $recentCourses = $adminController->getRecentCourses();
} catch (Exception $e) {
    // require __DIR__ . '/../views/errors/500.php';
    exit;
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
    <header class="">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <div class="flex text-3xl font-bold text-white bg-slate-300 rounded-sm">
                <div class="bg-red-500  px-1 rounded-md">You</div>
                <div class="">demy</div>
            </div>
            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">admin@youdemy.com</span>
                <a href="#" class="text-red-500 hover:text-red-600">
                    <i class="fas fa-sign-out-alt"></i> Logout
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
                        <a href="#" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-users mr-2"></i> Users
                        </a>
                        <a href="#" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-book mr-2"></i> Courses
                        </a>
                        <a href="#" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-tags mr-2"></i> Categories
                        </a>
                        <a href="#" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-hashtag mr-2"></i> Tags
                        </a>
                        <a href="#" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-question-circle mr-2"></i> Quizzes
                        </a>
                        <a href="#" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-chart-line mr-2"></i> Statistics
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-5">
                <!-- Recent Users Table -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Recent Users</h2>
                        <a href="#" class="text-red-500 hover:text-red-600">View All</a>
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
                                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($user['role']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $user['status'] === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                    <?= htmlspecialchars($user['status']) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button class="text-blue-500 hover:text-blue-700 mr-2">
                                                    <i class="fas fa-edit"></i>
                                                </button>
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
                        <a href="#" class="text-red-500 hover:text-red-600">View All</a>
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
                                                <button class="text-blue-500 hover:text-blue-700 mr-2">
                                                    <i class="fas fa-edit"></i>
                                                </button>
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
</body>
</html>
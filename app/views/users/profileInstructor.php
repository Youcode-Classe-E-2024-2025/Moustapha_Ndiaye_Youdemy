<?php
session_start();
// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit();
}
$user = $_SESSION['user'];

// Inclure le contrôleur
require_once __DIR__ . '/../../controllers/CourseController.php';
$courseController = new CourseController();

// Récupérer les données dynamiques
$stats = $courseController->getCourseStatistics($user['id']);
$recentCourses = $courseController->getRecentCourses($user['id']);
$recentStudents = $courseController->getRecentStudents($user['id']);
$categories = $courseController->getCategories(); // Récupérer les catégories pour le formulaire
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- SimpleMDE Markdown Editor CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
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
        <!-- Messages de succès ou d'erreur -->
        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_GET['success']) ?></span>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_GET['error']) ?></span>
            </div>
        <?php endif; ?>

        <!-- Statistiques du tableau de bord -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total des cours -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Total Courses</h3>
                <p class="text-3xl font-bold text-red-500"><?= htmlspecialchars($stats['total_courses']) ?></p>
            </div>
            <!-- Total des étudiants -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Total Students</h3>
                <p class="text-3xl font-bold text-red-500"><?= htmlspecialchars($stats['total_students']) ?></p>
            </div>
            <!-- Taux d'engagement -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Engagement Rate</h3>
                <p class="text-3xl font-bold text-red-500"><?= htmlspecialchars($stats['engagement_rate']) ?>%</p>
            </div>
        </div>

        <!-- Button to Open Modal -->
        <button onclick="openModal()" class="mt-4 ml-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            <i class="fas fa-plus mr-2"></i> Add Course
        </button>

        <!-- Modal -->
        <div id="addCourseModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background -->
                <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeModal()"></div>
                <!-- Content -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Add New Course</h3>
                        <form method="POST" action="/save-course" class="mt-4 space-y-4" enctype="multipart/form-data">
                            <!-- Form fields (title, category, description, content, thumbnail) -->
                            <textarea id="content" name="content" rows="10"></textarea>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cours récents et étudiants récents -->
        <div class="grid grid-cols-1 lg:grid-cols-6 gap-6">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <nav class="space-y-2">
                        <a href="#" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                        <a href="#" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-book mr-2"></i> Courses
                        </a>
                        <a href="#" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-users mr-2"></i> Students
                        </a>
                        <a href="#" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-chart-line mr-2"></i> Statistics
                        </a>
                        <a href="#" class="block px-4 py-2 rounded hover:bg-red-50 hover:text-red-500 transition-colors">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Zone de contenu -->
            <div class="lg:col-span-5">
                <!-- Tableau des cours récents -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Students</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($recentCourses as $course): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($course['title']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($course['category']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($course['students']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="/edit-course/<?= htmlspecialchars($course['id']) ?>" class="text-blue-500 hover:text-blue-700 mr-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="/delete-course" class="inline">
                                                <input type="hidden" name="course_id" value="<?= htmlspecialchars($course['id']) ?>">
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tableau des étudiants récents -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Recent Students</h2>
                        <a href="#" class="text-red-500 hover:text-red-600">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($recentStudents as $student): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($student['name']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($student['email']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($student['progress']) ?>%</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="/view-student/<?= htmlspecialchars($student['id']) ?>" class="text-blue-500 hover:text-blue-700 mr-2">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form method="POST" action="/delete-student" class="inline">
                                                <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['id']) ?>">
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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

                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Training</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:text-red-500 transition-colors">Our Courses</a></li>
                        <li><a href="#" class="hover:text-red-500 transition-colors">Hackerspaces</a></li>
                        <li><a href="#" class="hover:text-red-500 transition-colors">Youdemy</a></li>
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

    <!-- SimpleMDE Markdown Editor JS -->
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <script>
        // Initialize Markdown Editor
        const simplemde = new SimpleMDE({
            element: document.getElementById("content"),
            spellChecker: false,
            toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "|", "preview", "guide"],
        });

        // Modal Handling
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('addCourseModal');

            function openModal() {
                console.log("Opening modal..."); // Debugging
                modal.classList.remove('hidden'); // Remove the 'hidden' class
            }

            function closeModal() {
                modal.classList.add('hidden'); // Add the 'hidden' class
            }

            // Close modal when clicking outside
            window.onclick = function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            };

            // Expose openModal to the global scope
            window.openModal = openModal;
        });
    </script>
</body>
</html>
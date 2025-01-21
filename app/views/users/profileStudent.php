<?php
session_start();
// Redirect to login page if users if not connected
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit();
}
$user = $_SESSION['user'];

// check user role
if ($user['role'] !== 'Student') {
    header('Location: /unauthorized');
    exit();
}

// Initialize controller
require_once __DIR__ . '/../../controllers/CourseController.php';
$studentController = new CourseController();

// fecth dynamic datas
$courses = $studentController->getAllCourses();
$categories = $studentController->getCategories();

// Filter courses by categories
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $courses = $studentController->searchCourses($searchTerm);
}

if (isset($_GET['category'])) {
    $category = $_GET['category'];
    $courses = $studentController->getCoursesByCategory($category);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
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
                <span class="text-gray-600"><?= htmlspecialchars($user['email']) ?></span>
                <a href="/logout" class="text-red-500 hover:text-red-600">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8">
        <!-- Search Bar -->
        <div class="mb-8">
            <form method="GET" action="">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Rechercher un cours..." 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500"
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                >
            </form>
        </div>

        <!-- Filters -->
        <div class="sticky top-0 z-50 p-4 bg-white shadow-md mb-8">
            <div class="flex flex-wrap gap-4 justify-center">
                <!-- <a href="?category=all" class="px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors">
                    All
                </a> -->
                <?php foreach ($categories as $category): ?>
                    <a href="?category=<?= urlencode($category) ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-red-500 hover:text-white transition-colors">
                        <?= htmlspecialchars($category) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-transform hover:scale-105">
                        <div class="h-48 overflow-hidden">
                            <img 
                                src="<?= htmlspecialchars($course['image_uri']) ?>" 
                                alt="<?= htmlspecialchars($course['title']) ?>" 
                                class="w-full h-full object-cover"
                            >
                        </div>
                        <div class="p-6 space-y-4">
                            <h3 class="text-xl font-bold text-gray-800">
                                <?= htmlspecialchars($course['title']) ?>
                            </h3>
                            <p class="text-gray-600">
                                <?= htmlspecialchars($course['description']) ?>
                            </p>
                            <a href="/course/<?= htmlspecialchars($course['id']) ?>" class="text-red-500 font-semibold hover:text-red-600 transition-colors">
                                Enroll Now →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-600">Aucun cours disponible.</p>
                </div>
            <?php endif; ?>
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

    <script src="../js/profilStudent.js"></script>
</body>
</html>
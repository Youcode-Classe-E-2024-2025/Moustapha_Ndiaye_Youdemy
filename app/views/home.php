<?php

// Initialiser le contrôleur
require_once __DIR__ . '/../controllers/CourseController.php';
$studentController = new CourseController();

// Récupérer les données dynamiques
$courses = $studentController->getAllCourses();
$categories = $studentController->getCategories();

// Filtrer les cours si une recherche ou une catégorie est spécifiée
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
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/tailwind.css">
    <title>HomeUser</title>
</head>
<body class="bg-gray-200">
    <header>
    <nav class="p-4">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <div class="flex text-3xl font-bold text-white bg-slate-300 rounded-sm">
                <div class="bg-red-500  px-1 rounded-md">You</div>
                <div class="">demy</div>
            </div>

            <!-- Menu Burger (visible sur mobile) -->
            <button id="burger-btn" class="md:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Menu Desktop -->
        <div class="hidden md:flex space-x-4 text-2xl font-bold">
            <a href="/login" class="border border-black px-4 py-1 rounded-md hover:bg-red-50 transition-colors">
                Sign In
            </a>
            <a href="/register" class="text-red-500 border border-red-500 px-4 py-1 rounded-md hover:bg-red-50 transition-colors">
                Sign Up
            </a>
        </div>

            <!-- Menu Mobile -->
            <div id="mobile-menu" class="hidden fixed top-16 left-0 right-0 bg-white p-4 shadow-sm md:hidden">
                <div class="flex flex-col space-y-4 text-xl  text-center">
                    <div class="hover:text-gray-700 cursor-pointer hover:bg-red-50 ">Sign In</div>
                    <div class="text-red-500 px-2 rounded-md hover:bg-red-50 cursor-pointer">Sign Up</div>
                </div>
            </div>
        </div>
    </nav>
    </header>
    <!-- Cover -->
    <section class="p-4 md:p-8 lg:p-12">
        <div class="flex flex-col md:flex-row bg-white rounded-md shadow-md overflow-hidden">
            <!-- Texte -->
            <div class="p-6 md:p-10 space-y-5 md:w-1/2" data-aos="fade-right" data-aos-duration="1000">
                <h1 class="text-xl md:text-2xl lg:text-3xl font-bold leading-tight">
                    Building modern applications for the future with <span class="text-red-500">US</span>.<br class="hidden md:block">Get digital
                </h1>
                <button class="text-xl md:text-2xl bg-red-500 hover:bg-red-600 transition-colors rounded-md p-3 text-white">
                    Get Started
                </button>
            </div>
            
            <!-- Image -->
            <div class="md:w-1/2" data-aos="fade-left" data-aos-duration="1000">
                <img 
                    src="/images/Illustration1.png" 
                    alt="cover" 
                    class="w-full h-full object-cover"
                >
            </div>
        </div>
        <section class="py-10">
        <div class="flex flex-col md:flex-row bg-white rounded-md shadow-md overflow-hidden">
            <!-- Learners -->
            <div class="flex flex-col items-center p-6 md:p-8 text-center flex-1">
                <i data-lucide="users" class="w-10 h-10 mb-4 text-red-500"></i>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">390+</h1>
                <p class="text-gray-600 text-lg">Learners</p>
            </div>

            <!-- Divider -->
            <div class="w-full md:w-px h-px md:h-32 bg-gray-400 mx-auto"></div>

            <!-- Teachers -->
            <div class="flex flex-col items-center p-6 md:p-8 text-center flex-1">
                <i data-lucide="graduation-cap" class="w-10 h-10 mb-4 text-red-500"></i>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">390+</h1>
                <p class="text-gray-600 text-lg">Teachers</p>
            </div>

            <!-- Divider -->
            <div class="w-full md:w-px h-px md:h-32 bg-gray-400 mx-auto"></div>

            <!-- Courses -->
            <div class="flex flex-col items-center p-6 md:p-8 text-center flex-1">
                <i data-lucide="book-open" class="w-10 h-10 mb-4 text-red-500"></i>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">390+</h1>
                <p class="text-gray-600 text-lg">Courses</p>
            </div>
        </div>
    </section>
    <!--  -->
    <section class="p-4 md:p-8 lg:p-12">
        <div class="max-w-4xl mx-auto text-center space-y-6">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight bg-gradient-to-r from-red-500 to-red-700 bg-clip-text text-transparent">
                Join the training that meets your needs
            </h1>
            
            <p class="text-lg md:text-xl text-gray-600 leading-relaxed max-w-3xl mx-auto">
                Whether you want to learn about a field, accelerate your career with international certification, or change careers, we have the training made for you.
            </p>
        </div>
    </section>
    <!-- Courses -->
    <section class="p-4 md:p-8 lg:p-12 bg-gray-50 rounded-md">
         <!-- filters -->
    <div class="sticky top-0 z-50 p-4 ">
        <div class="max-w-7xl mx-auto flex flex-wrap gap-4 justify-center">
            <button data-category="all" class="px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors">
                All
            </button>
            <button data-category="full-stack" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-red-500 hover:text-white transition-colors">
                Full Stack
            </button>
            <button data-category="data-science" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-red-500 hover:text-white transition-colors">
                Data Science
            </button>
            <button data-category="devops" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-red-500 hover:text-white transition-colors">
                DevOps
            </button>
            <button data-category="mobile" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-red-500 hover:text-white transition-colors">
                Mobile
            </button>
            <button data-category="cybersecurity" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-red-500 hover:text-white transition-colors">
                Cybersecurity
            </button>
            <button data-category="ui-ux" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-red-500 hover:text-white transition-colors">
                UI/UX
            </button>
            <button data-category="ai" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-red-500 hover:text-white transition-colors">
                AI
            </button>
            <button data-category="game-dev" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-red-500 hover:text-white transition-colors">
                Game Dev
            </button>
            <button data-category="blockchain" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-red-500 hover:text-white transition-colors">
                Blockchain
            </button>
            <button data-category="qa" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-red-500 hover:text-white transition-colors">
                QA Testing
            </button>
        </div>
    </div>
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 py-5">
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
                    <button class="text-red-500 font-semibold hover:text-red-600 transition-colors">
                        Learn more →
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-span-full text-center py-8">
            <p class="text-gray-600">Aucun cours disponible.</p>
        </div>
    <?php endif; ?>
</div>

</section>
    <section class="py-8">
    <div class="flex flex-col md:flex-row bg-white rounded-md shadow-md overflow-hidden">
            <!-- Texte -->
            <div class="p-6 md:p-10 space-y-5 md:w-1/2" data-aos="fade-right" data-aos-duration="1000">
                <h1 class="text-xl md:text-2xl lg:text-3xl font-bold leading-tight">
                    Here we <span class="text-red-500">ARE</span>
                </h1>
                
            </div>
            
            <!-- Image -->
            <div class="md:w-1/2" data-aos="fade-left" data-aos-duration="1000">
                <img 
                    src="/images/HugeGlobal.svg" 
                    alt="cover" 
                    class="w-full h-full object-cover"
                >
            </div>
        </div>
    </section>
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
    <script src="/js/main.js"></script>
</body>
</html>
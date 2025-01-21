<?php
session_start();
// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit();
}
$user = $_SESSION['user'];

// Exemple de données de cours (à remplacer par une requête SQL réelle)
$courses = [
    [
        'title' => 'Introduction to Web Development',
        'description' => 'Apprenez les bases du développement web avec HTML, CSS et JavaScript.',
        'image_uri' => 'https://via.placeholder.com/400x200',
        'category' => 'Full Stack'
    ],
    [
        'title' => 'Data Science Fundamentals',
        'description' => 'Découvrez les concepts de base de la science des données et du machine learning.',
        'image_uri' => 'https://via.placeholder.com/400x200',
        'category' => 'Data Science'
    ],
    [
        'title' => 'DevOps Essentials',
        'description' => 'Maîtrisez les outils et pratiques DevOps pour améliorer votre workflow.',
        'image_uri' => 'https://via.placeholder.com/400x200',
        'category' => 'DevOps'
    ],
    // Ajoutez d'autres cours ici
];
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
                <span class="text-gray-600"><?php echo htmlspecialchars($user['email']); ?></span>
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
            <input 
                type="text" 
                id="searchInput" 
                placeholder="Rechercher un cours..." 
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500"
            >
        </div>

        <!-- Filters -->
        <div class="sticky top-0 z-50 p-4 bg-white shadow-md mb-8">
            <div class="flex flex-wrap gap-4 justify-center">
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
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow mt-8">
        <div class="container mx-auto px-6 py-4 text-center text-gray-600">
            &copy; 2025 Youdemy. All rights reserved.
        </div>
    </footer>

    <!-- JavaScript pour le filtrage des cours et la recherche -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('[data-category]');
            const courses = document.querySelectorAll('.bg-white.rounded-xl');
            const searchInput = document.getElementById('searchInput');

            // Fonction pour filtrer les cours
            function filterCourses() {
                const searchTerm = searchInput.value.toLowerCase();
                const activeCategory = document.querySelector('[data-category].bg-red-500')?.getAttribute('data-category') || 'all';

                courses.forEach(course => {
                    const title = course.querySelector('h3').textContent.toLowerCase();
                    const description = course.querySelector('p').textContent.toLowerCase();
                    const category = course.querySelector('p').textContent.toLowerCase();

                    const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
                    const matchesCategory = activeCategory === 'all' || category.includes(activeCategory);

                    if (matchesSearch && matchesCategory) {
                        course.style.display = 'block';
                    } else {
                        course.style.display = 'none';
                    }
                });
            }

            // Gestion des clics sur les boutons de catégorie
            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    buttons.forEach(btn => {
                        btn.classList.remove('bg-red-500', 'text-white');
                        btn.classList.add('bg-gray-200', 'text-gray-700');
                    });

                    button.classList.remove('bg-gray-200', 'text-gray-700');
                    button.classList.add('bg-red-500', 'text-white');

                    filterCourses();
                });
            });

            // Gestion de la recherche
            searchInput.addEventListener('input', filterCourses);
        });
    </script>
</body>
</html>
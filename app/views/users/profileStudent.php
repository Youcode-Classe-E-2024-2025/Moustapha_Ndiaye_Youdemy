<?php
session_start();

// Redirige vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit();
}

$user = $_SESSION['user'];

// Vérifie le rôle de l'utilisateur
if ($user['role'] !== 'Student') {
    header('Location: /unauthorized');
    exit();
}

// Initialise le contrôleur
require_once __DIR__ . '/../../controllers/CourseController.php';
$studentController = new CourseController();

// Récupère l'ID de l'étudiant depuis la session
$studentId = $user['id'];
$errors = [];

// Récupère les cours auxquels l'étudiant est inscrit
try {
    $studentEnroll = $studentController->getAllCoursesByUsers($studentId);
} catch (Exception $e) {
    $errors[] = "Erreur lors de la récupération des cours : " . $e->getMessage();
}

// Récupère tous les cours et catégories
try {
    $courses = $studentController->getAllCourses();
    $categories = $studentController->getCategories();

    // Filtrage des cours
    if (isset($_GET['search'])) {
        $searchTerm = $_GET['search'];
        $courses = $studentController->searchCourses($searchTerm);
    } elseif (isset($_GET['category'])) {
        $category = $_GET['category'];
        $courses = $studentController->getCoursesByCategory($category);
    } elseif (isset($_GET['mycourses']) && $_GET['mycourses'] === 'true') {
        $courses = $studentEnroll;
    }
} catch (Exception $e) {
    $errors[] = "Erreur lors de la récupération des données : " . $e->getMessage();
}

// Gestion de l'inscription à un cours
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseId = $_POST['course_id'] ?? null;
    $studentId = $_POST['student_id'] ?? null;

    if (empty($courseId) || empty($studentId)) {
        $errors[] = "L'ID du cours et l'ID de l'étudiant sont requis.";
    } else {
        try {
            $message = $studentController->enrollCourse((int)$courseId, (int)$studentId);
            header('Location: /profileStudent?success=' . urlencode($message));
            exit();
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Youdemy</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
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
    <main class="flex-grow container mx-auto px-6 py-8">
        <!-- Navigation Tabs -->
        <div class="flex justify-center mb-8 border-b">
            <a href="/profileStudent" 
               class="<?= !isset($_GET['mycourses']) ? 'border-b-2 border-red-500' : '' ?> px-6 py-3 text-gray-700 hover:text-red-500">
                All Courses
            </a>
            <a href="?mycourses=true" 
               class="<?= isset($_GET['mycourses']) ? 'border-b-2 border-red-500' : '' ?> px-6 py-3 text-gray-700 hover:text-red-500">
                My Courses
            </a>
        </div>

        <!-- Search and Filters Section -->
        <section class="mb-8 space-y-6">
            <!-- Search Bar -->
            <form method="GET" action="" class="max-w-2xl mx-auto">
                <?php if (isset($_GET['mycourses'])): ?>
                    <input type="hidden" name="mycourses" value="true">
                <?php endif; ?>
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search for courses..." 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 pl-10"
                        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                    >
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </form>

            <!-- Category Filters -->
            <?php if (!isset($_GET['mycourses'])): ?>
                <div class="flex flex-wrap gap-3 justify-center">
                    <?php foreach ($categories as $category): ?>
                        <a href="?category=<?= htmlspecialchars(urlencode($category)) ?>" 
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-red-500 hover:text-white transition-colors">
                            <?= htmlspecialchars($category) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Alerts Section -->
        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p class="font-medium">Success!</p>
                <p><?= htmlspecialchars($_GET['success']) ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-medium">Error</p>
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Courses Grid -->
        <section>
            <h2 class="text-2xl font-bold mb-6">
                <?= isset($_GET['mycourses']) ? 'My Enrolled Courses' : 'Available Courses' ?>
            </h2>
            <?php if (!empty($courses)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($courses as $course): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                <div class="relative h-48">
                    <img src="<?= htmlspecialchars($course['image_uri'] ?? '/path/to/default/image.jpg') ?>"
                         alt="<?= htmlspecialchars($course['title'] ?? 'Course Image') ?>"
                         class="w-full h-full object-cover"
                         loading="lazy">
                    <?php if (isset($_GET['mycourses'])): ?>
                        <div class="absolute top-2 right-2 bg-green-500 text-white px-3 py-1 rounded-full text-sm">
                            Enrolled
                        </div>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">
                        <?= htmlspecialchars($course['title'] ?? 'Titre non disponible') ?>
                    </h3>
                    <p class="text-gray-600 line-clamp-3 mb-4">
                        <?= htmlspecialchars($course['description'] ?? 'Description non disponible') ?>
                    </p>
                    <?php if (!isset($_GET['mycourses'])): ?>
                        <form method="POST" action="/profileStudent" class="inline">
                            <input type="hidden" name="course_id" value="<?= htmlspecialchars($course['id'] ?? '') ?>">
                            <input type="hidden" name="student_id" value="<?= htmlspecialchars($user['id'] ?? '') ?>">
                            <button type="submit" 
                                    class="inline-flex items-center text-red-500 font-semibold hover:text-red-600">
                                Enroll Now
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="/course/<?= htmlspecialchars($course['id'] ?? '') ?>" 
                           class="inline-flex items-center text-red-500 font-semibold hover:text-red-600">
                            Continue Learning
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center py-12 bg-white rounded-lg shadow">
        <i class="fas fa-book-open text-4xl text-gray-400 mb-4"></i>
        <p class="text-gray-600">
            <?= isset($_GET['mycourses']) ? 
                "You haven't enrolled in any courses yet." : 
                "No courses available at the moment." ?>
        </p>
    </div>
<?php endif; ?>
        </section>
    </main>

    <!-- Footer reste inchangé -->
    <footer class="bg-white mt-12 shadow-md">
        <!-- ... (votre code footer existant) ... -->
    </footer>
</body>
</html>
<?php
session_start();
// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit();
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
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
        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Total Courses</h3>
                <p class="text-3xl font-bold text-red-500">12</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Total Students</h3>
                <p class="text-3xl font-bold text-red-500">345</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Engagement Rate</h3>
                <p class="text-3xl font-bold text-red-500">78%</p>
            </div>
        </div>

        <!-- Recent Courses and Students -->
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

            <!-- Content Area -->
            <div class="lg:col-span-5">
                <!-- Recent Courses Table -->
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
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">Introduction to Web Development</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Development</td>
                                    <td class="px-6 py-4 whitespace-nowrap">42</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button class="text-blue-500 hover:text-blue-700 mr-2">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Repeat rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Students Table -->
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
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">John Doe</td>
                                    <td class="px-6 py-4 whitespace-nowrap">john@example.com</td>
                                    <td class="px-6 py-4 whitespace-nowrap">75%</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button class="text-blue-500 hover:text-blue-700 mr-2">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Repeat rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow mt-8">
        <div class="container mx-auto px-6 py-4 text-center text-gray-600">
            &copy; 2025 Youdemy. All rights reserved.
        </div>
    </footer>
</body>
</html>
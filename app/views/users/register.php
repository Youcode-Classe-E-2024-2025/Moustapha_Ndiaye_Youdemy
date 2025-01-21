<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// // Dans register.php
require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../controllers/UserController.php';

// // Créez une instance de Database
// // $database = new Database();
$database = Database::getInstance();

// // Récupérez l'objet PDO
$pdo = $database->getPdo();

// // Créez une instance de UserController en passant l'objet PDO
// $userController = new UserController($pdo);

// // Appelez la méthode createAccount si le formulaire est soumis
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $userController->createAccount();
// }


// Create an instance of UserController
$userController = new UserController($pdo);

// Form handling
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $role = $_POST['role'] ?? '';

    // Call the createAccount method
    $result = $userController->createAccount([
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'confirmPassword' => $confirmPassword,
        'role' => $role
    ]);

    if ($result['success']) {
        header('Location: /login'); // Redirect to the login page
        exit();
    } else {
        $errors = $result['errors'];
    }
}
// Display errors
// if (!empty($errors)) {
//     foreach ($errors as $error) {
//         echo "<div class='error'>$error</div>";
//     }
// }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/tailwind.css">
    <title>Register</title>
</head>
<body class="bg-gray-200 min-h-screen flex items-center justify-center p-6">
    
<section class="p-4 md:p-8 lg:p-12 min-h-screen flex items-center justify-center">
    <div class="flex flex-col md:flex-row bg-white rounded-lg shadow-lg overflow-hidden max-w-4xl w-full space-x-10">
         <!-- Image -->
         <div class="md:w-1/2" data-aos="fade-left" data-aos-duration="1000">
                <img 
                    src="/images/Illustration2.png" 
                    alt="cover" 
                    class="h-full "
                >
            </div>
        <!-- Form Section -->
        <div class="md:w-1/2 p-8">
            <!-- Header -->
            <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-800">Create an Account</h2>
            <p class="mt-2 text-gray-600">Register to get started</p>
            </div>

            <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
           <!-- Register Form -->
           <form id="registrationForm" action="/register" method="POST">
    <!-- Full Name -->
    <div class="relative mb-6">
        <input
            type="text"
            id="name"
            name="name" 
            pattern="[A-Za-z\s]{2,}"
            class="form-input peer"
            placeholder=" "
            required />
        <label for="name" class="form-label">Full Name</label>
        <span id="nameError" class="text-sm text-red-500 hidden">Please enter a valid name (letters and spaces only, minimum 2 characters).</span>
    </div>

    <!-- Email -->
    <div class="relative mb-6">
        <input
            type="email"
            name="email"
            id="email"
            class="form-input peer"
            placeholder=" "
            required />
        <label for="email" class="form-label">Email</label>
        <span id="emailError" class="text-sm text-red-500 hidden">Please enter a valid email address.</span>
    </div>

    <!-- Password -->
    <div class="relative mb-6">
        <input
            name="password" 
            type="password" 
            id="password"
            class="form-input peer"
            placeholder=" "
            required
            minlength="8" />
        <label for="password" class="form-label">Password</label>
        <span id="passwordError" class="text-sm text-red-500 hidden">Password must be at least 8 characters long.</span>
    </div>

    <!-- Confirm Password -->
    <div class="relative mb-6">
        <input
            name="confirmPassword" 
            type="password" 
            id="confirmPassword"
            class="form-input peer"
            placeholder=" "
            required />
        <label for="confirmPassword" class="form-label">Confirm Password</label>
        <span id="confirmPasswordError" class="text-sm text-red-500 hidden">Passwords do not match.</span>
    </div>

    <!-- Role Selection -->
    <div class="relative mb-6">
        <select
            name="role" 
            id="role"
            class="form-input peer"
            required>
            <option value="" disabled selected>Select your role</option>
            <option value="student">Student</option>
            <option value="instructor">Instructor</option>
        </select>
        <label for="role" class="form-label">Role</label>
        <span id="roleError" class="text-sm text-red-500 hidden">Please select a role.</span>
    </div>

    <!-- Submit Button -->
    <div class="text-center lg:text-left">
        <button
            type="submit"
            class="inline-block w-full rounded bg-red-500 px-7 pb-2 pt-3 text-sm font-medium uppercase leading-normal text-white"
            data-twe-ripple-init
            data-twe-ripple-color="light">
            Register
        </button>
    </div>
</form>
            <!-- Additional Links -->
            <div class="mt-6 space-y-2">
                <p class="text-center text-sm text-gray-600">
                    Already registred?
                    <a href="login" class="font-medium text-red-500 hover:text-red-600 transition-colors hover:underline">
                        Sign in
                    </a>
                </p>
                <p class="text-center text-sm">
                    <a href="/" class="font-medium text-red-500 hover:text-red-600 transition-colors hover:underline">
                        Return Home
                    </a>
                </p>
            </div>
        </div>
    </div>
</section>
     

<script src="../js/register.js"></script>

</body>
</html>
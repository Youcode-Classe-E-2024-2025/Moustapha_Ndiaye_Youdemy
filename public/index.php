<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load Composer dependencies
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/controllers/UserController.php';


// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

require __DIR__ . '/../app/core/Database.php';
$db = new Database();
//
$userController = new UserController($db);
$userController->createAccount();



$request_URI = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function safeRequire($path) {
    if (file_exists($path)) {
        // require_once $path;
        require __DIR__ . "/$path";
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Page not found";
        exit();
    }
}



switch ($request_URI) {
    case '/':
    case '/home':
        safeRequire('../app/views/home.php');
        break;
    case '/login':
        safeRequire('../app/views/users/login.php');
        break;
    case '/register':
        safeRequire('../app/views/users/register.php');
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        echo "Page not found";
        break;
}
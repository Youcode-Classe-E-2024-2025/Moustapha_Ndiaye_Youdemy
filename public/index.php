<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load Composer dependencies
require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Connect to the database
require __DIR__ . '/../app/core/Database.php';
$db = new Database();
require __DIR__ . '/../app/core/Router.php';

// Use the correct namespace for the Router class
use App\Core\Router;

// Create a router instance
$router = new Router();

// Handle the request
$router->dispatch($_SERVER['REQUEST_URI']);

// Load the routes
require __DIR__ . '/../app/config/routes.php';
//echo "URI demandée : " . $_SERVER['REQUEST_URI'];

// $router->debugRoutes();
// $router->dispatch($_SERVER['REQUEST_URI']);

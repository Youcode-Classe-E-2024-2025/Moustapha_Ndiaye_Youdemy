<?php
use App\Core\Router;

// Create a router instance
// $router = new Router();

// Define routes

$router->addRoute('GET', '/', 'HomeController@index');
$router->addRoute('GET', '/login', 'UserController@login');
$router->addRoute('POST', '/login', 'UserController@login');
$router->addRoute('GET', '/register', 'UserController@register');
$router->addRoute('POST', '/register', 'UserController@register');
$router->addRoute('GET', '/profile', 'UserController@profile');
$router->addRoute('GET', '/courses', 'CourseController@listCourses');
$router->addRoute('GET', '/courses/{id}', 'CourseController@showCourse');
$router->addRoute('POST', '/courses', 'CourseController@createCourse');
$router->addRoute('GET', '/admin', 'AdminController@dashboard');

// Dispatch request
$router->dispatch($_SERVER['REQUEST_URI']);
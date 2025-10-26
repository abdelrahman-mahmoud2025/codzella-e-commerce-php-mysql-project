<?php

// Start session
session_start();

// Load configuration
require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/../config/database.php';

if (!defined('APP_URL') || APP_URL === '') {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443) ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    $basePath = ($basePath === '/' ? '' : $basePath);
    define('APP_URL', $scheme . '://' . $host . $basePath);
}

// Autoload helpers
require_once __DIR__ . '/../app/helpers/SessionHelper.php';
require_once __DIR__ . '/../app/helpers/ValidationHelper.php';
require_once __DIR__ . '/../app/helpers/CartHelper.php';
require_once __DIR__ . '/../app/helpers/StripeHelper.php';

// Autoload models
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/Category.php';
require_once __DIR__ . '/../app/models/Product.php';
require_once __DIR__ . '/../app/models/Order.php';

// Autoload controllers
require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/ProductController.php';
require_once __DIR__ . '/../app/controllers/CartController.php';
require_once __DIR__ . '/../app/controllers/OrderController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';

// Get URL from query string
// Support both .htaccess (index.php?url=...) and direct PHP server routing (REQUEST_URI)
if (isset($_GET['url']) && $_GET['url'] !== '') {
    $rawUrl = rtrim($_GET['url'], '/');
} else {
    // Derive path from REQUEST_URI by removing the script base directory (e.g., /codezilla-store/public)
    $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    if ($basePath !== '' && $basePath !== '/' && strpos($requestPath, $basePath) === 0) {
        $requestPath = substr($requestPath, strlen($basePath));
    }
    $rawUrl = ltrim($requestPath, '/');
}

$rawUrl = filter_var($rawUrl, FILTER_SANITIZE_URL);
$url = $rawUrl === '' ? [] : explode('/', $rawUrl);

// Default route
$controller = 'HomeController';
$method = 'index';
$params = [];

// Route mapping
if (!empty($url[0])) {
    switch ($url[0]) {
        case '':
        case 'home':
            $controller = 'HomeController';
            $method = 'index';
            break;
            
        case 'login':
            $controller = 'AuthController';
            $method = 'login';
            break;
            
        case 'register':
            $controller = 'AuthController';
            $method = 'register';
            break;
            
        case 'logout':
            $controller = 'AuthController';
            $method = 'logout';
            break;
            
        case 'products':
            $controller = 'ProductController';
            $method = isset($url[1]) ? $url[1] : 'index';
            $params = array_slice($url, 2);
            break;
            
        case 'cart':
            $controller = 'CartController';
            $method = isset($url[1]) ? $url[1] : 'index';
            $params = array_slice($url, 2);
            break;
            
        case 'checkout':
            $controller = 'OrderController';
            $method = 'checkout';
            break;
            
        case 'orders':
            $controller = 'OrderController';
            $method = isset($url[1]) ? $url[1] : 'index';
            $params = array_slice($url, 2);
            break;
            
        case 'admin':
            $controller = 'AdminController';
            $method = isset($url[1]) ? $url[1] : 'dashboard';
            $params = array_slice($url, 2);
            break;
            
        default:
            $controller = 'HomeController';
            $method = 'notFound';
            break;
    }
}

// Instantiate controller and call method
try {
    $controllerInstance = new $controller();
    
    if (method_exists($controllerInstance, $method)) {
        call_user_func_array([$controllerInstance, $method], $params);
    } else {
        $homeController = new HomeController();
        $homeController->notFound();
    }
} catch (Exception $e) {
    if (APP_ENV === 'development') {
        die("Error: " . $e->getMessage());
    } else {
        die("An error occurred. Please try again later.");
    }
}

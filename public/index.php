<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Bramus\Router\Router;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Create Router instance
$router = new Router();

// Routes
$router->get('/', function() {
    echo json_encode([
        'name' => $_ENV['APP_NAME'],
        'version' => '1.0.0',
        'status' => 'operational'
    ]);
});

// API Routes
$router->mount('/api', function() use ($router) {
    $router->post('/upload', function() {
        (new \App\Controllers\UploadController())->handle();
    });

    $router->get('/vado/status', function() {
        (new \App\Controllers\UploadController())->status();
    });
});

// Admin Routes (To be implemented)
$router->mount('/admin', function() use ($router) {
    $router->get('/', function() {
        echo 'Admin Dashboard (Coming Soon)';
    });
});

// Error 404
$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => 'Route not found']);
});

// Run it!
$router->run();


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define base path to the Application folder
define('BASE_PATH', realpath(__DIR__ . '/../Application'));

// Autoload (Composer)
require_once __DIR__ . '/../vendor/autoload.php';

// Load routes (correct path)
require_once __DIR__ . '/../routes/web.php';

// Match request using AltoRouter
$match = $router->match();

if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    echo '404 Not Found';
}

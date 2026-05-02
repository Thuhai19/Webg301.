<?php
/**
 * Điểm vào duy nhất (Front Controller) — routing đơn giản
 */
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/app/config/config.php';

session_start();

require_once ROOT_PATH . '/app/core/bootstrap.php';

$rawController = strtolower((string) ($_GET['controller'] ?? 'dashboard'));
$rawAction = strtolower((string) ($_GET['action'] ?? 'index'));
$controllerName = preg_replace('/[^a-z]/', '', $rawController);
$actionName = preg_replace('/[^a-z]/', '', $rawAction);

$map = [
    'auth' => 'AuthController',
    'dashboard' => 'DashboardController',
    'player' => 'PlayerController',
    'character' => 'CharacterController',
    'skill' => 'SkillController',
    'item' => 'ItemController',
];

if (!isset($map[$controllerName])) {
    http_response_code(404);
    echo 'Page not found.';
    exit;
}

$className = $map[$controllerName];
$controller = new $className();

if (!method_exists($controller, $actionName)) {
    http_response_code(404);
    echo 'Action not found.';
    exit;
}

$controller->$actionName();

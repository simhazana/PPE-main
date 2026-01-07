<?php
declare(strict_types=1);
session_start();

// Autoload très simple
spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($path)) require $path;
});

use Core\Router;

$router = new Router();

// Routes
$router->get ('/',           [Controllers\AuthController::class, 'login']);
$router->get ('/index.php',  [Controllers\AuthController::class, 'login']); // fallback si /index.php est appelé
$router->post('/login',      [Controllers\AuthController::class, 'doLogin']);
$router->get ('/dashboard',  [Controllers\AuthController::class, 'dashboard']);
$router->get ('/logout',     [Controllers\AuthController::class, 'logout']);
$router->get ('/logout',     [Controllers\AuthController::class, 'logout']);
$router->get('#^/etat/([0-9]+)$#', [Controllers\EtatController::class, 'show']);
$router->get('/etat',       [Controllers\EtatController::class, 'index']);

$router->get('/etat/',       [Controllers\EtatController::class, 'index']);

// --- routes create ---
$router->get ('/etat/create',       [Controllers\EtatController::class, 'create']);
$router->post('/etat/create',       [Controllers\EtatController::class, 'store']);




$router->get ('#^/etat/([0-9]+)/edit$#', [Controllers\EtatController::class, 'edit']);
$router->post('#^/etat/([0-9]+)/edit$#', [Controllers\EtatController::class, 'update']);

// DELETE
$router->post('#^/etat/([0-9]+)/delete$#', [Controllers\EtatController::class, 'delete']);


$router->get('#^/fraisForfait/([0-9]+)$#', [Controllers\FraisForfaitController::class, 'show']);
$router->get('/fraisForfait',       [Controllers\FraisForfaitController::class, 'index']);

$router->get('/fraisForfait/',       [Controllers\FraisForfaitController::class, 'index']);






// Normalisation du path (gère le projet dans un sous-dossier, ex. /monapp/public)
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$scriptDir   = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/'); // ex: /monapp/public

if ($scriptDir !== '' && $scriptDir !== '/' && strncmp($requestPath, $scriptDir, strlen($scriptDir)) === 0) {
    $requestPath = substr($requestPath, strlen($scriptDir)) ?: '/';
}

if ($requestPath === '/index.php') $requestPath = '/';

// Fallback manuel si le Router n'accroche pas la regex
if (preg_match('#^' . preg_quote($scriptDir, '#') . '/etat/([0-9]+)$#', $_SERVER['REQUEST_URI'] ?? '', $m)
    || preg_match('#^/etat/([0-9]+)$#', $requestPath, $m)) {
    (new \Controllers\EtatController)->show((int)$m[1]);
    exit;
}

if (preg_match('#^' . preg_quote($scriptDir, '#') . '/fraisForfait/([0-9]+)$#', $_SERVER['REQUEST_URI'] ?? '', $m)
    || preg_match('#^/fraisForfait/([0-9]+)$#', $requestPath, $m)) {
    (new \Controllers\FraisForfaitController)->show((int)$m[1]);
    exit;
}
// Fallback manuel pour /etat/{id}/edit
if (preg_match('#^' . preg_quote($scriptDir, '#') . '/etat/([0-9]+)/edit$#', $_SERVER['REQUEST_URI'] ?? '', $m)
    || preg_match('#^/etat/([0-9]+)/edit$#', $requestPath, $m)) {

    $id = (int)$m[1];

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        (new \Controllers\EtatController)->update($id);
    } else {
        (new \Controllers\EtatController)->edit($id);
    }
    exit;
}

// Fallback manuel pour /etat/{id}/delete
if (preg_match('#^' . preg_quote($scriptDir, '#') . '/etat/([0-9]+)/delete$#', $_SERVER['REQUEST_URI'] ?? '', $m)
    || preg_match('#^/etat/([0-9]+)/delete$#', $requestPath, $m)) {

    $id = (int)$m[1];

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        (new \Controllers\EtatController)->delete($id);
    } else {
        // On ne fait rien en GET sur /delete, on renvoie vers la liste
        header('Location: /etat');
    }
    exit;
}



$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $requestPath);

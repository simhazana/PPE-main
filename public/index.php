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
$router->get('#^/etat/([0-9]+)$#', [Controllers\EtatController::class, 'show']);// copier pour visiteur
$router->get('/etat',       [Controllers\EtatController::class, 'index']);// copier pour visiteur

$router->get('/etat/',       [Controllers\EtatController::class, 'index']);// copier pour visiteur

// --- routes create ---
$router->get ('/etat/create',       [Controllers\EtatController::class, 'create']);
$router->post('/etat/create',       [Controllers\EtatController::class, 'store']);




$router->get ('#^/etat/([0-9]+)/edit$#', [Controllers\EtatController::class, 'edit']);
$router->post('#^/etat/([0-9]+)/edit$#', [Controllers\EtatController::class, 'update']);



// DELETE
$router->post('#^/etat/([0-9]+)/delete$#', [Controllers\EtatController::class, 'delete']);


$router->get('#^/fraisForfait/([0-9]+)$#', [Controllers\FraisForfaitController::class, 'show']);
$router->get('/fraisForfait',       [Controllers\FraisForfaitController::class, 'index']);

$router->get('/fraisForfait/',       [Controllers\FraisForfaitController::class, 'index']);// en ordre :  show, index, create, store
$router->get ('/fraisForfait/create',       [Controllers\FraisForfaitController::class, 'create']);
$router->post('/fraisForfait/create',       [Controllers\FraisForfaitController::class, 'store']);

<<<<<<< HEAD
$router->get ('#^/fraisForfait/([0-9]+)/edit$#', [Controllers\FraisForfaitController::class, 'edit']);
$router->post('#^/fraisForfait/([0-9]+)/edit$#', [Controllers\FraisForfaitController::class, 'update']);

$router->get('#^/visiteur/([0-9]+)$#', [Controllers\VisiteurController::class, 'show']);// copier pour visiteur
$router->get('/visiteur',       [Controllers\VisiteurController::class, 'index']);// copier pour visiteur

$router->get('/visiteur/',       [Controllers\VisiteurController::class, 'index']);// copier pour visiteur
=======
>>>>>>> 8006f8d566d1963bd768a29489a99fd709141a28


///
$router->get('#^/fraisHorsForfait/([0-9]+)$#', [Controllers\FraisHorsForfaitController::class, 'show']);
$router->get('/fraisHorsForfait',       [Controllers\FraisHorsForfaitController::class, 'index']);

$router->get('/fraisHorsForfait/',       [Controllers\FraisHorsForfaitController::class, 'index']);// en ordre :  show, index, create, store
$router->get ('/fraisHorsForfait/create',       [Controllers\FraisHorsForfaitController::class, 'create']);
$router->post('/fraisHorsForfait/create',       [Controllers\FraisHorsForfaitController::class, 'store']);

$router->get ('#^/fraisHorsForfait/([0-9]+)/edit$#', [Controllers\FraisHorsForfaitController::class, 'edit']);
$router->post('#^/fraisHorsForfait/([0-9]+)/edit$#', [Controllers\FraisHorsForfaitController::class, 'update']);

// Normalisation du path (gère le projet dans un sous-dossier, ex. /monapp/public)
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$scriptDir   = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/'); // ex: /monapp/public

if ($scriptDir !== '' && $scriptDir !== '/' && strncmp($requestPath, $scriptDir, strlen($scriptDir)) === 0) {
    $requestPath = substr($requestPath, strlen($scriptDir)) ?: '/';
}

if ($requestPath === '/index.php') $requestPath = '/';

// Fallback manuel si le Router n'accroche pas la regex // copier pour visiteur(fait)
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

////
if (preg_match('#^' . preg_quote($scriptDir, '#') . '/fraisHorsForfait/([0-9]+)$#', $_SERVER['REQUEST_URI'] ?? '', $m)
    || preg_match('#^/fraisHorsForfait/([0-9]+)$#', $requestPath, $m)) {
    (new \Controllers\FraisHorsForfaitController)->show((int)$m[1]);
    exit;
}

if (preg_match('#^' . preg_quote($scriptDir, '#') . '/visiteur/([0-9]+)$#', $_SERVER['REQUEST_URI'] ?? '', $m)
    || preg_match('#^/visiteur/([0-9]+)$#', $requestPath, $m)) {
    (new \Controllers\VisiteurController)->show((int)$m[1]);
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

// Fallback manuel pour /fraisForfait/{id}/edit
if (preg_match('#^' . preg_quote($scriptDir, '#') . '/fraisForfait/([0-9]+)/edit$#', $_SERVER['REQUEST_URI'] ?? '', $m)
    || preg_match('#^/fraisForfait/([0-9]+)/edit$#', $requestPath, $m)) {

    $id = (int)$m[1];

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        (new \Controllers\FraisForfaitController)->update($id);
    } else {
        (new \Controllers\FraisForfaitController)->edit($id);
    }
    exit;
    
}

// Fallback manuel pour /fraisHorsForfait/{id}/edit
if (preg_match('#^' . preg_quote($scriptDir, '#') . '/fraisHorsForfait/([0-9]+)/edit$#', $_SERVER['REQUEST_URI'] ?? '', $m)
    || preg_match('#^/fraisHorsForfait/([0-9]+)/edit$#', $requestPath, $m)) {

    $id = (int)$m[1];

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        (new \Controllers\FraisHorsForfaitController)->update($id);
    } else {
        (new \Controllers\FraisHorsForfaitController)->edit($id);
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

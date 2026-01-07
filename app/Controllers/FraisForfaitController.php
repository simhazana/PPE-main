<?php
namespace Controllers;

use Core\Controller;
use Models\FraisForfait;

final class FraisForfaitController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['uid'])) {
            $this->redirect('/');
        }

        try {
            $fraisForfait = FraisForfait::findAll(); // appel statique aligné avec le modèle
        } catch (\Throwable $e) {
            // Pour déboguer, active temporairement la ligne suivante :
            // error_log($e->getMessage());
            $_SESSION['flash'] = 'Impossible de charger les frais forfait.';
            $fraisForfait = [];
        }

        $this->render('fraisForfait/index', [
            'title'   => 'Liste des frais forfait',
            'fraisForfait'   => $fraisForfait,
            'message' => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

 public function show($id): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/');

    $id = (int)$id;

    try {
        $fraisForfait = \Models\FraisForfait::findById($id);
        if (!$fraisForfait) {
            http_response_code(404);
            $_SESSION['flash'] = 'frais forfait introuvable.';
            $this->redirect('/fraisForfait');
        }
    } catch (\Throwable $e) {
        // error_log($e->getMessage()); // utile en debug
        $_SESSION['flash'] = 'Erreur lors du chargement de le frais forfait.';
        $fraisForfait = null;
    }

    $this->render('fraisForfait/show', [
        'title' => 'Détail du frais forfait',
        'fraisForfait'  => $fraisForfait,
        'message' => $_SESSION['flash'] ?? '',
    ]);
    unset($_SESSION['flash']);
}


}

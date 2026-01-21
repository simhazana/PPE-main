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

// l'affichage du formulaire.

public function create(): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/');// redirect, render= fichier index qui redirige

        $this->render('fraisForfait/create', [ // va afficher la vue
            'title'   => 'Créer un frais',
            'message' => $_SESSION['flash'] ?? '', // flash= erreur
            'old'     => $_SESSION['old'] ?? ['libelle' => ''],
            'errors'  => $_SESSION['errors'] ?? [],
        ]);

        unset($_SESSION['flash'], $_SESSION['old'], $_SESSION['errors']); //unset= est ce que vide
    }
// envoyer a la base de donné.
    public function store(): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/');

    $libelle = trim($_POST['libelle'] ?? '');// trim verif si chaine d caractere avec caractere speciaux
    $montant = $_POST['montant'] ?? '';//post recup le champ du form

    if ($libelle === '') {
        $errors['libelle'] = 'Le libellé est obligatoire.';
    } elseif (mb_strlen($libelle) > 100) {
        $errors['libelle'] = 'Le libellé ne doit pas dépasser 100 caractères.';
    }

     if ($montant === '') {
        $errors['montant'] = 'Le montant est obligatoire.';
    } elseif ( $montant <= 0) {
        $errors['montant'] = 'Le montant ne doit pas etre negatif.';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old']    = ['libelle' => $libelle,'montant'=> $montant];
        $_SESSION['flash']  = 'Merci de corriger les erreurs du formulaire.';
        $this->redirect('./fraisForfait/create');
    }

    try { // si ca marche
        $id = \Models\FraisForfait::create($libelle,$montant); 
        $_SESSION['flash'] = 'Frais créé avec succès.';
        $this->redirect('./fraisForfait/' . $id);
    } catch (\Throwable $e) { // si ca marche pas
        $_SESSION['flash'] = 'Impossible de créer le frais.';
        $this->redirect('./fraisForfait');
    }
}


}

<?php
namespace Controllers;

use Core\Controller;
use Models\Visiteur;

final class VisiteurController extends Controller
{
    public function index(): void
    {
        // verifier si connecter sinon rediriger vers la page de connexion
        if (empty($_SESSION['uid'])) {
            $this->redirect('/');
        }

        try {
            $visiteur = Visiteur::findAll(); // appel statique aligné avec le modèle
        } catch (\Throwable $e) {
            // Pour déboguer, active temporairement la ligne suivante :
            // error_log($e->getMessage());
            $_SESSION['flash'] = 'Impossible de charger les visiteurs.';
            $visiteur = [];
        }

        $this->render('Visiteur/index', [
            'title'   => 'Liste des Visiteurs',
            'visiteur'   => $visiteur,
            'message' => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

 public function show($id): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/');

    $id = (int)$id;

    try {
        $visiteur = \Models\Visiteur::findById($id);
        if (!$visiteur) {
            http_response_code(404);
            $_SESSION['flash'] = 'Visiteur introuvable.';
            $this->redirect('/visiteur');
        }
    } catch (\Throwable $e) {
        // error_log($e->getMessage()); // utile en debug
        $_SESSION['flash'] = 'Erreur lors du chargement du visiteur.';
        $visiteur = null;
    }

    $this->render('visiteur/show', [
        'title' => 'Détail du visiteur',
        'etat'  => $visiteur,
        'message' => $_SESSION['flash'] ?? '',
    ]);
    unset($_SESSION['flash']);
}

/*
 public function create(): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/');

        $this->render('etat/create', [
            'title'   => 'Créer un état',
            'message' => $_SESSION['flash'] ?? '',
            'old'     => $_SESSION['old'] ?? ['libelle' => ''],
            'errors'  => $_SESSION['errors'] ?? [],
        ]);

        unset($_SESSION['flash'], $_SESSION['old'], $_SESSION['errors']);
    }

    public function store(): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/');

    $libelle = trim($_POST['libelle'] ?? '');

    $errors = [];

    if ($libelle === '') {
        $errors['libelle'] = 'Le libellé est obligatoire.';
    } elseif (mb_strlen($libelle) > 100) {
        $errors['libelle'] = 'Le libellé ne doit pas dépasser 100 caractères.';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old']    = ['libelle' => $libelle];
        $_SESSION['flash']  = 'Merci de corriger les erreurs du formulaire.';
        $this->redirect('./etat/create');
    }

    try {
        $id = \Models\Etat::create($libelle); // maintenant avec ?
        $_SESSION['flash'] = 'État créé avec succès.';
        $this->redirect('./etat/' . $id);
    } catch (\Throwable $e) {
        $_SESSION['flash'] = 'Impossible de créer l’état.';
        $this->redirect('./etat');
    }
}


// ---------- EDIT (GET) ----------
public function edit($id): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/');

    $id = (int)$id;

    try {
        $etat = \Models\Etat::findById($id);
        if (!$etat) {
            $_SESSION['flash'] = "État introuvable.";
            $this->redirect('./etat');
        }
    } catch (\Throwable $e) {
        $_SESSION['flash'] = "Erreur lors du chargement de l'état.";
        $this->redirect('./etat');
    }

    // remplissage auto
    $old = $_SESSION['old'] ?? ['libelle' => $etat['libelle']];

    $this->render('etat/edit', [
        'title'   => 'Modifier un état',
        'etat'    => $etat,
        'old'     => $old,
        'errors'  => $_SESSION['errors'] ?? [],
        'message' => $_SESSION['flash'] ?? ''
    ]);

    unset($_SESSION['old'], $_SESSION['errors'], $_SESSION['flash']);
}

// ---------- UPDATE (POST) ----------
public function update($id): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/');

    $id = (int)$id;
    $libelle = trim($_POST['libelle'] ?? '');

    $errors = [];

    if ($libelle === '') {
        $errors['libelle'] = 'Le libellé est obligatoire.';
    }

    if ($errors) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = ['libelle' => $libelle];
        $_SESSION['flash'] = "Merci de corriger les erreurs.";
        $this->redirect("./etat/$id/edit");
    }

    try {
        \Models\Etat::update($id, $libelle);
        $_SESSION['flash'] = "État modifié avec succès.";
        $this->redirect("./etat/$id");
    } catch (\Throwable $e) {
        $_SESSION['flash'] = "Erreur lors de la mise à jour.";
        $this->redirect("./etat");
    }
}




public function delete($id): void
{
    if (empty($_SESSION['uid'])) {
        $this->redirect('/');
    }

    $id = (int)$id;

    try {
        $ok = \Models\Etat::delete($id);

        if ($ok) {
            $_SESSION['flash'] = "État supprimé avec succès.";
        } else {
            $_SESSION['flash'] = "Impossible de supprimer cet état.";
        }
    } catch (\Throwable $e) {
        // error_log($e->getMessage());
        $_SESSION['flash'] = "Erreur lors de la suppression de l’état.";
    }

    $this->redirect('/etat');
}

*/



}

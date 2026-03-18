<?php
namespace Controllers;

use Core\Controller;
use Models\FicheFrais;

final class FicheFraisController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['uid'])) {
            $this->redirect('/');
        }

        try {
            $ficheFrais = FicheFrais::findAll(); // appel statique aligné avec le modèle
        } catch (\Throwable $e) {
            //Pour déboguer, active temporairement la ligne suivante :
            error_log($e->getMessage());
            $_SESSION['flash'] = 'Impossible de charger les fiches frais.';
            $ficheFrais = [];
        }

        $this->render('ficheFrais/index', [
            'title'   => 'Liste des frais forfait',
            'ficheFrais'   => $ficheFrais,
            'message' => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

 public function show($idvisiteur, $mois): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/');


    try {
        $ficheFrais = \Models\FicheFrais::findById($idvisiteur,$mois);
        if (!$ficheFrais) {
            http_response_code(404);
            $_SESSION['flash'] = 'fiche frais introuvable.';
            $this->redirect('/ficheFrais');
            return;
        }
    } catch (\Throwable $e) {
        error_log($e->getMessage()); // utile en debug
        $_SESSION['flash'] = 'Erreur lors du chargement de la fiche frais.';
        $ficheFrais = null;
    }

    $this->render('ficheFrais/show', [
        'title' => 'Détail du frais forfait',
        'ficheFrais'  => $ficheFrais,
        'message' => $_SESSION['flash'] ?? '',
    ]);
    unset($_SESSION['flash']);
}

// l'affichage du formulaire.

public function create(): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/');// redirect, render= fichier index qui redirige

        $this->render('ficheFrais/create', [ // va afficher la vue
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

    $visiteur = trim($_POST['visiteur'] ?? '');
    $mois = $_POST['mois'] ?? '';
    $nbrJustificatifs = trim($_POST['nbrJustificatifs'] ?? '');
    $montantValide = $_POST['montantValide'] ?? '';
    $dateModif = trim($_POST['dateModif'] ?? '');
    $fraisHorsForfait = $_POST['fraishorsforfait'] ?? '';
    $etat = trim($_POST['etat'] ?? '');
    
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
        $this->redirect('./ficheFrais/create');
    }

    try { // si ca marche
        $id = \Models\FicheFrais::create($libelle,$montant); 
        $_SESSION['flash'] = 'Frais créé avec succès.';
        $this->redirect('./ficheFrais/' . $id);
    } catch (\Throwable $e) { // si ca marche pas
        $_SESSION['flash'] = 'Impossible de créer le frais.';
        $this->redirect('./ficheFrais');
    }
}

  // ---------- EDIT (GET) ----------
public function edit($id): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/');

    $id = (int)$id;

    try {
        $ficheFrais = \Models\FicheFrais::findById($id);
        if (!$ficheFrais) {
            $_SESSION['flash'] = "Frais forfait introuvable.";
            $this->redirect('./ficheFrais');
        }
    } catch (\Throwable $e) {
        $_SESSION['flash'] = "Erreur lors du chargement du frais forfait.";
        $this->redirect('./ficheFrais');
    }

    // remplissage auto
    $old = $_SESSION['old'] ?? [
        'libelle' => $ficheFrais['libelle'],
        'montant' => $ficheFrais['montant']
        ];


    $this->render('ficheFrais/edit', [
        'title'   => 'Modifier un frais forfait',
        'ficheFrais'  => $ficheFrais,
        /*'montant' => $montant,*/
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
    $montant = trim($_POST['montant'] ?? '');

    $errors = [];

    if ($libelle === '') {
        $errors['libelle'] = 'Le libellé est obligatoire.';
    }

    if ($montant === '') {
        $errors['montant'] = 'Le montant est obligatoire.';
    }

    if ($errors) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = ['libelle' => $libelle,'montant' => $montant];
        $_SESSION['flash'] = "Merci de corriger les erreurs.";
        $this->redirect("./ficheFrais/$id/edit");
    }



    try {
        \Models\FicheFrais::update($id, $libelle, $montant);
        $_SESSION['flash'] = "Frais Forfait modifié avec succès.";
        $this->redirect("./ficheFrais/$id");
    } catch (\Throwable $e) {
        $_SESSION['flash'] = "Erreur lors de la mise à jour.";
        $this->redirect("./ficheFrais");
    }
}



    public function delete($id): void
{
    if (empty($_SESSION['uid'])) {
        $this->redirect('/');
    }

    $id = (int)$id;

    try {
        $ok = \Models\FicheFrais::delete($id);

        if ($ok) {
            $_SESSION['flash'] = " Frais forfait supprimé avec succès.";
        } else {
            $_SESSION['flash'] = "Impossible de supprimer ce Frais forfait.";
        }
    } catch (\Throwable $e) {
        // error_log($e->getMessage());
        $_SESSION['flash'] = "Erreur lors de la suppression du Frais forfait.";
    }

    $this->redirect('/ficheFrais');
}

}

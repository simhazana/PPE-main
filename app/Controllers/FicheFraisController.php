<?php
namespace Controllers;

use Core\Controller;
use Models\FicheFrais;
use Models\Visiteur;

final class FicheFraisController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        try {
            if ($this->isComptable()) {
                // Le comptable voit toutes les fiches avec le nom du visiteur
                $ficheFrais = FicheFrais::findAll();
            } else {
                // Le visiteur ne voit que ses propres fiches
                $ficheFrais = FicheFrais::findByVisiteur((int)$_SESSION['uid']);
            }
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['flash'] = 'Impossible de charger les fiches frais.';
            $ficheFrais = [];
        }

        $this->render('fichefrais/index', [
            'title'      => 'Liste des fiches frais',
            'ficheFrais' => $ficheFrais,
            'message'    => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

    public function show($idvisiteur, $mois): void
    {
        $this->requireAuth();

        try {
            $ficheFrais = FicheFrais::findById((int)$idvisiteur, (int)$mois);
            if (!$ficheFrais) { $this->redirect('/fichefrais'); return; }
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['flash'] = 'Erreur lors du chargement de la fiche frais.';
            $ficheFrais = null;
        }

        $this->render('fichefrais/show', [
            'title'      => 'Détail de la fiche frais',
            'ficheFrais' => $ficheFrais,
            'message'    => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->render('fichefrais/create', [
            'title'   => 'Créer une fiche frais',
            'message' => $_SESSION['flash'] ?? '',
            'old'     => $_SESSION['old'] ?? [],
            'errors'  => $_SESSION['errors'] ?? [],
        ]);
        unset($_SESSION['flash'], $_SESSION['old'], $_SESSION['errors']);
    }

    public function store(): void
    {
        $this->requireAuth();

        $visiteur         = trim($_POST['visiteur'] ?? '');
        $mois             = $_POST['mois'] ?? '';
        $nbrJustificatifs = trim($_POST['nbrJustificatifs'] ?? '');
        $montantValide    = $_POST['montantValide'] ?? '';
        $dateModif        = trim($_POST['dateModif'] ?? '');
        $fraisHorsForfait = $_POST['fraishorsforfait'] ?? '';
        $etat             = trim($_POST['etat'] ?? '');

        try {
            $id = FicheFrais::create($visiteur, $mois);
            $_SESSION['flash'] = 'Fiche frais créée avec succès.';
            $this->redirect('/fichefrais');
        } catch (\Throwable $e) {
            $_SESSION['flash'] = 'Impossible de créer la fiche frais.';
            $this->redirect('/fichefrais');
        }
    }

    public function edit($id, $mois): void
    {
        $this->requireAuth();
        $id = (int)$id;

        try {
            $ficheFrais = FicheFrais::findById($id, (int)$mois);
            if (!$ficheFrais) {
                $_SESSION['flash'] = 'Fiche frais introuvable.';
                $this->redirect('/fichefrais');
            }
        } catch (\Throwable $e) {
            $_SESSION['flash'] = 'Erreur lors du chargement.';
            $this->redirect('/fichefrais');
        }

        $old = $_SESSION['old'] ?? [
            'nbrJustificatifs' => $ficheFrais['nbrJustificatifs'],
            'montantValide'    => $ficheFrais['montantValide'],
            'dateModif'        => $ficheFrais['dateModif'],
        ];

        $this->render('fichefrais/edit', [
            'title'      => 'Modifier une fiche frais',
            'ficheFrais' => $ficheFrais,
            'old'        => $old,
            'errors'     => $_SESSION['errors'] ?? [],
            'message'    => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['old'], $_SESSION['errors'], $_SESSION['flash']);
    }

    public function update($idvisiteur, $mois): void
    {
        $this->requireAuth();

        $nbrJustificatifs = trim($_POST['nbrJustificatifs'] ?? '');
        $montantValide    = trim($_POST['montantValide'] ?? '');
        $dateModif        = trim($_POST['dateModif'] ?? '');
        $errors = [];

        if ($nbrJustificatifs === '') $errors['nbrJustificatifs'] = 'Le nombre de justificatifs est obligatoire.';
        if ($montantValide === '')    $errors['montantValide']    = 'Le montant est obligatoire.';

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old']    = compact('nbrJustificatifs', 'montantValide', 'dateModif');
            $_SESSION['flash']  = 'Merci de corriger les erreurs.';
            $this->redirect("/fichefrais/$idvisiteur/$mois/edit");
        }

        try {
            FicheFrais::update($idvisiteur, $mois, $nbrJustificatifs, $montantValide, $dateModif);
            $_SESSION['flash'] = 'Fiche frais modifiée avec succès.';
            $this->redirect("/fichefrais/$idvisiteur/$mois");
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['flash'] = 'Erreur lors de la mise à jour.';
            $this->redirect('/fichefrais');
        }
    }

    public function delete($id): void
    {
        $this->requireComptable();
        $id = (int)$id;
        try {
            $ok = FicheFrais::delete($id);
            $_SESSION['flash'] = $ok ? 'Fiche frais supprimée.' : 'Impossible de supprimer.';
        } catch (\Throwable $e) {
            $_SESSION['flash'] = 'Erreur lors de la suppression.';
        }
        $this->redirect('/fichefrais');
    }

    // ─── Actions de changement d'état (comptable uniquement) ───────────────

    public function validate($idvisiteur, $mois): void
    {
        $this->requireComptable();
        $this->changerEtat($idvisiteur, $mois, 3, 'Validé'); // id 3 = Validé
    }

    public function refuse($idvisiteur, $mois): void
    {
        $this->requireComptable();
        // On crée l'état Refusé en BDD s'il n'existe pas encore (id à adapter selon ta BDD)
        $this->changerEtat($idvisiteur, $mois, 10, 'Refusé'); // id 10 = Refusé (à adapter)
    }

    public function cloture($idvisiteur, $mois): void
    {
        $this->requireComptable();
        $this->changerEtat($idvisiteur, $mois, 2, 'Clôturé'); // id 2 = Clôturé
    }

    public function rembourse($idvisiteur, $mois): void
    {
        $this->requireComptable();
        $this->changerEtat($idvisiteur, $mois, 7, 'Remboursé'); // id 7 = Remboursé
    }

    private function changerEtat(string $idvisiteur, string $mois, int $idEtat, string $libelle): void
    {
        try {
            $ok = FicheFrais::setEtat($idvisiteur, $mois, $idEtat);
            $_SESSION['flash'] = $ok ? "Fiche passée en « $libelle »." : "Impossible de changer l'état.";
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['flash'] = "Erreur technique lors du changement d'état.";
        }
        $this->redirect('/fichefrais');
    }
}
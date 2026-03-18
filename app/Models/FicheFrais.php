<?php
namespace Models;

use Config\Database;

final class FicheFrais
{
    // Méthode statique, simple et fiable
    public static function findAll(): array
    {
        $pdo = Database::get();
        $st  = $pdo->query('SELECT 
        FicheFrais.idVisiteur,
        FicheFrais.mois,
        FicheFrais.nbrJustificatifs,
        FicheFrais.montantValide,
        FicheFrais.dateModif,
        FicheFrais.idLigneFraisHorsForfait,
        FicheFrais.idEtat

        /*FraisHorForfait.libelle AS LibelleHorForfait,
        Etat.libelle AS LibelleEtat*/
        
        FROM 
            FicheFrais 

        Join
            visiteur ON FicheFrais.idVisiteur = visiteur.id
        Join
            fraishorsforfait ON FicheFrais.idLigneFraisHorsForfait= FraisHorsForfait.id
        Join 
            Etat ON FicheFrais.idEtat = Etat.id');

        return $st->fetchAll(); 
    }
    public static function findById(int $idvisiteur,int $mois): ?array
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('SELECT * FROM FicheFrais WHERE idVisiteur = :id AND mois= :mois');
        $st->execute([
            'idVisiteur' => $idvisiteur,
            'mois' => $mois
            ]);

        $row = $st->fetch();
        return $row ?: null;
    }

    public static function create(string $libelle, string $montant): int
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('INSERT INTO ficheFrais (libelle, montant) VALUES (?,?)');
        $st->execute([$libelle,$montant]);
        return (int)$pdo->lastInsertId(); // ajouter un id +1

    } 

   public static function update(int $id, string $libelle,string $montant): bool
   {

        $pdo = Database::get();
        $st  = $pdo->prepare('UPDATE ficheFrais SET libelle = ?, montant= ? WHERE id = ?');
        return $st->execute([$libelle,  $montant, $id]);
}

    public static function delete(int $id): bool
{
    $pdo = Database::get();
    $st  = $pdo->prepare('DELETE FROM ficheFrais WHERE id = ?');
    return $st->execute([$id]);
}


}  


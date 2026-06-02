<?php
namespace Models;

use Config\Database;

final class FicheFrais
{
    // Toutes les fiches (comptable) — avec le nom du visiteur
    public static function findAll(): array
    {
        $pdo = Database::get();
        $st  = $pdo->query('SELECT 
            FicheFrais.idVisiteur,
            FicheFrais.mois,
            FicheFrais.nbrJustificatifs,
            FicheFrais.montantValide,
            FicheFrais.dateModif,
            CONCAT(visiteur.nom, \' \', visiteur.prenom) AS nomVisiteur,
            FraisHorsForfait.libelle AS libelleHorsForfait,
            Etat.libelle AS libelleEtat
            FROM FicheFrais
            JOIN visiteur ON FicheFrais.idVisiteur = visiteur.id
            JOIN FraisHorsForfait ON FicheFrais.idLigneFraisHorsForfait = FraisHorsForfait.id
            JOIN Etat ON FicheFrais.idEtat = Etat.id');
        return $st->fetchAll();
    }

    // Fiches d'un visiteur spécifique (visiteur connecté)
    public static function findByVisiteur(int $idVisiteur): array
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('SELECT 
            FicheFrais.idVisiteur,
            FicheFrais.mois,
            FicheFrais.nbrJustificatifs,
            FicheFrais.montantValide,
            FicheFrais.dateModif,
            FraisHorsForfait.libelle AS libelleHorsForfait,
            Etat.libelle AS libelleEtat
            FROM FicheFrais
            JOIN FraisHorsForfait ON FicheFrais.idLigneFraisHorsForfait = FraisHorsForfait.id
            JOIN Etat ON FicheFrais.idEtat = Etat.id
            WHERE FicheFrais.idVisiteur = ?');
        $st->execute([$idVisiteur]);
        return $st->fetchAll();
    }

    public static function findById(int $idvisiteur, int $mois): ?array
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('SELECT 
            FicheFrais.idVisiteur,
            FicheFrais.mois,
            FicheFrais.nbrJustificatifs,
            FicheFrais.montantValide,
            FicheFrais.dateModif,
            FraisHorsForfait.libelle AS libelleHorsForfait,
            Etat.libelle AS libelleEtat
            FROM FicheFrais
            JOIN FraisHorsForfait ON FicheFrais.idLigneFraisHorsForfait = FraisHorsForfait.id
            JOIN Etat ON FicheFrais.idEtat = Etat.id
            WHERE FicheFrais.idVisiteur = :idV AND FicheFrais.mois = :mois');
        $st->execute(['idV' => $idvisiteur, 'mois' => $mois]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public static function create(string $visiteur, string $mois): int
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('INSERT INTO ficheFrais (idVisiteur, mois, nbrJustificatifs, montantValide, dateModif, idLigneFraisHorsForfait, idEtat) VALUES (?,?,0,0,NOW(),1,1)');
        $st->execute([$visiteur, $mois]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(string $idvisiteur, string $mois, string $nbrJustificatifs, string $montantValide, string $dateModif): bool
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('UPDATE FicheFrais SET nbrJustificatifs = ?, montantValide = ?, dateModif = ? WHERE idVisiteur = ? AND mois = ?');
        return $st->execute([$nbrJustificatifs, $montantValide, $dateModif, $idvisiteur, $mois]);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('DELETE FROM ficheFrais WHERE idVisiteur = ?');
        return $st->execute([$id]);
    }

    public static function validate(string $idvisiteur, string $mois): bool
    {
        return self::setEtat($idvisiteur, $mois, 3);
    }

    // Change l'état d'une fiche frais (id de la table etat)
    public static function setEtat(string $idvisiteur, string $mois, int $idEtat): bool
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('UPDATE FicheFrais SET idEtat = ?, dateModif = NOW() WHERE idVisiteur = ? AND mois = ?');
        return $st->execute([$idEtat, $idvisiteur, $mois]);
    }
}
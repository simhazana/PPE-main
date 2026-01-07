<?php
namespace Models;

use Config\Database;

final class FraisForfait
{
    // Méthode statique, simple et fiable
    public static function findAll(): array
    {
        $pdo = Database::get();
        $st  = $pdo->query('SELECT id, libelle, montant FROM fraisForfait ORDER BY id');
        return $st->fetchAll(); // FETCH_ASSOC déjà par défaut via Database
    }
    public static function findById(int $id): ?array
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('SELECT id, libelle, montant FROM fraisForfait WHERE id = :id');
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ?: null;
    }
    
}

<?php
namespace Models;

use Config\Database;

final class Visiteur
{
    // Méthode statique, simple et fiable
    // findall permet de lister
    public static function findAll(): array
    {
        $pdo = Database::get();
        $st  = $pdo->query('SELECT id,nom,prenom,adresse,ville,cp,date_embauche,login,mdp FROM  visiteur ORDER BY id');
        return $st->fetchAll(); // FETCH_ASSOC déjà par défaut via Database
    }
    public static function findById(int $id): ?array
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('SELECT * FROM visiteur WHERE id = ?');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

     public static function create(
        string $nom,
        string $prenom,
        string $adresse,
        string $ville,
        string $cp,
        string $date_embauche,
        string $login,
        string $mdp
        ): int
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('INSERT INTO visiteur (nom,prenom,adresse,ville,cp,date_embauche,login,mdp) VALUES (?,?,?,?,?,?,?,?)');
        $st->execute([$nom, $prenom, $adresse, $ville, $cp, $date_embauche, $login, $mdp]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(
        int $id,
        string $nom,
        string $prenom, 
        string $adresse,
        string $ville,
        string $cp,
        string $date_embauche,
        string $login
        /*,
        string $mdp*/
        ): bool
{
    $pdo = Database::get();
    $st  = $pdo->prepare('UPDATE visiteur SET 
        nom = ? , 
        prenom = ?, 
        adresse = ?, 
        ville = ?, 
        cp  = ?,
        date_embauche = ?, 
        login = ?
        WHERE id = ?'
        );

    return $st->execute([
        $nom,
        $prenom,
        $adresse,
        $ville,
        $cp, 
        $date_embauche, 
        $login,
        $id
        /*$mdp*/
        
        ]);
}


public static function delete(int $id): bool
{
    $pdo = Database::get();
    $st  = $pdo->prepare('DELETE FROM visiteur WHERE id = ?');
    return $st->execute([$id]);
}

}
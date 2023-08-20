<?php
namespace App\Util;
use PDO;

class Sql {
    private static ?PDO $pdo = null;
    
    /**
     * Récupération de la connexion BDD pour les extensions (enfants)
     */
    public static function bdd(): PDO
    {
        if (self::$pdo == null) {
            self::$pdo = self::connection();
        }
        return self::$pdo;
    }

    /**
     * Connexion à la base de données
     */
    private static function connection(): PDO
    {
        try {
            $db = new PDO("mysql:host=localhost;dbname=php-poo", 'root', '');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } 
        catch (\PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
}
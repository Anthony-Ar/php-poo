<?php
namespace App\Repository;

use App\Util\Sql;
use PDO;

class Database extends Sql
{
    protected string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Récupère toutes les entrées d'une table
     */
    public function findAll(?string $orderBy = 'ID DESC', ?string $limit = null): array|null
    {
        $queryOrderBy = $orderBy !== null ? 'ORDER BY '.$orderBy : '';
        $queryLimit = $limit !== null ? 'LIMIT '.$limit : '';

        $query = Sql::bdd()->prepare("SELECT * FROM {$this->table} {$queryOrderBy} {$queryLimit}");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une seule entrée en fonction d'un ID
     */
    public function findOne(int $id): array|bool
    {
        $query = Sql::bdd()->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime une entrée
     */
    public function delete(int $id): string|null
    {
        $query = Sql::bdd()->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return ''; // Alerts
    }

    /**
     * Additionne le total d'une colonne
     */
    public function countSum(string $column, ?string $where = null): int|null
    {
        $queryWhere = $where !== null ? 'WHERE '.$where : '';
        $query = Sql::bdd()->prepare("SELECT SUM({$column}) as sum FROM {$this->table} {$queryWhere}");
        $query->execute();
        return $query->fetchColumn();
    }

    /**
     * Compte le nombre de ligne dans une table
     */
    public function countTotal(?string $where = null): int|null
    {
        $queryWhere = $where !== null ? 'WHERE '.$where : '';
        $query = $this->bdd()->prepare("SELECT COUNT(*) as count FROM {$this->table} {$queryWhere}");
        $query->execute();
        return $query->fetchColumn();
    }
}
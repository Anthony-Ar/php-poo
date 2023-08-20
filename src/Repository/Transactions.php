<?php
namespace App\Repository;

use App\Entity\TransactionsBase;

final class Transactions extends TransactionsBase
{
    /**
     * Surcharge de findAll() pour associer un nom (clients) à l'accountId (transactions)
     */
    public function findAll(?string $orderBy = 'date desc', ?string $limit = null): array|null
    {
        $queryOrderBy = $orderBy !== null ? 'ORDER BY '.$orderBy : '';
        $queryLimit = $limit !== null ? 'LIMIT '.$limit : '';

        $query = $this->bdd()->prepare("
            SELECT transactions.*, clients.name
            FROM transactions 
            INNER JOIN clients ON transactions.accountId = clients.id 
            {$queryOrderBy}
            {$queryLimit}
        ");
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** 
     * Cherche la dernière transaction enregistrée en fonction d'un accountId
     */
    public function findLast(int $id, ?string $orderBy = 'date DESC'): array|bool
    {
        $queryOrderBy = $orderBy !== null ? 'ORDER BY '.$orderBy : '';

        $query = $this->bdd()->prepare("SELECT * FROM transactions WHERE accountId = ? {$queryOrderBy} LIMIT 1");
        $query->bindParam(1, $id, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Effectue une recherche de transactions par client
     */
    public function findByAccountId(int $account, ?string $orderBy = 'ID DESC', ?string $limit = null): array|null
    {
        $queryOrderBy = $orderBy != null ? 'ORDER BY '.$orderBy : '';
        $queryLimit = $limit != null ? 'LIMIT '.$limit : '';

        $query = $this->bdd()->prepare("SELECT * FROM transactions WHERE accountId = :accountId {$queryOrderBy} {$queryLimit}");
        $query->bindParam(':accountId', $account, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** 
     * Met à jour une transaction
     */
    public function update(int $id): string|null {
        $query = $this->bdd()->prepare("UPDATE transactions SET date = ?, accountId = ?, quantity = ?, price = ? WHERE id = {$id}");
        $query->bindParam(1, $this->getDate());
        $query->bindParam(2, $this->getAccountId(), \PDO::PARAM_INT);
        $query->bindParam(3, $this->getQuantity(), \PDO::PARAM_INT);
        $query->bindParam(4, $this->getPrice(), \PDO::PARAM_INT);
        $query->execute();

        return ''; // Alerts 
    }
}
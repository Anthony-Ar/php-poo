<?php
namespace App\Controller;

use App\Helper\DateHelper;
use App\Repository\Clients;
use App\Repository\Transactions;
use Symfony\Component\HttpFoundation\Response;

class ClientsController extends AbstractController
{
    private Clients $clients;
    
    public function __construct(Clients $clients)
    {
        $this->clients = $clients;
    }

    /**
     * Route : /clients/
     */
    public function index(): Response
    {
        return $this->render('pages/clients/index.html.twig', [
            'clients' => $this->clients->findAll(),
            'clients_total' => $this->clients->countTotal()
        ]);
    }

    /**
     * Route : /clients/{id}/
     */
    public function uniqueView(int $id): Response
    {        
        $profil = $this->clients->findOne($id);
        if (!$profil) $this->redirect('/');

        $getTransactions = $this->getContainer(Transactions::class);
        $transactions = [
            'transactions' => $getTransactions->findByAccountId($id),
            'price' => $getTransactions->countSum('price', 'accountId ='.$id),
            'total' => $getTransactions->countTotal('accountId ='.$id)
        ];

        $lastAction = $getTransactions->findLast($id);
        $lastActivity = $lastAction ? DateHelper::getInterval($lastAction['date']) : 'Jamais';
        
        return $this->render('pages/clients/unique.html.twig', [
            'profil' => $this->clients->findOne($id),
            'transactions' => $transactions,
            'activity' => $lastActivity
        ]);
    }
}
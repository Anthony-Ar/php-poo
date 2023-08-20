<?php
namespace App\Controller;

use App\Repository\Transactions;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class TransactionsController extends AbstractController
{
    private Transactions $transactions;

    public function __construct(Transactions $transactions) 
    {
        $this->transactions = $transactions;
    }

    /**
     * Route : / ou /transactions/
     */
    public function index(Request $request): Response
    {
        if($request->isMethod('POST') && $request->request->has('search_form')) {
            var_dump($request->request->get('client_name'));
            var_dump($request->request->has('strict'));
            var_dump($request->request->get('date'));
            $transactions = $this->transactions->findAll();
        } else {
            $transactions = $this->transactions->findAll();
        }

        return $this->render('pages/transactions/index.html.twig', [
            'transactions' => $transactions,
            'count_total' => $this->transactions->countSum('price'),
            'transactions_total' => $this->transactions->countTotal()
        ]);
    }
}
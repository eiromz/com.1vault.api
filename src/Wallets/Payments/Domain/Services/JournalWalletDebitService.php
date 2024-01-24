<?php

namespace Src\Wallets\Payments\Domain\Services;

use App\Exceptions\InsufficientBalance;
use Symfony\Component\HttpFoundation\Response;

class JournalWalletDebitService
{
    public object $accountInstance;
    public $request;
    private $balance_before;
    private $balance_after;
    private $repository;

    public $creationKeys = ["amount", "trx_ref" ,
      "debit", "credit", "label", "source", "balance_before", "balance_after"
    ];
    public function __construct($accountInstance,$request=null,$repository)
    {
        $this->accountInstance = $accountInstance;
        $this->request = $request;
    }

    /**
     * @throws InsufficientBalance
     */
    public function checkBalance()
   {
       if($this->request->amount > $this->accountInstance->balance_after){
           throw new InsufficientBalance('Insufficient Balance',Response::HTTP_BAD_REQUEST);
       }
       return $this;
   }

   public function calculateNewBalance()
   {
       return ($this->accountInstance->balance_after - $this->request->amount);
   }

   public function debit(){
        $this->request->merge([
            'balance_before' => $this->accountInstance->balance_after,
            'balance_after' => $this->calculateNewBalance(),
        ]);

        //dd($this->request->only($this->creationKeys));

        dd($this->repository->create($this->request->only($this->creationKeys)));
   }

   public function notify()
   {
       //i want to notify the source of the wallet via firebase that a transaction has occured on his account
       // i want to notify via email that a transaction has occured on the users account.
   }

   public function firebase()
   {

   }

   public function email()
   {

   }
}

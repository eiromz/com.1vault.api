<?php

namespace Src\Wallets\Payments\Domain\Services;

use App\Exceptions\InsufficientBalance;
use Symfony\Component\HttpFoundation\Response;

class JournalWalletDebitService
{
    public object $accountInstance;
    public $request;
    private $repository;
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

   public function debit(){

        $this->repository->create([

        ]);

        return $this;
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

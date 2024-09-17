<?php

namespace Src\Wallets\Payments\Domain\Services;

class ProcessCartTransaction
{
    private array $params;

    private $service;

    //SUBSCRIPTION
    //ONE TIME PAYMENT
    //ADD ORDER TO CART
    //check if cart is present in the request
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function process() {}

    private function subscription()
    {
        //check if its a recurring charge
        //check if
        //check the factors that make a transaction become a subscription
        //check the transaction that make a transaction one time.
    }

    private function cart()
    {
        //check if cart exists
        //get the cart
    }

    private function service() {}

    private function saveOrderNumber() {}
}

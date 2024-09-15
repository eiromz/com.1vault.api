<?php

namespace Src\Wallets\Payments\Domain\Actions;

class UpdateCartWithOrderNumber
{
    public function __construct(public $cart, public $orderNumber) {}

    public function execute(): void
    {
        $this->cart->order_number = $this->orderNumber;
        $this->cart->save();
    }
}

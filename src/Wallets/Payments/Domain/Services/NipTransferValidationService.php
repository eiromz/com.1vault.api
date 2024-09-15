<?php

namespace Src\Wallets\Payments\Domain\Services;

class NipTransferValidationService
{
    public function validateTransaction($accountNumber)
    {
        //welcome to the land of the
    }

    public function validateAmount($amount)
    {
        // Check if amount is a positive number
        return is_numeric($amount) && $amount > 0;
    }

    public function validateCurrency($currency)
    {
        // Validate currency is a three-letter code
        return preg_match('/^[A-Z]{3}$/', $currency);
    }

    public function validateTransfer($accountNumber, $amount, $currency)
    {
        return $this->validateAccountNumber($accountNumber) &&
            $this->validateAmount($amount) &&
            $this->validateCurrency($currency);
    }
}

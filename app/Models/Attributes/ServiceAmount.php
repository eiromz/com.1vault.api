<?php

namespace App\Models\Attributes;

use App\Exceptions\BaseException;
use Carbon\Carbon;

class ServiceAmount
{
    public function __construct(
        public float $amount, private int $discount,private bool $has_discount){}

    /**
     * @throws BaseException
     */
    public function execute(): float|int
    {
        if($this->has_discount && is_int($this->discount) && $this->discount > 0){
            $this->amount = calculateDiscount($this->discount,$this->amount);
        }
        return $this->amount;
    }
}

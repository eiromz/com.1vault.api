<?php

namespace App\Models\Attributes;

use App\Exceptions\BaseException;

class ServiceAmount
{
    public float $amount;

    public int $discount;

    public $has_discount;

    public function __construct(public $attributes)
    {
        $this->amount = $this->attributes['amount'];
        $this->discount = $this->attributes['discount'];
        $this->has_discount = $this->attributes['has_discount'];
    }

    /**
     * @throws BaseException
     */
    public function execute(): float|int
    {
        //dd($this->attributes['has_discount']);
        if ($this->attributes['has_discount'] && $this->discount > 0) {
            $this->amount = calculateDiscount($this->attributes['has_discount'], $this->discount, $this->amount);
        }

        return $this->amount;
    }
}

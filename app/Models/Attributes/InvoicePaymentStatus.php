<?php

namespace App\Models\Attributes;

use Carbon\Carbon;

class InvoicePaymentStatus
{
    public $payment_status;

    public $due_date;

    public function __construct($payment_status, $due_date)
    {
        $this->payment_status = $payment_status;
        $this->due_date = $due_date;
    }

    public function execute()
    {
        $value = 'Unpaid';

        if ($this->payment_status) {
            $value = 'Paid';
        }

        if (! $this->payment_status && Carbon::parse($this->due_date)->isPast()) {
            $value = 'Overdue';
        }

        return $value;
    }
}

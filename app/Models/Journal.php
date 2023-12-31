<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'customer_id',
        'trx_ref',
        'session_id',
        'amount',
        'commission',
        'debit',
        'credit',
        'balance_before',
        'balance_after',
        'label',
        'source',
        'payload',
    ];
}

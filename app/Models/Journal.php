<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Journal extends Model
{
    use HasFactory,HasUuids;

    protected $guarded = [];

    protected $with = ['customer', 'service'];

    protected $casts = [
        'debit' => 'boolean',
        'credit' => 'boolean',
        'payload' => 'array',
    ];

    const BANK_CODE = '000023';

    const BANK_NAME = 'PROVIDUS BANK';

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}

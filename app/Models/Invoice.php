<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'invoice_date' => 'datetime',
        'due_date' => 'datetime',
        'items' => 'array',
    ];

    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function collaborator(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    //    public function client(): BelongsTo
    //    {
    //        return $this->belongsTo(Client::class);
    //    }
}

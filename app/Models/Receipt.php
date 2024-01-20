<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    use HasFactory;

    protected $casts = [
        'transaction_date' => 'datetime',
        'items' => 'array',
    ];

    protected $guarded = [];

    protected $appends = ['receipt_number'];

    protected $with = ['client'];

    protected function receiptNumber(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => 'REC000'.$attributes['id'],
        );
    }
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
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}

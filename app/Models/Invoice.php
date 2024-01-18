<?php

namespace App\Models;

use App\Models\Attributes\InvoicePaymentStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;
    protected $casts = [
        'invoice_date' => 'datetime',
        'due_date' => 'datetime',
        'items' => 'array',
    ];
    protected $guarded = [];
    protected $appends = ['invoice_number'];
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

    protected function invoiceNumber(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => 'INV000'.$attributes['id'],
        );
    }

    protected function paymentStatus(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => (new InvoicePaymentStatus(
                $attributes['payment_status'],$attributes['due_date']
            ))->execute(),
        );
    }

//    protected function items(): Attribute
//    {
//        return Attribute::make(
//            get: fn (mixed $value, array $attributes) => 'INV000'.$attributes['id'],
//        );
//    }
}

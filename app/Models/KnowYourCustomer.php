<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnowYourCustomer extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    const PENDING = 0;

    const ACTIVE = 1;

    const SUSPENDED = 2;

    const BLOCKED = 3;

    const CONTACT_ADMIN = 4;

    const FRAUD = 5;

    const FAILED = 6;

    protected $fillable = [
        'customer_id',
        'bvn',
        'doc_type',
        'doc_image',
        'selfie',
        'status',
        'utility_bill',
        'bvn_validation_payload',
        'date_attempted_account_generation'
    ];

    protected $casts = [
        'date_attempted_account_generation' => 'datetime'
    ];

    const STATUS_CODES = [
        0, 1, 2, 3, 4, 5, 6,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    protected function bvn(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => base64_decode($value),
        );
    }
}

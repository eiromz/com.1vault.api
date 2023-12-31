<?php

namespace App\Models;

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
    ];

    const STATUS_CODES = [
        0, 1, 2, 3, 4, 5, 6,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}

//Service can be added to cart
//Cart can be paid for using

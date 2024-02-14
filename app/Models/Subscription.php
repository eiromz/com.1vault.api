<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory,HasUuids,SoftDeletes;

    protected $guarded = [];

    protected $appends = ['is_active'];

    protected $casts = [
        'subscription_date' => 'date',
        'expiration_date' => 'date',
        'cancel_subscription' => 'boolean'
    ];

    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => !Carbon::parse($attributes['expiration_date'])->isPast(),
        );
    }

    public  function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}

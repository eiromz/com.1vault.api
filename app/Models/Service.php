<?php

namespace App\Models;

use App\Models\Attributes\ServiceAmount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory,HasUuids,SoftDeletes;

    protected $casts = [
        'benefit' => 'array',
        'status' => 'boolean',
        'is_request' => 'boolean',
        'is_recurring' => 'boolean',
        'requires_payment' => 'boolean',
        'discount' => 'integer',
        'has_discount' => 'boolean',
        'quantity' => 'integer',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('status', '=', true);
        });
    }

    public function serviceBenefit(): HasMany
    {
        return $this->hasMany(ServiceBenefit::class);
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => (new ServiceAmount(
                $attributes['amount'], $attributes['discount'], $attributes['has_discount']
            ))->execute(),
        );
    }

    public function subscription(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory,HasUuids,SoftDeletes;

    protected $casts = [
        'benefit' => 'array'
    ];
    public function serviceBenefit(): HasMany
    {
        return $this->hasMany(ServiceBenefit::class);
    }
}

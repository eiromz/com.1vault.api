<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Business extends Model
{
    use HasFactory,HasUuids;

    protected $guarded = [];

    protected $with = ['customer', 'state'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}

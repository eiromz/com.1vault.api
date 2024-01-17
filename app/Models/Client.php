<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory, HasUuids;

    protected $with = ['customer', 'collaborator', 'state'];

    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function collaborator(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}

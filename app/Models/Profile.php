<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory,HasUuids;

    protected $guarded = [];

    const DOC_TYPES = ['drivers_license', 'international_passport', 'voters_card'];

    protected $with = ['state'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $appends = ['fullname'];

    protected function fullname(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => ($attributes['firstname'].' '.$attributes['lastname']),
        );
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function doctypes()
    {
        $collection = collect(Profile::DOC_TYPES);

        return $collection->jsonSerialize();
    }
}

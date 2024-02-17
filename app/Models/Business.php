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

    protected $casts = [
        'is_store_front' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function categories()
    {
        $collection = collect([
            'sole owner', 'partnership', 'limited liability company', 'public limited company', 'others',
        ]);

        return $collection->values();
    }

    public function businessSector()
    {
        $collection = collect([
            'fashion', 'gadgets', 'election', 'home/decoration/garden', 'Sports & Outdoors', 'Baby & Kids',
            'Pet Supplies', 'Books', 'Beauty & Personal Care', 'Health & Wellness', 'Industrial & Scientific',
            'Office Supplies', 'Food & Beverages',
        ]);

        return $collection->values();
    }
}

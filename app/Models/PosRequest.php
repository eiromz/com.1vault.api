<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosRequest extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'primary_contact_person' => 'array',
        'secondary_contact_person' => 'array',
        'pos_locations' => 'array',
    ];

    public function sectors()
    {
        $collection = collect([
            'store/supermarket', 'restaurants', 'wholesale/distributor', 'telecoms', 'fuel station',
            'fast food', 'hotel/guest house', 'logistics', 'church/ngo', 'hospital', 'airlines', 'travel agencies',
            'embassy', 'education/schools', 'others',
        ]);

        return $collection->values();
    }

    public function businessTypes()
    {
        $collection = collect([
            'sole owner', 'partnership', 'limited liability company', 'public limited company', 'others',
        ]);

        return $collection->values();
    }

    public function cardTypes()
    {
        $collection = collect([
            'local card', 'international mastercard', 'international visa card',
        ]);

        return $collection->values();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvidusDataStore extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = ['session_id', 'active', 'payload', 'settlement_id'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProvidusDataStore extends Model
{
    use HasFactory,HasUuids,SoftDeletes;
    protected $fillable = ['session_id', 'processed', 'payload', 'settlement_id','account_number'];
}

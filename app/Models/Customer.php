<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'is_owner',
        'is_member',
        'otp',
        'otp_expires_at',
        'accept_terms_conditions',
        'role',
        'referral_code',
        'account_number',
        'ACCOUNTID',
        'status'
    ];

    protected $table = 'customers';

    CONST OWNER_ABILITIES = [
        'wallets','customer','services'
    ];

    CONST COLLABORATOR_ABILITIES = [
        'customer','services'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'transaction_pin',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function knowYourCustomer()
    {
        return $this->hasMany(KnowYourCustomer::class,'auth_user_id');
    }
}

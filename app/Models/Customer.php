<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, HasUuids,Notifiable,SoftDeletes;

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
        'phone_number',
        'firebase_token',
        'image',
        'ACCOUNTID',
        'status',
    ];

    protected $table = 'customers';

    const OWNER_ABILITIES = [
        'wallets', 'customer', 'services', 'staff', 'invoices', 'services',
    ];

    const COLLABORATOR_ABILITIES = [
        'customer', 'services', 'invoices',
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

    protected $with = ['account', 'knowYourCustomer', 'profile'];

    public function knowYourCustomer(): HasOne
    {
        return $this->hasOne(KnowYourCustomer::class, 'customer_id')->latest();
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'customer_id');
    }

    public function account(): HasOne
    {
        return $this->hasOne(Account::class);
    }
}

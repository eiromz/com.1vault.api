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
    protected $guarded = [];

    protected $table = 'customers';

    const OWNER_ABILITIES = [
        'wallets', 'customer', 'services', 'staff', 'invoices', 'services',
    ];

    const EMPLOYEE_ABILITIES = [
        'customer', 'services', 'invoices',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
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
        'is_member' => 'boolean',
        'is_owner' => 'boolean',
        'accept_terms_conditions' => 'boolean',
        'can_receive_notification' => 'boolean',
        'can_receive_subscription_reminder' => 'boolean',
    ];

    protected $with = ['account'];

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'customer_id');
    }

    public function account(): HasOne
    {
        return $this->hasOne(Account::class);
    }

    public function knowYourCustomer(): HasOne
    {
        return $this->hasOne(KnowYourCustomer::class, 'customer_id')->latest();
    }

    public function storeFront(): HasOne
    {
        return $this->hasOne(StoreFront::class)
            ->where('is_store_front', '=', true);
    }

    public function business(): HasOne
    {
        return $this->hasOne(Business::class)
            ->where('is_store_front', '=', false)->latest();
    }
}

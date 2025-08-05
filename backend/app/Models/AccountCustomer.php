<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AccountCustomer extends Authenticatable implements JWTSubject
{
    //
    use Notifiable, AuthenticatableTrait;
    protected $table = 'account_customer';
    protected $fillable = [
       'name', 'number_phone', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }

}

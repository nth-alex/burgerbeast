<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'sex',
        'password',
        'bonus'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'bonus' => 'integer'
    ];

    protected $with = ['addresses'];
    protected $appends = ['notifications'];
    protected $attributes = ['is_admin' => 0, 'sex' => 'male'];

    public function addresses()
    {
        return $this->hasMany('App\Address');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function getNotificationsAttribute()
    {
        return $this->notifications()->get();
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }
}

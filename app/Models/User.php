<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'avatar',
        'twitch_synced_at',
    ];

    protected $hidden = [
        'twitch_token',
        'twitch_remember_token',
        'remember_token',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'broadcaster_id');
    }

}

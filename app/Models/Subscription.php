<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function subscriber()
    {
        return $this->belongsTo(User::class, 'subscriber_id');
    }

    public function gifter()
    {
        return $this->belongsTo(User::class, 'gifter_id');
    }
}

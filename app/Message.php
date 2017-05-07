<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'description', 'time_sent', 'owner_id', 'event_id'
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];
}

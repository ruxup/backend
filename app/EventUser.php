<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventUser extends Model
{
    use SoftDeletes;
    protected $table = 'eventuser';
    protected $dates = ['deleted_at'];
    public $timestamps = false;
}

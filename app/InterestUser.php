<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterestUser extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'interest_id',
    ];
    protected $table = 'interestuser';
    protected $dates = ['deleted_at'];
    public $timestamps = false;
}

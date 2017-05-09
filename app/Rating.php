<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'rater_id', 'ratee_id', 'star'
    ];

    protected $table = 'rating';
    protected $dates = ['deleted_at'];
    public $timestamps = false;
}

<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
       'name', 'email', 'password', 'created_at', 'updated_at'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    protected $table = 'users';

    public function events()
    {
        return $this->belongsToMany('App\Event', 'eventuser')
            ->withPivot('joined_at', 'active');
    }

}

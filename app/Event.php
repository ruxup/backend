<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'location', 'start_time', 'end_time', 'category', 'description', 'image', 'owner_id',
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany('App\User', 'eventuser')
            ->withPivot('joined_at', 'active');
    }

    public static function getTableColumns() {
        return Schema::getColumnListing(config('constants.events_table'));
    }
}

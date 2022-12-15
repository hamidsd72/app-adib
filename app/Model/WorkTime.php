<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WorkTime extends Model
{
    protected $table = 'adib_it_work_times';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user__id');
    }

    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commendable');
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($check) {
            $check->comments()->delete();
        });
    }

}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TimesheetCircle extends Model {
    
    protected $table = 'adib_it_timesheet_circles';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function timesheet() {
        return $this->belongsTo('App\Model\WorkTimesheet', 'timesheet_id');
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($check) {
            $check->comments()->delete();
        });
    }

}

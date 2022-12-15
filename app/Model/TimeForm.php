<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TimeForm extends Model {

    protected $table = 'adib_it_time_forms';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function user_status() {
        return $this->belongsTo('App\User', 'status_user_id');
    }

}

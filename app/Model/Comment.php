<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

    protected $table = 'adib_it_comments';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user() {
        return $this->belongsTo('App\User', 'user__id');
    }

    public function commendable() {
        return $this->morphTo();
    }

    public function libraries() {
        return $this->morphMany('App\Model\Library', 'librariable');
    }

    public function ticket() {
        return $this->belongsTo('App\Model\Ticket','commendable_id','id');
    }

    public function scopeGetYesterday($query) {
        return $query->where('created_at', '>=', Carbon::yesterday()->startOfDay())->where('created_at', '<=', Carbon::yesterday()->endOfDay());
    }

}


<?php

namespace App\Model;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class RollCall extends Model
{
    protected $table = 'roll_calls';

    protected $fillable = [
        "user_id",
        "reagent_id",
        "updated_at",
        "text",
    ];

    public function user() {
        return $this->belongsTo('App\User','user_id')->first();
    }
    // public function TodayRollCall() {
    //     return $this->belongsTo('App\User','user_id')->where('created_at', Carbon::now()->today())->first();
    // }
    // public function TodayRollCallCompany() {
    //     return $this->hasMany('App\User','reagent_id')->where('created_at', Carbon::now()->today())->get();
    // }
}

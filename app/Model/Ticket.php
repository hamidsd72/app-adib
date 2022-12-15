<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
 
class Ticket extends Model {

    protected $table = 'adib_it_tickets';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function hours() {
        return $this->hasMany('App\Model\Hour', 'ticket_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user__id');
    }

    public function referred() {
        return $this->belongsTo('App\User', 'referred_to')->first('name');
    }

    public function creator() {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function user_role() {
        return $this->belongsTo('App\Model\Role', 'role_id');
    }

    public function role() {
        return $this->belongsTo('App\Model\Role', 'role__id')->first('description');
    }

    public function libraries() {
        return $this->morphMany('App\Model\Library', 'librariable');
    }

    public function comments() {
        return $this->hasMany('App\Model\Comment', 'commendable_id')->orderBy('created_at');
    }

    public function scopeGetYesterday($query) {
        $data = $query->where('created_at', '>=', Carbon::yesterday()->startOfDay())->where('created_at', '<=', Carbon::yesterday()->endOfDay())
        ->orWhere('updated_at', '>=', Carbon::yesterday()->startOfDay())->where('updated_at', '<=', Carbon::yesterday()->endOfDay());
        return $data;
    }

    // protected $guarded = ['id', 'created_at', 'updated_at'];
    // public function parent()
    // {
    //     return $this->hasOne('App\Model\Ticket', 'parent_id');
    // }
    // public function children()
    // {
    //     return $this->hasMany('App\Model\Ticket', 'parent_id')->orderBy('id', 'DESC');
    // }
    // public function user_create()
    // {
    //     return $this->belongsTo('App\User', 'create_user_id');
    // }
    // public function user_edit()
    // {
    //     return $this->belongsTo('App\User', 'update_user_id');
    // }
    // public function files()
    // {
    //     return $this->morphMany('App\Model\Filep', 'files');
    // }
}
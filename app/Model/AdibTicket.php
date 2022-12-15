<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ticket extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function hours()
    {
        return $this->hasMany('App\Models\Hour', 'ticket_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user__id');
    }
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'creator_id');
    }
    public function user_role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }

    public function libraries()
    {
        return $this->morphMany('App\Models\Library', 'librariable');
    }

    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commendable')->orderBy('created_at');
    }

    public function scopeGetYesterday($query)
    {
        $data = $query->where('created_at', '>=', Carbon::yesterday()->startOfDay())->where('created_at', '<=', Carbon::yesterday()->endOfDay())
        ->orWhere('updated_at', '>=', Carbon::yesterday()->startOfDay())->where('updated_at', '<=', Carbon::yesterday()->endOfDay());
        return $data;
    }
}

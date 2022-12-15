<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Help extends Model {

    protected $table = 'adib_it_helps';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function expert()
    {
        return $this->belongsTo('App\User', 'expert_id');
    }

    public function libraries()
    {
        return $this->morphMany('App\Model\Library', 'librariable');
    }

    public function comments()
    {
        return $this->hasMany('App\Model\VisitComment','visit_id');
    }

    public function doneJob()
    {
        return $this->hasMany('App\Model\VisitDoneJob','visit_id');
    }

}

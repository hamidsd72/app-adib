<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class ServiceLevel extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public function questions()
    {
        return $this->hasMany('App\Model\ServiceQuery','level_id');
    }
    public function questions_active()
    {
        return $this->hasMany('App\Model\ServiceQuery','level_id')->where('status','active');
    }
    public function service()
    {
        return $this->belongsTo('App\Model\Service','service_id');
    }
}

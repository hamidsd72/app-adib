<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    protected $table = 'adib_it_works';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function referrer()
    {
        return $this->belongsTo('App\User', 'referrer_id');
    }

    public function company()
    {
        return $this->belongsTo('App\User', 'company_id');
    }

}

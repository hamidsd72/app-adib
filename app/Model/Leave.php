<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model {
    protected $table = 'adib_it_leaves';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user__id');
    }

}

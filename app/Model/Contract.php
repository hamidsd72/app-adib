<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model {

    protected $table = 'adib_it_contracts';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user() {
        return $this->belongsTo('App\User', 'user__id');
    }
}

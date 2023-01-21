<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Hour extends Model {
    
    protected $table = 'adib_it_hours';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function ticket() {
        return $this->hasOne('App\Model\Ticket', 'id',  'ticket_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

}

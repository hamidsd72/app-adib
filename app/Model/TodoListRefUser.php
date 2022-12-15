<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TodoListRefUser extends Model {

    protected $table = 'adib_it_todo_list_ref_users';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function cat() {
        return $this->belongsTo('App\Model\TodoListRefUserCat', 'cat_id');
    }

    public function user_create() {
        return $this->belongsTo('App\User', 'user_id_create');
    }

}

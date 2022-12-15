<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TodoListRefUserCat extends Model {

    protected $table = 'adib_it_todo_list_ref_user_cats';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function users_ref() {
        return $this->hasMany('App\Model\TodoListRefUser', 'cat_id');
    }

    public function user_create() {
        return $this->belongsTo('App\User', 'user_id_create');
    }

}

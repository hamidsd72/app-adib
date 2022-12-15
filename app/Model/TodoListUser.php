<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TodoListUser extends Model {
    
    protected $table = 'adib_it_todo_list_users';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function todo_list_cat() {
        return $this->belongsTo('App\Model\TodoList', 'cat_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function user_create() {
        return $this->belongsTo('App\User', 'user_create_id');
    }

}

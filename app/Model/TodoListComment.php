<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TodoListComment extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function todo_list()
    {
        return $this->belongsTo('App\Model\TodoList', 'todo_list_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function libraries()
    {
        return $this->morphMany('App\Model\Library', 'librariable');
    }
}

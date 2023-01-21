<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TodoListCat extends Model {
    
    protected $table = 'adib_it_todo_list_cats';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function todo_lists() {
        return $this->hasMany('App\Model\TodoList', 'cat_id');
    }
    public function todo_list_checks() {
        return $this->hasMany('App\Model\TodoListChek', 'cat_id');
    }
    public function todo_list_key_word() {
        return $this->hasOne('App\Model\TodoListChek', 'cat_id')->where('tab_id',0);
    }
    public function users_cc() {
        return $this->hasMany('App\Model\TodoListUser', 'cat_id');
    }

    public static function roles_set($item) {
        $res='';
        $role_count=Role::count();
        $roles=Role::whereIN('id',json_decode($item))->get();
        if(count($roles)<$role_count) {
            foreach ($roles as $key=>$role) {
                $res.=$key>0?',':'';
                $res.=$role->description;
            }
        }
        else {
            $res='همه';
        }
        return $res;
    }

}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    // protected $table = 'roles';
    protected $table = 'adib_it_roles';

    public function users() {
        return $this->hasMany('App\User', 'role_id', 'id');
    }

    // protected $fillable = [
    //     "id",
    //     "name",
    //     "guard_name",
    //     "created_at",
    //     "updated_at"
    // ];

}


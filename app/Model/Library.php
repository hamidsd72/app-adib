<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Library extends Model {

    protected $table = 'adib_it_libraries';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function librariable() {
        return $this->morphTo();
    }
}

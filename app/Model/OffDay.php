<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OffDay extends Model
{
    protected $table = 'off_days';

    protected $fillable = [
        "id",
        "user_id",
        "title",
        "date",
    ];

}


<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class OffCode extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public static function user($type)
    {
        switch ($type){
            case '0':
                return 'همه کاربران';
                break;
            default:
                $user=\App\User::find($type);
                if($user)
                {
                    return $user->first_name.' '.$user->last_name;
                }
                else {
                    return 'کاربر پاک شده';
                }
                break;
        }
    }
}

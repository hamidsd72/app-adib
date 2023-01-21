<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name', 'name', 'email', 'password',
        'company__name', 'company__phone', 'company__fax',
        'company__telegram', 'company__address', 'company__site',
        'company__manager_phone', 'company__representative_name',
        'company__representative_phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $hourDate;

    //Relation

    public function role()
    {
        return $this->hasOne('App\Models\Role', 'id', 'role_id');
    }

    public function projects()
    {
        return $this->hasMany('App\Models\Project', 'user__id');
    }

    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket', 'user__id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'user__id');
    }

    public function domains()
    {
        return $this->hasMany('App\Models\Domain', 'user__id');
    }
    
    public function company()
    {
        return $this->hasMany('App\Models\Company', 'user__id');
    }

    public function leaves()
    {
        return $this->hasMany('App\Models\Leave', 'user__id');
    }

    public function sales()
    {
        return $this->hasMany('App\Models\Sale', 'user__id');
    }

    public function hosts()
    {
        return $this->hasMany('App\Models\Host', 'user__id');
    }

    public function contracts()
    {
        return $this->hasMany('App\Models\Contract', 'user__id');
    }
    public function phases()
    {
        return $this->hasMany('App\Models\Phase', 'user__id');
    }
    public function openPhases()
    {
        return $this->hasMany('App\Models\Phase', 'user__id')->where('phase__percent', '!=', 100);
    }

    public function traffics()
    {
        return $this->hasMany('App\Models\Traffic', 'user__id');
    }

    public function userSeo()
    {
        return $this->hasMany('App\Models\UserSeo', 'user_id');
    }

    public function hasRole($roles)
    {
        $this->have_role = $this->getUserRole();

        if ($this->have_role->name == 'Management') {
            return true;
        }
        if (is_array($roles)) {
            foreach ($roles as $need_role) {
                if ($this->checkIfUserHasRole($need_role)) {
                    return true;
                }
            }
        } else {
            return $this->checkIfUserHasRole($roles);
        }
        return false;
    }

    private function getUserRole()
    {
        return $this->role()->getResults();
    }

    private function checkIfUserHasRole($need_role)
    {
        return (strtolower($need_role) == strtolower($this->have_role->name)) ? true : false;
    }

    public function startHour()
    {
        return $this->hasMany('App\Models\WorkTime', 'user_id');
    }
    public function workTimesheet()
    {
        $today=Carbon::now();
        $date=$today->format('Y-m-d');
        return $this->hasMany('App\Models\WorkTimesheet', 'user_id')->where('startDate',$date);
    }
    public function workTimesheets()
    {
        return $this->hasMany('App\Models\WorkTimesheet', 'user_id');
    }

}

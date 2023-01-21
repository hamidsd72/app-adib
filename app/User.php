<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Model\Sms;


class User extends Authenticatable {
    use Notifiable;
    use HasRoles;

    protected $hidden = ['password', 'remember_token',];

    public $hourDate;

    protected $table = 'adib_it_users';

    protected $fillable = ['user_name','name','email','company__name','company__phone',
    'company__fax','company__telegram','company__address','company__site','company__manager_phone',
    'company__representative_name','company__representative_phone','password','referred_to','suspended',
    'draft_permission','mohr','emza','profile',];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
    public function photo()
    {
        return $this->morphOne('App\Model\Photo', 'pictures');
    }
    public function file()
    {
        return $this->morphOne('App\Model\Filep', 'files');
    }
    public function setting()
    {
        return $this->hasMany('App\Model\Setting','user_id');
    }
    public function about()
    {
        return $this->hasMany('App\Model\About','user_id');
    }
    public function state()
    {
        return $this->belongsTo('App\Model\ProvinceCity','state_id');
    }
    public function city()
    {
        return $this->belongsTo('App\Model\ProvinceCity','city_id');
    }
    public function reagent()
    {
        return $this->hasOne('App\User','reagent_id','reagent_code');
    }
    public function marketer()
    {
        return $this->belongsTo('App\Model\Marketer','id','user_id');
    }
    public function agent()
    {
        return $this->belongsTo('App\Model\Agent','id','user_id');
    }
    public function role()
    {
        return $this->belongsTo('App\Model\Role','role_id')->first(['id','name','description']);
    }
    public function works()
    {
        return $this->belongsTo('App\Model\Work','id','user_id')->orderByDesc('updated_at')->get();
    }

    public function projects()
    {
        return $this->hasMany('App\Model\Project', 'user__id');
    }

    public function tickets()
    {
        // return $this->hasMany('App\Model\Ticket', 'user__id');
        return $this->hasMany('App\Model\Ticket', 'referred_to')->where('ticket__status','doing');
    }

    public function sorted_tickets()
    {
        return $this->hasMany('App\Model\Ticket', 'referred_to')->where('ticket__status','doing')->orderBy('seen__id')->get();
    }

    public function comments()
    {
        return $this->hasMany('App\Model\Comment', 'user__id');
    }

    public function domains()
    {
        return $this->hasMany('App\Model\Domain', 'user__id');
    }
    
    public function company()
    {
        return $this->hasMany('App\Model\Company', 'user__id');
    }

    public function leaves()
    {
        return $this->hasMany('App\Model\Leave', 'user__id');
    }

    public function leavesItems()
    {
        return $this->hasMany('App\Model\Leave', 'id', 'user__id');
    }

    public function sales()
    {
        return $this->hasMany('App\Model\Sale', 'user__id');
    }

    public function hosts()
    {
        return $this->hasMany('App\Model\Host', 'user__id');
    }

    public function contracts()
    {
        return $this->hasMany('App\Model\Contract', 'user__id');
    }
    public function contract()
    {
        return $this->hasOne('App\Model\Contract', 'user__id')->where('active',1);
    }
    public function phases()
    {
        return $this->hasMany('App\Model\Phase', 'user__id');
    }
    public function openPhases()
    {
        return $this->hasMany('App\Model\Phase', 'user__id')->where('phase__percent', '!=', 100);
    }

    public function traffics()
    {
        return $this->hasMany('App\Model\Traffic', 'user__id');
    }

    public function userSeo()
    {
        return $this->hasMany('App\Model\UserSeo', 'user_id');
    }

    public function readNotify()
    {
        return $this->hasMany('App\Model\Notification','notifiable_id')->whereNotNull('read_at')
        ->where('type','App\Notification\TodoList\TodoListNotification')->where('notifiable_type','App\Models\User');
    }

    public function unreadNotify()
    {
        return $this->hasMany('App\Model\Notification','notifiable_id')->whereNull('read_at')
        ->where('type','App\Notification\TodoList\TodoListNotification')->where('notifiable_type','App\Models\User');
    }

    // public function hasRole($roles)
    // {
    //     $this->have_role = $this->getUserRole();

    //     if ($this->have_role->name == 'Management') {
    //         return true;
    //     }
    //     if (is_array($roles)) {
    //         foreach ($roles as $need_role) {
    //             if ($this->checkIfUserHasRole($need_role)) {
    //                 return true;
    //             }
    //         }
    //     } else {
    //         return $this->checkIfUserHasRole($roles);
    //     }
    //     return false;
    // }

    // private function getUserRole()
    // {
    //     return $this->role()->getResults();
    // }

    private function checkIfUserHasRole($need_role)
    {
        return (strtolower($need_role) == strtolower($this->have_role->name)) ? true : false;
    }

    public function startHour()
    {
        return $this->hasMany('App\Model\WorkTime', 'user_id');
    }
    public function workTimesheet()
    {
        $today=Carbon::now();
        $date=$today->format('Y-m-d');
        return $this->hasMany('App\Model\WorkTimesheet', 'user_id')->where('startDate',$date);
    }
    public function workTimesheets()
    {
        return $this->hasMany('App\Model\WorkTimesheet', 'user_id');
    }
    
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($item) {
            $item->photo()->get()
                ->each(function ($photo) {
                    $path = $photo->path;
                    File::delete($path);
                    $photo->delete();
                });
            $item->file()->get()
                ->each(function ($file) {
                    $path = $file->path;
                    File::delete($path);
                    $file->delete();
                });
        });
    }

}

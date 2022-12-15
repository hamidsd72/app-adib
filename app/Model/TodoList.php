<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TodoList extends Model {

    protected $table = 'adib_it_todo_lists';
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function parent() {
        return $this->belongsTo('App\Model\TodoList', 'parent_id');
    }

    public function childs() {
        return $this->hasMany('App\Model\TodoList', 'parent_id');
    }

    public function cat() {
        return $this->belongsTo('App\Model\TodoListCat', 'cat_id');
    }

    public function company_user() {
        return $this->belongsTo('App\User', 'company_id');
    }

    public function company_contract() {
        return $this->belongsTo('App\Model\Contract', 'contract_id');
    }

    public function group_ref() {
        return $this->belongsTo('App\Model\TodoListRefUserCat', 'user_id');
    }

    public function group_ref_user() {
        return $this->belongsTo('App\User', 'user_group_id');
    }

    public static function group_ref_user_percent($id,$user_id,$type) {
        $all=TodoListRefUser::where('cat_id',$id)->count();
        $item=TodoListRefUser::where('cat_id',$id)->where('user_id',$user_id)->first();
        $end=TodoListRefUser::where('cat_id',$id)->where('sort','>',$item->sort)->first()?false:true;
        $to_next=TodoListRefUser::where('sort','<',$item->sort)->count();
        $percent_one=round(100/$all);

        if($end) {
            $percent=100;
        }
        elseif($to_next==0 && $type=='back') {
            $percent=0;
        }
        elseif($type=='back') {
            $percent=$to_next*$percent_one;
        }
        elseif($type=='ref') {
            $percent=$to_next+1*$percent_one;
        }
        return $percent;
    }

    public static function group_ref_user_next($id,$user_id) {
        $item=TodoListRefUser::where('cat_id',$id)->where('user_id',$user_id)->first();
        $next=TodoListRefUser::where('cat_id',$id)->where('sort','>',$item->sort)->orderBy('sort')->first();
        if($next) {
            $next_id=$next->user_id;
            $next_name=$next->user?$next->user->name:$next->user_id;
        }
        else {
            $next_id='end';
            $next_name='پایان';
        }

        return [$next_id,$next_name];
    }

    public static function group_ref_user_back($id,$user_id) {
        $item=TodoListRefUser::where('cat_id',$id)->where('user_id',$user_id)->first();
        $back=TodoListRefUser::where('cat_id',$id)->where('sort','<',$item->sort)->orderByDesc('sort')->get();
        return $back;
    }

    public function user_ref() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function user_create() {
        return $this->belongsTo('App\User', 'user_id_create');
    }

    public function comments() {
        return $this->hasMany('App\Model\TodoListComment', 'todo_list_id')->orderByDesc('id');
    }

    public static function status_set($status) {
        $res=null;
        switch ($status)  {
            case 'pending':
                $res='<span class="table-status table-pending">در انتظار شروع</span>';
                break;
            case 'doing':
                $res='<span class="table-status table-closed">در حال اجرا</span>';
                break;
            case 'stop':
                $res='<span class="table-status table-no-pay">متوقف</span>';
                break;
            case 'end':
                $res='<span class="table-status table-answered">اتمام</span>';
                break;
            case 'circle':
                $res='<span class="table-status table-finished">چرخشی</span>';
                break;
            case 'pending1':
                $res='در انتظار شروع';
                break;
            case 'doing1':
                $res='در حال اجرا';
                break;
            case 'stop1':
                $res='متوقف';
                break;
            case 'end1':
                $res='اتمام';
                break;
            case 'circle1':
                $res='چرخشی';
                break;
            default:
                $res=null;
                break;
        }
        return $res;
    }

    public static function priority_set($priority) {
        $res=null;
        switch ($priority) {
            case 'low':
                $res='<span class="table-status table-finished">کم</span>';
                break;
            case 'medium':
                $res='<span class="table-status table-pending">متوسط</span>';
                break;
            case 'top':
                $res='<span class="table-status table-no-pay">زیاد</span>';
                break;
            default:
                $res=null;
                break;
        }
        return $res;
    }

    public static function reminder_set($reminder) {
        $res=null;
        switch ($reminder) {
            case 'date':
                $res='تاریخ مشخص';
                break;
            case 'week':
                $res='هر هفته';
                break;
            case '2week':
                $res='هر 2 هفته';
                break;
            case 'month':
                $res='هر ماه';
                break;
            default:
                $res=null;
                break;
        }
        return $res;
    }

    public static function reminder_day($reminder) {
        $reminder=json_decode($reminder);
        $res=null;
        $res1=null;
        foreach ($reminder as $key=>$r)  {
            switch ($r) {
                case '0':
                    $res1='یکشنبه';
                    break;
                case '1':
                    $res1='دوشنبه';
                    break;
                case '2':
                    $res1='سه شنبه';
                    break;
                case '3':
                    $res1='چهار شنبه';
                    break;
                case '4':
                    $res1='پنج شنبه';
                    break;
                case '5':
                    $res1='جمعه';
                    break;
                case '6':
                    $res1='شنبه';
                    break;
            }
            $res.=$key>0?',':'';
            $res.=$res1;
        }

        return $res;
    }

    protected static function boot() {
        parent::boot();
        static::deleting(function ($item) {
            if(count($item->users_cc)) {
                foreach ($item->users_cc as $user)
                {
                    $user->delete();
                }
            }
            if(count($item->comments)) {
                foreach ($item->comments as $comment)
                {
                    $comment->delete();
                }
            }
        });
    }

}

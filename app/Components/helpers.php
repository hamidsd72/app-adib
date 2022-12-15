<?php

use App\Model\ChatId;
use App\Model\User;
use App\Model\TodoList;
use App\Model\TodoListComment;
use App\Model\TodoListRefUser;

use App\Notification\TodoList\TodoListNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Illuminate\Container\Container;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\Cookie\Factory as CookieFactory;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Broadcasting\Factory as BroadcastFactory;
use Telegram\Bot\Api;

if (!function_exists('todo_list_mali')) {
    function todo_list_mali($contract,$date,$price,$todo_id=0)
    {
        $user_group_id = TodoListRefUser::where('cat_id', 2)->orderBy('sort')->first();
        $title='سررسید پرداختی '.number_format($price).' ریال';
        if($todo_id==0)
        {
            $item = TodoList::create([
                'cat_id' => 2,
                'title' => $title,
                'price' => $price,
                'company_id' => $contract->user__id,
                'contract_id' => $contract->id,
                'type_ref' => 'multi',
                'user_id' => 2,
                'user_group_id' => $user_group_id ? $user_group_id->user_id : '',
                'text' => $title,
                'priority' => 'medium',
                'type_reminder' => 'date',
                'reminder' => Carbon::parse(j2g($date))->format('Y-m-d'),
                'reminder_fa' => $date,
                'percent' => 0,
                'status' => 'pending',
                'time' => 0,
                'user_id_create' => auth()->id(),
            ]);
            TodoListComment::create([
                'user_id' => auth()->id(),
                'todo_list_id' => $item->id,
                'text' => 'این فعالیت را ایجاد کرد',
                'change_item' => set_report_table($item),
            ]);

            $user_cat=TodoListRefUser::where('cat_id',$item->user_id)->select('user_id')->get()->toArray();
            $users=User::whereIN('id',$user_cat)->get();
            foreach ($users as $user)
            {
                $user->notifyNow(new TodoListNotification($item,'ایجاد فعالیت'));
            }
        }
        else
        {
            $item = TodoList::find($todo_id);
            $old=$item;
            if($item)
            {
                TodoList::where('id',$todo_id)->update([
                    'title' => $title,
                    'price' => $price,
                    'text' => $title,
                    'reminder' =>  Carbon::parse(j2g($date))->format('Y-m-d'),
                    'reminder_fa' => $date,
                ]);

                TodoListComment::create([
                    'user_id' => auth()->id(),
                    'todo_list_id' => $item->id,
                    'text' => 'این فعالیت را ویرایش کرد',
                    'change_item' => set_report_table($item,$old),
                ]);

                $user_cat=TodoListRefUser::where('cat_id',$item->user_id)->select('user_id')->get()->toArray();
                $users=User::whereIN('id',$user_cat)->get();
                foreach ($users as $user)
                {
                    $user->notifyNow(new TodoListNotification($item,'ویرایش فعالیت'));
                }
            }
        }
    }
}

if (!function_exists('todo_list_bimeh')) {
    function todo_list_bimeh($contract,$date,$type,$todo_id=0)
    {
        $user_group_id = TodoListRefUser::where('cat_id', 4)->orderBy('sort')->first();
        if($todo_id==0)
        {
            $title=$type=='start'?'پیگیری بیمه نامه شروع':'پیگیری بیمه نامه پایان';
            $item = TodoList::create([
                'cat_id' => 4,
                'title' => $title,
                'company_id' => $contract->user__id,
                'contract_id' => $contract->id,
                'type_ref' => 'multi',
                'user_id' => 4,
                'user_group_id' => $user_group_id ? $user_group_id->user_id : '',
                'text' => $title,
                'priority' => 'medium',
                'type_reminder' => 'date',
                'reminder' =>  Carbon::parse($date)->format('Y-m-d'),
                'reminder_fa' => g2j($date,'Y,m,d'),
                'percent' => 0,
                'status' => 'pending',
                'time' => 0,
                'user_id_create' => auth()->id(),
            ]);
            TodoListComment::create([
                'user_id' => auth()->id(),
                'todo_list_id' => $item->id,
                'text' => 'این فعالیت را ایجاد کرد',
                'change_item' => set_report_table($item),
            ]);

            $user_cat=TodoListRefUser::where('cat_id',$item->user_id)->select('user_id')->get()->toArray();
            $users=User::whereIN('id',$user_cat)->get();
            foreach ($users as $user)
            {
                $user->notifyNow(new TodoListNotification($item,'ایجاد فعالیت'));
            }
        }
        else
        {
            $item = TodoList::find($todo_id);
            $old=$item;
            if($item)
            {
                TodoList::where('id',$todo_id)->update([
                    'reminder' =>  Carbon::parse($date)->format('Y-m-d'),
                    'reminder_fa' => g2j($date,'Y,m,d'),
                ]);
                TodoListComment::create([
                    'user_id' => auth()->id(),
                    'todo_list_id' => $item->id,
                    'text' => 'این فعالیت را ویرایش کرد',
                    'change_item' => set_report_table($item,$old),
                ]);

                $user_cat=TodoListRefUser::where('cat_id',$item->user_id)->select('user_id')->get()->toArray();
                $users=User::whereIN('id',$user_cat)->get();
                foreach ($users as $user)
                {
                    $user->notifyNow(new TodoListNotification($item,'ویرایش فعالیت'));
                }
            }
        }
    }
}

if (!function_exists('todo_list_contract')) {
    function todo_list_contract($contract)
    {
        $date=Carbon::parse($contract->expire)->subDays(30)->format('Y-m-d');

        $user_group_id = TodoListRefUser::where('cat_id', 3)->orderBy('sort')->first();
        $item = TodoList::create([
            'cat_id' => 3,
            'title' => 'پیگیری قرارداد',
            'company_id' => $contract->user__id,
            'contract_id' => $contract->id,
            'type_ref' => 'multi',
            'user_id' => 3,
            'user_group_id' => $user_group_id ? $user_group_id->user_id : '',
            'text' => 'پیگیری قرارداد',
            'priority' => 'medium',
            'type_reminder' => 'date',
            'reminder' => $date,
            'reminder_fa' => my_jdate($date,'Y,m,d'),
            'percent' => 0,
            'status' => 'pending',
            'time' => 0,
            'user_id_create' => auth()->id(),
        ]);
        TodoListComment::create([
            'user_id' => auth()->id(),
            'todo_list_id' => $item->id,
            'text' => 'این فعالیت را ایجاد کرد',
            'change_item' => set_report_table($item),
        ]);

        $user_cat=TodoListRefUser::where('cat_id',$item->user_id)->select('user_id')->get()->toArray();
        $users=User::whereIN('id',$user_cat)->get();
        foreach ($users as $user)
        {
            $user->notifyNow(new TodoListNotification($item,'ایجاد فعالیت'));
        }
    }
}

if (!function_exists('todo_list_report')) {
    function todo_list_report($contract)
    {
        $date_start=Carbon::parse($contract->start_date);
        $date_end=Carbon::parse($contract->expire);
        $diff_month=$date_start->diffInMonths($date_end,false);
        $end_set=Carbon::parse('2000-01-01');
        $set_notifity=true;
        foreach (range(0,$diff_month) as $key)
        {
            $date=Carbon::parse($date_start)->addMonths($key)->format('Y-m-d');
            $date_fa_y=my_jdate($date,'Y');
            $date_fa_m=my_jdate($date,'m');
            if((int)$date_fa_m < 7)
            {
                $date_fa=$date_fa_y.','.$date_fa_m.',31';
            }
            elseif((int)$date_fa_m < 12)
            {
                $date_fa=$date_fa_y.','.$date_fa_m.',30';
            }
            else
            {
                $date_fa=$date_fa_y.','.$date_fa_m.',26';
            }
            $date_last=Carbon::parse(j2g($date_fa));
            if($date_last >= $date_end && $end_set < $date_end)
            {
                $date_fa=g2j($date_end,'Y,m,d');
            }
            elseif($date_last >= $date_end && $end_set == $date_end)
            {
                $set_notifity=false;
            }
            if($set_notifity)
            {
                $end_set=Carbon::parse(j2g($date_fa));
                $user_group_id = TodoListRefUser::where('cat_id', 6)->orderBy('sort')->first();

                $item = TodoList::create([
                    'cat_id' => 6,
                    'title' => 'گزارش ماهانه سئو',
                    'company_id' => $contract->user__id,
                    'contract_id' => $contract->id,
                    'type_ref' => 'multi',
                    'user_id' => 6,
                    'user_group_id' => $user_group_id ? $user_group_id->user_id : '',
                    'text' => 'گزارش ماهانه سئو',
                    'priority' => 'medium',
                    'type_reminder' => 'date',
                    'reminder' =>  Carbon::parse(j2g($date_fa))->format('Y-m-d'),
                    'reminder_fa' => $date_fa,
                    'percent' => 0,
                    'status' => 'pending',
                    'time' => 0,
                    'user_id_create' => auth()->id(),
                ]);
                TodoListComment::create([
                    'user_id' => auth()->id(),
                    'todo_list_id' => $item->id,
                    'text' => 'این فعالیت را ایجاد کرد',
                    'change_item' => set_report_table($item),
                ]);

                $user_cat=TodoListRefUser::where('cat_id',$item->user_id)->select('user_id')->get()->toArray();
                $users=User::whereIN('id',$user_cat)->get();
                foreach ($users as $user)
                {
                    $user->notifyNow(new TodoListNotification($item,'ایجاد فعالیت'));
                }
            }
        }
    }
}

if (!function_exists('todo_list_content_week')) {
    function todo_list_content_week($contract,$number)
    {
        $date_start=Carbon::parse($contract->start_date);
        $date_end=Carbon::parse($contract->expire);
        $diff_week=$date_start->diffInWeeks($date_end,false);

        foreach (range(0,$diff_week) as $key)
        {
            $date_week=Carbon::parse($date_start)->addWeeks($key)->format('Y-m-d');
            $date_start_week_en=Carbon::parse($date_week)->startOfWeek()->format('Y-m-d');
            $date=Carbon::parse($date_start_week_en)->subDays(2)->format('Y-m-d');
            if($date > $date_start && $date <= $date_end)
            {
                foreach (range(1,$number) as $nn)
                {
                    $user_group_id = TodoListRefUser::where('cat_id', 5)->orderBy('sort')->first();
                    $item = TodoList::create([
                        'cat_id' => 5,
                        'title' => 'تولید محتوا',
                        'company_id' => $contract->user__id,
                        'contract_id' => $contract->id,
                        'type_ref' => 'multi',
                        'user_id' => 5,
                        'user_group_id' => $user_group_id ? $user_group_id->user_id : '',
                        'text' => 'تولید محتوا',
                        'priority' => 'medium',
                        'type_reminder' => 'date',
                        'reminder' => $date,
                        'reminder_fa' => g2j($date,'Y,m,d'),
                        'percent' => 0,
                        'status' => 'pending',
                        'time' => 0,
                        'user_id_create' => auth()->id(),
                    ]);
                    TodoListComment::create([
                        'user_id' => auth()->id(),
                        'todo_list_id' => $item->id,
                        'text' => 'این فعالیت را ایجاد کرد',
                        'change_item' => set_report_table($item),
                    ]);

                    $user_cat=TodoListRefUser::where('cat_id',$item->user_id)->select('user_id')->get()->toArray();
                    $users=User::whereIN('id',$user_cat)->get();
                    foreach ($users as $user)
                    {
                        $user->notifyNow(new TodoListNotification($item,'ایجاد فعالیت'));
                    }
                }
            }
        }
    }
}

if (!function_exists('todo_list_content')) {
    function todo_list_content($contract,$number)
    {
        $date_start=Carbon::parse($contract->start_date);
        $date_start_content=Carbon::parse($contract->start_date)->addDays(1);
        $date_end=Carbon::parse($contract->expire);
        $diff_month=$date_start->diffInMonths($date_end,false);
        $text='تعداد کلمات کلیدی : '.$contract->seo_key_num;
        $text.=', کلمات کلیدی : ';
        $text.=$contract->seo_key;
        foreach (range(0,$diff_month) as $key)
        {
            $date=Carbon::parse($date_start_content)->addMonths($key)->format('Y-m-d');
            if($date > $date_start && $date <= $date_end)
            {
                foreach (range(1,$number) as $nn)
                {
                    $user_group_id = TodoListRefUser::where('cat_id', 5)->orderBy('sort')->first();
                    $item = TodoList::create([
                        'cat_id' => 5,
                        'title' => 'تولید محتوا',
                        'company_id' => $contract->user__id,
                        'contract_id' => $contract->id,
                        'type_ref' => 'multi',
                        'user_id' => 5,
                        'user_group_id' => $user_group_id ? $user_group_id->user_id : '',
                        'text' => $text,
                        'priority' => 'medium',
                        'type_reminder' => 'date',
                        'reminder' => $date,
                        'reminder_fa' => g2j($date,'Y,m,d'),
                        'percent' => 0,
                        'status' => 'pending',
                        'time' => 0,
                        'user_id_create' => auth()->id(),
                    ]);
                    TodoListComment::create([
                        'user_id' => auth()->id(),
                        'todo_list_id' => $item->id,
                        'text' => 'این فعالیت را ایجاد کرد',
                        'change_item' => set_report_table($item),
                    ]);

                    $user_cat=TodoListRefUser::where('cat_id',$item->user_id)->select('user_id')->get()->toArray();
                    $users=User::whereIN('id',$user_cat)->get();
                    foreach ($users as $user)
                    {
                        $user->notifyNow(new TodoListNotification($item,'ایجاد فعالیت'));
                    }
                }
            }
        }
    }
}

if (!function_exists('todo_list_instagram_post')) {
    function todo_list_instagram_post($contract)
    {
        $date_start=Carbon::parse($contract->start_date);
        $date_start_content=Carbon::parse($contract->start_date)->addDays(1);
        $date_end=Carbon::parse($contract->expire);
        $diff_month=$date_start->diffInMonths($date_end,false);
        foreach (range(0,$diff_month) as $key)
        {
            $date=Carbon::parse($date_start_content)->addMonths($key)->format('Y-m-d');
            if($date > $date_start && $date <= $date_end)
            {
                foreach (range(1,$contract->instagram_post_num) as $nn)
                {
                    $user_group_id = TodoListRefUser::where('cat_id', 5)->orderBy('sort')->first();
                    $item = TodoList::create([
                        'cat_id' => 8,
                        'title' => 'پست اینستاگرام',
                        'company_id' => $contract->user__id,
                        'contract_id' => $contract->id,
                        'type_ref' => 'multi',
                        'user_id' => 8,
                        'user_group_id' => $user_group_id ? $user_group_id->user_id : '',
                        'text' => 'پست اینستاگرام',
                        'priority' => 'medium',
                        'type_reminder' => 'date',
                        'reminder' => $date,
                        'reminder_fa' => g2j($date,'Y,m,d'),
                        'percent' => 0,
                        'status' => 'pending',
                        'time' => 0,
                        'user_id_create' => auth()->id(),
                    ]);
                    TodoListComment::create([
                        'user_id' => auth()->id(),
                        'todo_list_id' => $item->id,
                        'text' => 'این فعالیت را ایجاد کرد',
                        'change_item' => set_report_table($item),
                    ]);

                    $user_cat=TodoListRefUser::where('cat_id',$item->user_id)->select('user_id')->get()->toArray();
                    $users=User::whereIN('id',$user_cat)->get();
                    foreach ($users as $user)
                    {
                        $user->notifyNow(new TodoListNotification($item,'ایجاد فعالیت'));
                    }
                }
            }
        }
    }
}

if (!function_exists('todo_list_instagram_story')) {
    function todo_list_instagram_story($contract)
    {
        $date_start=Carbon::parse($contract->start_date);
        $date_start_content=Carbon::parse($contract->start_date)->addDays(1);
        $date_end=Carbon::parse($contract->expire);
        $diff_month=$date_start->diffInMonths($date_end,false);
        foreach (range(0,$diff_month) as $key)
        {
            $date=Carbon::parse($date_start_content)->addMonths($key)->format('Y-m-d');
            if($date > $date_start && $date <= $date_end)
            {
                foreach (range(1,$contract->instagram_story_num) as $nn)
                {
                    $user_group_id = TodoListRefUser::where('cat_id', 5)->orderBy('sort')->first();
                    $item = TodoList::create([
                        'cat_id' => 8,
                        'title' => 'استوری اینستاگرام',
                        'company_id' => $contract->user__id,
                        'contract_id' => $contract->id,
                        'type_ref' => 'multi',
                        'user_id' => 8,
                        'user_group_id' => $user_group_id ? $user_group_id->user_id : '',
                        'text' => 'استوری اینستاگرام',
                        'priority' => 'medium',
                        'type_reminder' => 'date',
                        'reminder' => $date,
                        'reminder_fa' => g2j($date,'Y,m,d'),
                        'percent' => 0,
                        'status' => 'pending',
                        'time' => 0,
                        'user_id_create' => auth()->id(),
                    ]);
                    TodoListComment::create([
                        'user_id' => auth()->id(),
                        'todo_list_id' => $item->id,
                        'text' => 'این فعالیت را ایجاد کرد',
                        'change_item' => set_report_table($item),
                    ]);

                    $user_cat=TodoListRefUser::where('cat_id',$item->user_id)->select('user_id')->get()->toArray();
                    $users=User::whereIN('id',$user_cat)->get();
                    foreach ($users as $user)
                    {
                        $user->notifyNow(new TodoListNotification($item,'ایجاد فعالیت'));
                    }
                }
            }
        }
    }
}

if (!function_exists('todo_notification')) {
    function todo_notification()
    {
        $week_num_today=Carbon::now()->dayOfWeek;
        $today=Carbon::now();

        $item_today=TodoList::where('type_reminder','date')->whereDate('reminder',$today)->whereIN('status',['doing','pending'])
            ->orWhere('type_reminder','week')->where('reminder','LIKE','%'.$week_num_today.'%')->whereIN('status',['doing','pending'])
            ->orderByDesc('id')->get();

        foreach ($item_today as $item)
        {
            if($item->type=='multi')
            {
                $user=User::find($item->user_group_id);
            }
            else
            {
                $user=User::find($item->user_id);
            }
            if ($user) {
                $user->notifyNow(new TodoListNotification($item,'یادآوری'));
            }
        }
    }
}
if (!function_exists('todo_before')) {
    function todo_before($item)
    {
        $check=false;

        $item_week_days=json_decode($item->reminder);
        $item_create=Carbon::parse($item->created_at)->format('Y-m-d');
        $item_end_work=blank($item->end_date_work)?null:Carbon::parse($item->end_date_work)->format('Y-m-d');

        $week_today=Carbon::now()->dayOfWeek;
        $today=Carbon::now()->format('Y-m-d');

//        dd($item_week_days,$item_create,$item_end_work,$week_today,$today);
        $check_day=null;
        foreach ($item_week_days as $day)
        {
            $diff=$week_today-$day;
            if($diff < 0)
                $day_date=Carbon::now()->addDays($diff)->format('Y-m-d');
            else
                $day_date=Carbon::now()->subDays($diff)->format('Y-m-d');

            if($day_date < $today)
            {
                if(blank($check_day) || $check_day < $day_date)
                    $check_day=$day_date;
            }
        }
        if(blank($item_end_work))
        {
            if($item_create < $check_day)
                $check=true;
        }
        else
        {
            if($item_end_work < $check_day)
                $check=true;
        }

        return $check;
    }
}

if (!function_exists('min2time')) {
    function min2time($date)
    {
       $hour=floor($date/60>10?$date/60:'0'.$date/60);
       $min=round($date%60>10?$date%60:'0'.$date%60);

       $time=$hour.':'.$min;
       return $time;
    }
}

if (!function_exists('j2g')) {
    function j2g($date)
    {
        $ymd = str_replace('-', '/', $date);
        $ymd = str_replace(',', '/', $ymd);
        $ymd = explode('/', $ymd);
        require_once('jdf.php');
        if (count($ymd) == 3) {
            $jalali_date = jalali_to_gregorian($ymd[0], $ymd[1], $ymd[2]);
            return implode('-', $jalali_date);
        }
    }
}
if (!function_exists('g2j')) {
    function g2j($date, $type)
    {
        $timestamp = (strtotime($date));
        require_once('jdf.php');
        $jalali_date = jdate($type, $timestamp);
        return $jalali_date;
    }
}
if (!function_exists('set_report_table')) {
    function set_report_table($item, $old_item=null)
    {
        if($old_item==null)
        {
            $res='';
            $res.=report_col_name('cat_id').': ';
            $res.=$item->cat?$item->cat->title:'';
            $res.='___';
            $res.=report_col_name('title').': ';
            $res.=$item->title;
            $res.='___';
            $res.=report_col_name('company_id').': ';
            $res.=$item->company_user?$item->company_user->name:'سایر';
            $res.='___';
            $res.=report_col_name('contract_id').': ';
            $res.=$item->company_contract?$item->company_contract->type:'سایر';
            $res.='___';
            $res.=report_col_name('type_ref').': ';
            $res.=$item->type_ref=='one'?'تکی':'گروهی';
            $res.='___';
            $res.=report_col_name('user_id').': ';
            if($item->type_ref=='one')
            {
                $res.=$item->user_ref?$item->user_ref->name:'';
                $res.=$item->user_ref && $item->user_ref->role?'(':'';
                $res.=$item->user_ref && $item->user_ref->role?$item->user_ref->role->description:'';
                $res.=$item->user_ref && $item->user_ref->role?')':'';
            }
            else
            {
                $res.=$item->group_ref?$item->group_ref->title:'';
                $res.=$item->group_ref?'(گروه)':'';
            }
            $res.='___';
            $res.=report_col_name('priority').': ';
            $res.=$item->priority_set($item->priority);
            $res.='___';
            $res.=report_col_name('type_reminder').': ';
            $res.=$item->reminder_set($item->type_reminder);
            $res.='___';
            $res.=report_col_name('reminder').': ';
            $res.=$item->type_reminder=='date'?g2j($item->reminder,'Y/m/d'):$item->reminder_day($item->reminder);
            $res.='___';
            $res.=report_col_name('status').': ';
            $res.=$item->status_set($item->status);
            $res.='___';
            $res.=report_col_name('start_date').': ';
            $res.=$item->start_date?g2j($item->start_date,'Y/m/d'):'';
            $res.='___';
            $res.=report_col_name('end_date').': ';
            $res.=$item->end_date?g2j($item->end_date,'Y/m/d'):'';
            $res.='___';
            $res.=report_col_name('text').': ';
            $res.=$item->text;
            $res.='___';
        }
        else
        {
            $res='';
            foreach ($item->getChanges() as $key => $val) {
                if($key!='updated_at')
                {
                    if($key == 'cat')
                    {
                        $res.=report_col_name('cat_id').': ';
                        $res.=$item->cat?$item->cat->title:'';
                        $res.='___';
                    }
                    if($key == 'title')
                    {
                        $res .= report_col_name('title') . ': ';
                        $res .= $item->title;
                        $res .= '___';
                    }
                    if($key == 'company_id')
                    {
                        $res.=report_col_name('company_id').': ';
                        $res.=$item->company_user?$item->company_user->name:'سایر';
                        $res.='___';
                    }
                    if($key == 'contract_id')
                    {
                        $res.=report_col_name('contract_id').': ';
                        $res.=$item->company_contract?$item->company_contract->type:'سایر';
                        $res.='___';
                    }
                    if($key == 'type_ref')
                    {
                        $res.=report_col_name('type_ref').': ';
                        $res.=$item->type_ref=='one'?'تکی':'گروهی';
                        $res.='___';
                    }
                    if($key == 'user_id')
                    {
                        $res.=report_col_name('user_id').': ';
                        if($item->type_ref=='one')
                        {
                            $res.=$item->user_ref?$item->user_ref->name:'';
                            $res.=$item->user_ref && $item->user_ref->role?'(':'';
                            $res.=$item->user_ref && $item->user_ref->role?$item->user_ref->role->description:'';
                            $res.=$item->user_ref && $item->user_ref->role?')':'';
                        }
                        else
                        {
                            $res.=$item->group_ref?$item->group_ref->title:'';
                            $res.=$item->group_ref?'(گروه)':'';
                        }
                        $res.='___';
                    }
                    if($key == 'priority')
                    {
                        $res.=report_col_name('priority').': ';
                        $res.=$item->priority_set($item->priority);
                        $res.='___';
                    }
                    if($key == 'type_reminder')
                    {
                        $res.=report_col_name('type_reminder').': ';
                        $res.=$item->reminder_set($item->type_reminder);
                        $res.='___';
                    }
                    if($key == 'reminder')
                    {
                        $res.=report_col_name('reminder').': ';
                        $res.=$item->type_reminder=='date'?g2j($item->reminder,'Y/m/d'):$item->reminder_day($item->reminder);
                        $res.='___';
                    }
                    if($key == 'status')
                    {
                        $res.=report_col_name('status').': ';
                        $res.=$item->status_set($item->status);
                        $res.='___';
                    }
                    if($key == 'start_date')
                    {
                        $res.=report_col_name('start_date').': ';
                        $res.=$item->start_date?g2j($item->start_date,'Y/m/d'):'';
                        $res.='___';
                    }
                    if($key == 'end_date')
                    {
                        $res.=report_col_name('end_date').': ';
                        $res.=$item->end_date?g2j($item->end_date,'Y/m/d'):'';
                        $res.='___';
                    }
                    if($key == 'text')
                    {
                        $res.=report_col_name('text').': ';
                        $res.=$item->text;
                        $res.='___';
                    }
                    if($key == 'user_group_id' && !blank($item->user_group_id))
                    {
                        $res.=report_col_name('user_group_id').': ';
                        $res.=$item->group_ref_user?$item->group_ref_user->name:$item->group_ref_user;
                        $res.='___';
                    }
                    if($key == 'time' && !blank($item->time))
                    {
                        $res.=report_col_name('time').': ';
                        $res.=$item->time;
                        $res.='___';
                    }
                    if($key == 'percent' && !blank($item->percent))
                    {
                        $res.=report_col_name('percent').': ';
                        $res.=$item->percent.'%';
                        $res.='___';
                    }
                }
            }
        }

        return $res;
    }
}
if (!function_exists('report_col_name')) {
    function report_col_name($col)
    {
        $res=null;
        switch ($col)
        {
            case 'cat_id':
                $res='دسته فعالیت';
                break;
            case 'title':
                $res='عنوان';
                break;
            case 'company_id':
                $res='شرکت';
                break;
            case 'contract_id':
                $res='قرارداد';
                break;
            case 'type_ref':
                $res='نوع ارجاع';
                break;
            case 'user_id':
                $res='کاربر مسئول';
                break;
            case 'text':
                $res='توضیح';
                break;
            case 'priority':
                $res='اولویت';
                break;
            case 'type_reminder':
                $res='نوع زمان یادآوری';
                break;
            case 'reminder':
                $res='زمان یادآوری';
                break;
            case 'percent':
                $res='درصد پیشرفت';
                break;
            case 'status':
                $res='وضعیت';
                break;
            case 'start_date':
                $res='تاریخ شروع کار';
                break;
            case 'end_date':
                $res='تاریخ پایان کار';
                break;
            case 'time':
                $res='زمان صرف شده(دقیقه)';
                break;
            case 'end_date_work':
                $res='آخرین تاریخ انجام کار';
                break;
            case 'end_reminder_date':
                $res='آخرین تاریخ یادآوری';
                break;
            case 'user_group_id':
                $res='ارجاع به';
                break;
            default:
                $res=$col;
                break;
        }
        return $res;
    }
}
if (! function_exists('str_limit')) {
    /**
     * Limit the number of characters in a string.
     *
     * @param  string  $value
     * @param  int     $limit
     * @param  string  $end
     * @return string
     */
    function str_limit($value, $limit = 100, $end = '...')
    {
        return Str::limit($value, $limit, $end);
    }
}


if (!function_exists('role_set')) {
    function role_set($role)
    {
        $res='__';
        if($role==1){$res='مدیریت';}
        elseif($role==2){$res='بخش شبکه و سخت افزار';}
        elseif($role==3){$res='بخش مالی';}
        elseif($role==4){$res='بخش سایت و سئو';}
        elseif($role==5){$res='مشتری';}
        elseif($role==6){$res='بخش فروش';}
        elseif($role==7){$res='بخش اداری';}
        elseif($role==8){$res='بخش رضایت مشتری';}
        elseif($role==9){$res='مدیریت فنی';}
        return $res;
    }

}

if (!function_exists('time_expload')) {
    function time_expload($time,$type)
    {
        $item=explode(':',$time);
        $hour=$item[0]??0;
        $min=$item[1]??0;
        return $type=='min'?$min:$hour;
    }

}
if (!function_exists('iconCheck')) {
    function iconCheck($data)
    {
        if ($data == 'yes') {
            return '&#x2713;&nbsp;&nbsp;&nbsp;&nbsp;';
        } elseif ($data == 'داینامیک') {
            return 'داینامیک&nbsp;&#x2713;&nbsp;&nbsp;&nbsp;&nbsp;استاتیک&nbsp;&#x2613;&nbsp;&nbsp;&nbsp;&nbsp;';
        } elseif ($data == 'استاتیک') {
            return 'داینامیک&nbsp;&#x2613;&nbsp;&nbsp;&nbsp;&nbsp;استاتیک&nbsp;&#x2713;&nbsp;&nbsp;&nbsp;&nbsp;';
        } else {
            return '&#x2613;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
    }

}
if (!function_exists('userSection')) {
    function userSection()
    {
        return auth()->user()->role_id==5?'dashboard':'panel';
    }

}
if (!function_exists('user')) {
    function user($id)
    {
        $user = User::find($id);
        if (empty($user)) {
            return null;
        }
        return $user;
    }

}
if (!function_exists('my_contracts')) {
    function my_contracts()
    {
        // HOT CONTRACTS
        $contracts = \App\Models\Contract::where('active', 1)->where('user__id', Auth::user()->id)->whereDate('expire','>',date('Y-m-d 00:00:00'))->orderBy('id')->get();
        $hot_contracts = new Collection();
        foreach ($contracts as $contract) {
            $c = dateDiffDomain(date('Y-m-d 00:00:00'), $contract->expire);
            if ($c <= 30)
                $hot_contracts->push($contract);
        }
        return $hot_contracts;
    }
}
if (!function_exists('my_all_contracts')) {
    function my_all_contracts()
    {
        // HOT CONTRACTS
        $contracts = \App\Models\Contract::where('active', 1)->where('user__id', Auth::user()->id)->whereDate('expire','>',date('Y-m-d 00:00:00'))->orderBy('id')->get();
        return $contracts;
    }
}

if (!function_exists('devices_status')) {
    function devices_status($status)
    {
        switch ($status) {
            case 0:
                return '<span class="alert alert-danger">ارجاع به فنی</span>';
                break;
            case 1:
                return '<span class="alert alert-info">(ارجاع به مدیر فنی)سیستم حاضر است</span>';
                break;
            case 2:
                return '<span class="alert alert-warning">استعلام از مشتری</span>';
                break;
            case 3:
                return '<span class="alert alert-secondary">غیر قابل تعمیر</span>';
                break;
            case 4:
                return '<span class="alert alert-info">ارجاع به بخش مالی</span>';
                break;
            case 5:
                return '<span class="alert alert-success">انجام شده و آماده تحویل</span>';
                break;
            case 10:
                return '<span style="background-color: #eaff9f;color: #444444;" class="alert">بایگانی شده</span>';
                break;
            default:
                return 'نامشخص';
                break;
        }
    }

}

if (!function_exists('visit_status')) {
    function visit_status($status)
    {
        switch ($status) {
            case 0:
                return '<span class="alert alert-info">ارجاع به مدیر فنی</span>';
                break;
            case 1:
                return '<span class="alert alert-danger">ارجاع به کارشناس فنی</span>';
                break;
            case 2:
                return '<span class="alert alert-warning">استعلام از مشتری</span>';
                break;
            case 3:
                return '<span class="alert alert-secondary">غیر قابل تعمیر</span>';
                break;
            case 4:
                return '<span class="alert alert-info">ارجاع به بخش مالی</span>';
                break;
            case 5:
                return '<span class="alert alert-success">انجام شده و آماده تحویل</span>';
                break;
            case 10:
                return '<span style="background-color: #eaff9f;color: #444444;" class="alert">بایگانی شده</span>';
                break;
            default:
                return 'نامشخص';
                break;
        }
    }

}
if (!function_exists('help_status')) {
    function help_status($status)
    {
        switch ($status) {
            case 0:
                return '<span class="alert alert-info">ارجاع به رئیس شرکت</span>';
                break;
            case 1:
                return '<span class="alert alert-danger">ارجاع به کارشناس فنی</span>';
                break;
            case 2:
                return '<span class="alert alert-warning">استعلام از مشتری</span>';
                break;
            case 3:
                return '<span class="alert alert-secondary">غیر قابل تعمیر</span>';
                break;
            case 4:
                return '<span class="alert alert-info">ارجاع به بخش مالی</span>';
                break;
            case 5:
                return '<span class="alert alert-success">انجام شده و آماده تحویل</span>';
                break;
            case 10:
                return '<span style="background-color: #eaff9f;color: #444444;" class="alert">بایگانی شده</span>';
                break;
            default:
                return 'نامشخص';
                break;
        }
    }

}
if (!function_exists('send_mail')) {
    function send_mail ($email,$subject,$masage)
    {
        $n=new \App\Models\SendMail();
        $n->email=$email;
        $n->subject=$subject;
        $n->text=$masage;
        $n->save();
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: <noreply@adib-it.com>' . "\r\n";

        $msg= '<div style="width: 95%;min-height: 300px;margin: auto;position: relative;border: 1px solid #e1e1e1;direction: rtl">';
        $msg.= '<img src="https://adib-it.com/mail.png" style="width: 100%">';
        $msg.= '<div style="padding: 0 10px;text-align: justify">';

        $msg.=$masage;
        $msg.='</div>';
        $msg.='</div>';
        $msg.= '<div style="background: #dfedfb;height:160px;width: 100%;margin:auto;margin-top:20px;padding-top: 20px;box-shadow: 0 0 5px 1px">';
        $msg.= '<p style="text-align: center;font-weight: bold">
مجموعه ادیب با تمام توان در خدمت شماست</p>';
        $msg.= '<p style="text-align: center;font-weight: bold">';
        $msg.= '<a href="adib-it.com" style="color:#0000a4;font-size: 16px;border-bottom: unset!important;text-decoration: unset!important">adib-it.com</a>';
        $msg.= '</p>';
        $msg.= '<p style="text-align: center;font-weight: bold">';

        $msg.= '<a href="https://t.me/Amin_khansari" style="color:#0000a4;font-size: 16px;border-bottom: unset!important;text-decoration: unset!important"><img src="https://www.umevideo.com/includes/assets/icon/1telegram.ico" style="width: 20px"></a>';
        $msg.= '</p>';
        $msg.= '<p style="text-align: center;transform: rotate(5deg);font-weight: bold;margin-bottom: 0">طراحی و توسعه <a href="adib-it.com" style="color:#c40800;font-size: 16px;border-bottom: unset!important;text-decoration: unset!important">ADIB-IT</a></p>';
        $msg.= '</div>';
        mail($email,$subject, $msg , $headers);
        return "ok";
    }

}

if (!function_exists('addDraft')) {
    function addDraft($companyName, $companyPhone, $companyAddress, $companyCode, $companyEmail, $companyRepresentativeName, $companyRepresentativePhone, $subject, $time, $pay, $priceRial, $priceToman, $priceString, $official, $number, $type, $siteDesignType, $support, $domain, $picture, $brand, $enamad, $payment, $shop, $advari1, $advari2)
    {
        $peyvast = '';
        if ($type == 'طراحی سایت') {
            $peyvast = 'و دو پیوست فنی';
        } elseif ($type == 'طراحی سایت و سئو') {
            $peyvast = 'و سه پیوست فنی';
        } elseif ($type == 'سئو') {
            $peyvast = '';
        } elseif ($type == 'نگهداری شبکه') {
            $peyvast = 'و یک پیوست فنی';
        }

        if ($official == 'yes') {
            $companyNameMe = 'شرکت ادیب گستر عصر نوین';
            $companyCodeMe = 'با شماره ثبت 459291 و شناسه ملی 14004342247 و کد اقتصادی 411468115787 ';
            $companyCode = $companyCode;
            $companyTabMe = '<p style="direction: rtl; text-align: justify;">&nbsp;</p><p style="direction: rtl;">در انتهای پروژه و در فاکتور رسمی ارائه شده 9% بابت مالیات بر ارزش افزوده به کل قرارداد اضافه می گردد. که پرداخت آن بر عهده کارفرما می باشد.</p>
<p style="direction: rtl;">3-7&nbsp; از مبلغ کل قرارداد 5% جهت ارائه مفاساحساب بیمه نزد کارفرما باقی می ماند که پس از ارائه برگه مفاساحساب تسویه می گردد.</p>';
        } else {
            $companyNameMe = 'شرکت ادیب';
            $companyCodeMe = '';
            $companyCode = '';
            $companyTabMe = '<p style="direction: rtl; text-align: justify;">&nbsp;</p>';
        }
        if ($type == 'سئو' or $type == 'طراحی سایت و سئو') {
            $seo = '<p style="direction: rtl; text-align: justify;"><b>خدمات پیمانکار:</b></p><ul style="direction: rtl;text-align: justify;"><li style="direction: rtl;">حفظ اطلاعات موجود روی هاست.طبق درخواست کارفرما در ابتدای قرار داد</li><li style="direction: rtl;">به روز رسانی سی ام اس موجود (در مورد سایتهای داینامیک و وردپرس)</li><li style="direction: rtl;">جلوگیری از سواستفاده های احتمالی از قبیل حک کردن ایمیل یا وب سایت</li><li style="direction: rtl;">کنترل صحت عملکردسایت (به صورت هفتگی)</li><li style="direction: rtl;">رفع مشکلات از قبیل (Suspend) شدن</li><li style="direction: rtl;">یادآوری سررسید تمدید دامین و هاست.</li><li style="direction: rtl;">اصلاح کدهای قالب</li><li style="direction: rtl;">حذف لینک های شکسته (در طول مدت قرار داد)</li><li style="direction: rtl;">هفته ای دو مرتبه امکان آپلود محتوا</li><li style="direction: rtl;">انجام عملیات بک آپ گیری روزی یک مرتبه</li></ul>';
        } elseif ($type == 'نگهداری شبکه') {
            $seo = '<p style="direction: rtl; text-align: right;"><strong>خدمات و تعهدات پیمانکار : </strong></p> <p style="direction: rtl; text-align: right;">در صورت اعلام هرگونه گزارش نقص فنی به صورت مکتوب از سوی پرسنل کارفرما که قبلاً توسط ایشان معرفی گردیده است، نسبت به حل مشکل اقدام نماید.</p> <ul style="direction: rtl;text-align: right;"> <li style="direction: rtl;">چنانچه قطعات کامپیوتر به هر عنوان آسیب دیده باشد پیمانکار پس از تهیه لیست قطعات آسیب دیده و برآورد هزینه احتمالی، با اخذ تائید کارفرما، اقدام به تهیه قطعات آسیب دیده و تعویض آنها می&shy;نماید. متعاقبا هزینه قطعات تهیه شده به عهده کارفرما می&shy; باشد.</li> <li style="direction: rtl;">ارائه گزارش خدمات انجام گرفته توسط کارشناسان به کارفرما.</li> <li style="direction: rtl;">ارائه خدمات امنیتی شامل حفظ سلامت کامپیوترها در برابر ویروس ها و آسیب های نرم افزاری و جلوگیری از سرقت اطلاعات.</li> <li style="direction: rtl;">از اطلاعات موجود هر روز یک بار دیسک پشتیبان تهیه می شود (با توجه به امکانات موجود در شرکت کارفرما).</li> <li style="direction: rtl;">با توجه به محدودیت های ظرفیتی در گرفتن دیسک پشتیبان، حداکثرتا 15 روز می توان اطلاعات را بازیابی کرد. در غیر این صورت فایل مذکور یافت نخواهد شد (با توجه به امکانات موجود در شرکت کارفرما).</li> <li style="direction: rtl;">بازدیدهای مداوم به صورت remote و بررسی وضعیت کارکرد صحیح سرور.</li> <li style="direction: rtl;">ارائه خدمات مشاوره ای در زمینه فن آوری اطلاعات جهت ارتقاء سطح کیفی.</li> <li style="direction: rtl;">حداکثر مدت زمان انتظار کارفرما جهت اعزام کارشناس 4 ساعت کاری می باشد.</li> <li style="direction: rtl;">بازدید ادواری و اعزام کارشناسان ' . $advari1 . ' به محل کارفرما (در صورت لزوم و در مواقع اضطراری تا ' . $advari2 . ' به صورت موردی).</li> <li style="direction: rtl;">چنانچه مشکل خاصی بعد از بازدید ادواری پیش آید پیمانکار می بایست اقدامات لازم را طبق مطالب مندرج در پیوست شماره 1 جهت رفع مشکل موجود انجام دهد.</li> <li style="direction: rtl;">نگهداری سیستم عامل های شبکه.</li> <li style="direction: rtl;">نگهداری پروتکل شبکه و دایرکتوری اجزای شبکه.</li> <li style="direction: rtl;">ارائه مشاوره به کارفرما برای ارتقاء سیستم های سخت افزاری و مدیریت شبکه در صورت نیاز.</li> <li style="direction: rtl;">پشتیبانی تلفنی کاربران در ساعات اداری (Help Desk).</li> <li style="direction: rtl;">بازدید و بررسی فعال بودن دستگاه UPS به صورت ماهانه در هر بازدید ادواری صورت گرفته و گزارش به کارفرما اعلام می شود. در صورت خرابی دستگاه ها، مشاوره از طرف شرکت پیمانکار انجام شده و بنابر اشکال اعلام هزینه خواهد شد.</li> </ul> <p style="direction: rtl; text-align: right;"><strong>تبصره 3 :</strong> تمامی موارد و مشکلات ابلاغی از طرف نماینده کارفرما می بایست مشمول وظایف مشروح پیمانکار طبق این قرارداد باشد شرکت پیمانکار مسئولیتی در مورد مشکلات و موارد خارج از آن نخواهد داشت.</p> <p style="direction: rtl; text-align: right;"><strong>تبصره 4 :</strong> بررسی و رفع مشکلات غیر قابل پیش بینی و امکان سنجی حل آن در صورت وقوع، طی یک جلسه با حضور طرفین قرارداد مشخص می شود.</p>';
        } else {
            $seo = '';
        }
        if ($type != 'نگهداری شبکه') {
            $seo2 = '<p style="direction: rtl; text-align: justify;">تبصره 2 : چنانچه در مدت اجرای قرارداد موارد یا اتفاقاتی حادث شود که برای آن در این قرار داد پیش بینی خاصی صورت نگرفته باشد با توافق کتبی طرفین آن موارد به قرارداد اضافه خواهد شد.</p> <p style="direction: rtl; text-align: justify;">پشتیبانی و انجام امور خارج از موارد بالا،به هیچ عنوان برعهده ی پیمانکار نیست و بدیهی است که مسئولیتی در قبال امور سایر موضوعات متوجه پیمانکار نمیباشد.</p> <p style="direction: rtl; text-align: justify;">کارفرما با امضای این پیوست به طور کامل&nbsp; در جریان مسئولیت های محول شده به پیمانکار قرار گرفته و کلیه موارد را می پذیرد.</p>';
            $taahod = '<p style="direction: rtl; text-align: justify;">1-6 کارفرما متعهد می&zwnj;گردد یک نفر را به عنوان نماینده تام الاختیار به عنوان رابط فنی و عمومی با اشراف کامل بر موضوع قرارداد را جهت سهولت برقراری ارتباط و کاهش ترافیک و فوت زمان تحویل موضوع قراردادهمزمان با اعلام عقد قرارداد تعیین و به پیمانکار معرفی می&zwnj;نماید. این معرفی طی یک برگه مکتوب و با مهر و امضای مدیریت عامل مجموع کارفرما به عنوان سند رسمی شناخته و اجرایی می&zwnj;گردد. بدیهی است که پیمانکار در حین مدت&nbsp; قرارداد مکلف به پاسخگویی تنها به همین فرد به عنوان نماینده کارفرما می&zwnj;باشد و پاسخگوی سایر افراد مجموعه کارفرما نخواهد بود.</p>
<p style="direction: rtl; text-align: justify;">نام نماینده کارفرما: ' . $companyRepresentativeName . '</p>
<p style="direction: rtl; text-align: justify;">آدرس ایمیلی که شرکت پیمانکار مکاتبات را ارسال نماید: ' . $companyEmail . '</p>
<p style="direction: rtl; text-align: justify;">شماره ارتباطی از طریق نرم افزار تلگرام و یا نرم افزارهای مشابه : ' . $companyRepresentativePhone . '</p>
<p style="direction: rtl; text-align: justify;">2-6 کارفرما متعهد می&zwnj;گردد کلیه امکانات لازم ( محتوا، عکس، گواهی نامه&zwnj;ها، رمز عبور و دسترسی&zwnj;ها ) برای انجام امور محوله موضوع قرارداد را در اختیار پیمانکار قرار دهد و از هیچگونه همکاری با نماینده پیمانکار کوتاهی ننماید.</p>
<p style="direction: rtl; text-align: justify;">3-6 کارفرما مکلف است پس از دریافت هر بخش (فاز) قرارداد مشکلات احتمالی را بررسی و در صورت وجود هرگونه ایرادی، قبل از پایان فاز سوم، آن را به صورت مکتوب به پیمانکار اعلام می&zwnj;نماید. بدیهی است که رفع اشکال پس از پایان فاز سوم و تحویل نهایی مشمول پرداخت هزینه مازاد بر مبلغ قرارداد خواهد بود.</p>
<p style="direction: rtl; text-align: justify;">4-6 دريافت اطلاعات از تاریخ عقد قرارداد، تنها و تنها در بیست روز کاری مقدور می&zwnj;باشد. پس از این تاریخ فایلی دریافت نخواهد شد و كارفرما پس از جلسه آموزشي و دريافت پنل مي تواند فايل هاي جديد را شخصا اضافه نمايد.</p>
<p style="direction: rtl; text-align: justify;">5-6 شايان ذكر است كه پس از اين تاريخ دريافت فايل به هيچ عنوان پذيرفته نخواهد شد،ادامه بارگزاري در صورتي كه موضوع قرارداد شامل پشتيباني و سئو هم باشد، پس از اتمام فاز سوم (انتقال به روت اصلي) ، هفته اي دوبار انجام خواهد شد.</p>';
        } else {
            $seo2 = '';
            if ($official == 'yes') {
                $taahod = '<ul> <li style="direction: rtl;text-align: right;">عدم ارائه رمز عبور سیستم ها و دریافت خدمات از افراد غیر متخصص و غیر مرتبط با پیمانکار .</li> <li style="direction: rtl;text-align: right;">ارسال درخواست رفع موارد و مشکلات به وجود آمده به صورت کتبی برای پیمانکار.</li> <li style="direction: rtl;text-align: right;">کارفرما متعهد است لیستی از تجهیزات و کامپیوترهای موجود در مجموعه را تهیه کند و در اختیار پیمانکار قرار دهد. بدیهی است ارائه خدمات به تجهیزات خارج از مجموعه ، از قبیل کامپیوترهای لیست نشده ، پرینترها ، دوربین ها و غیره از عهده پیمانکار خارج است.</li> <li style="direction: rtl;text-align: right;">پیمانکار موظف است به تعداد موارد درج شده در متن قرارداد ( بند 9 تعهدات پیمانکار) بازدید ادواری از سیستم کارفرما داشته باشد. مراجعات بعدی که بنا بر درخواست کارفرما باشد ، به صورت جداگانه محاسبه و ابلاغ می شود. مبلغ بازدید خارج از برنامه همکاران معادل 25 درصد از مبلغ قرارداد برای یک ماه می باشد.</li> </ul> <p style="direction: rtl;text-align: right;"><strong>تبصره 5 :</strong>&nbsp;در صورت تماس با تلفن های شخصی کارشناسان و یا افراد غیر مرتبط با پیمانکار، این شرکت هیچ گونه مسئولیتی را متقبل نمی شود.</p> <p style="direction: rtl;text-align: right;">تسویه ماهانه فاکتورهای پیمانکار ناشی از فروش وسایل و یا تجهیزاتی که به منظور رفع اشکال یا ارتقاء سطح کیفی و با هماهنگی قبلی تهیه شده است.</p> <p style="direction: rtl;text-align: right;"><strong>تبصره&nbsp;</strong><strong>6&nbsp;</strong><strong>:</strong> مبلغ ارزش افزوده در هر فاکتور خدمات ماهانه توسط کارفرما لحاظ می گردد که کارفرما موظف به پرداخت آن می باشد. پیمانکار در پایان هر ماه فاکتور گزارش عملکرد را طی یک برگ فاکتور رسمی به کارفرما ارائه خواهد کرد و کارفرما موظف به پرداخت و لحاظ فاکتور رسمی ارائه شد به عنوان وجه پرداختی به پیمانکار می باشد.</p>';
            } else {
                $taahod = '<ul> <li style="direction: rtl; text-align: right;">عدم ارائه رمز عبور سیستم ها و دریافت خدمات از افراد غیر متخصص و غیر مرتبط با پیمانکار .</li> <li style="direction: rtl; text-align: right;">ارسال درخواست رفع موارد و مشکلات به وجود آمده به صورت کتبی برای پیمانکار.</li> <li style="direction: rtl; text-align: right;">کارفرما متعهد است لیستی از تجهیزات و کامپیوترهای موجود در مجموعه را تهیه کند و در اختیار پیمانکار قرار دهد. بدیهی است ارائه خدمات به تجهیزات خارج از مجموعه ، از قبیل کامپیوترهای لیست نشده ، پرینترها ، دوربین ها و غیره از عهده پیمانکار خارج است.</li> <li style="direction: rtl; text-align: right;">پیمانکار موظف است به تعداد موارد درج شده در متن قرارداد ( بند 9 تعهدات پیمانکار) بازدید ادواری از سیستم کارفرما داشته باشد. مراجعات بعدی که بنا بر درخواست کارفرما باشد ، به صورت جداگانه محاسبه و ابلاغ می شود. مبلغ بازدید خارج از برنامه همکاران معادل 25 درصد از مبلغ قرارداد برای یک ماه می باشد.</li> </ul> <p style="direction: rtl; text-align: right;"><strong>تبصره 5 :</strong>&nbsp;در صورت تماس با تلفن های شخصی کارشناسان و یا افراد غیر مرتبط با پیمانکار، این شرکت هیچ گونه مسئولیتی را متقبل نمی شود.</p> <p style="direction: rtl; text-align: right;">تسویه ماهانه فاکتورهای پیمانکار ناشی از فروش وسایل و یا تجهیزاتی که به منظور رفع اشکال یا ارتقاء سطح کیفی و با هماهنگی قبلی تهیه شده است.</p>';
            }

        }
        if ($type == 'نگهداری شبکه') {
            $network = '<p style="direction: rtl;"><strong>ماده 2 : موضوع قرارداد</strong></p>
<p style="direction: rtl;">موضوع قرارداد عبارت است از ارائه خدمات نگهداری شبکه کامپیوتری و فن آوری موجود و دوربین مدار بسته موجود درشرکت کار فرما، با شرایط مندرج در مفاد این قرارداد.</p>
<p style="direction: rtl;"><strong>تبصره 1 :</strong> نگهداری و پشتیبانی از نرم افزارهای فارسی ساخته شده در کشور شامل قرارداد نمی باشد.</p>
<p style="direction: rtl;"><strong>تبصره 2 :</strong> موضوع آموزش شامل قرارداد نمی باشد.</p>';
        } else {
            $network = '<p style="direction: rtl; text-align: justify;"><b>ماده 2 : موضوع قرارداد:</b></p>
<p style="direction: rtl; text-align: justify;">براساس این قرارداد مقرر شده است که موضوعات (' . $subject . ') انتخاب شده به عنوان موضوع قرارداد تعیین و انجام شود.</p>
<p style="direction: rtl;">طراحی سایت ' . iconCheck($siteDesignType) . ' پشتیبانی ' . iconCheck($support) . ' تهیه هاست دامین ' . iconCheck($domain) . ' عکاسی ' . iconCheck($picture) . ' مشاور برندینگ ' . iconCheck($brand) . ' نماد اعتماد الکترونیکی ' . iconCheck($enamad) . ' اتصال به درگاه اینترنتی ' . iconCheck($payment) . ' فروشگاه اینترنتی ' . iconCheck($shop) . '</p>';
        }
        $draft = '<p style="direction: rtl; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>تاریخ :</b> ' . my_jdate(date("Y-m-d"), "Y/m/d") . '</p>
<p style="direction: rtl; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>شماره :</b> ' . $number . '</p>
<p style="direction: rtl; text-align: center;">بسمه تعالی</p>
<p style="direction: rtl; text-align: center;">قرارداد</p>
<p style="direction: rtl;">&nbsp;</p>
<p style="direction: rtl; text-align: justify;"><b>ماده 1 : طرفین قرارداد</b></p>
<p style="direction: rtl; text-align: justify;">درتاریخ فوق قرارداد ذیل بین ' . $companyName . ' به نمایندگی ' . $companyRepresentativeName . ' به نشانی : ' . $companyAddress . '&nbsp;به شماره تماس : ' . $companyPhone . $companyCode . ' که منبعد در این قرارداد کارفرما نامیده میشود و طرف دیگر ' . $companyNameMe . ' به نشانی : خیابان آیت اله کاشانی، خیابان بهمنی نژاد، روبروی باشگاه پرسپولیس، ساختمان مهدی، پلاک 15، واحد 1 به شماره تماس : 49295 ' . $companyCodeMe . 'که منبعد در این قرارداد پیمانکار نامیده میشود منعقد گردید.</p>
' . html_entity_decode($network) . '
<p style="direction: rtl; text-align: justify;"><b>ماده 3 : مدت زمان</b></p>
<div style="direction: rtl;text-align:right;">' . html_entity_decode(nl2br($time)) . html_entity_decode(nl2br($seo)) . '</div>
<p style="direction: rtl; text-align: justify;">&nbsp;</p>
' . html_entity_decode($seo2) . '
<p style="direction: rtl; text-align: justify;"><b>ماده 4 : مبلغ قرارداد</b></p>
<p style="direction: rtl; text-align: justify;">1-4 مبلغ پروژه ' . $priceRial . ' ریال معادل ' . $priceToman . ' تومان (به حروف) ' . $priceString . ' ریال است &zwnj;.</p>
<p style="direction: rtl; text-align: justify;">کلیه پرداخت ها به صورت نقدی و یا از طریق حصول نقدی چک (قبل از شروع فاز بعدی) قابل قبول می‌باشد. پرداخت تنها به حساب ' . $companyNameMe . ' قابل قبول است.</p>
<p style="direction: rtl; text-align: justify;">بدیهی است که در غیر اینصورت، کلیه تبعات متوجه کارفرما بوده و مبلغ پرداختی به منزله عدم پرداخت توسط کارفرما قلمداد می&zwnj;گردد.</p>
<p style="direction: rtl; text-align: justify;"><b>ماده 5 : نحوه پرداخت مبلغ قرارداد</b></p>
<div style="direction: rtl;text-align:right;">' . html_entity_decode(nl2br($pay)) . $companyTabMe . '</div>
<p style="direction: rtl; text-align: justify;">تبصره 3 : درصورت تاخیر کارفرما در پرداخت مبالغ در حین قرارداد، پروژه در هر کجای قرارداد متوقف و به صورت تعلیق در خواهد آمد. بدیهی است که کلیه عواقب ناشی از این تاخیر تنها متوجه کارفرما بوده . هزینه&zwnj;ها و زمان احتمالی متحمله، پس از بررسی متعاقباً توسط پیمانکار اعلام خواهد شد.</p>
<p style="direction: rtl; text-align: justify;">تبصره 5 : پس از تست و تحویل وتایید کارفرما و تسویه حساب کامل سایت از آدرس فرعی به آدرس اصلی منتقل می شود</p>
<p style="direction: rtl; text-align: justify;">تبصره 6 : هرگونه درخواست کدنویسی ، ماژول نویسی و ..... خارج از موضوعات تعیین شده در جلسه اول قرارداد، از لحاظ زمان و مبلغ اجرا به صورت مجزا و بررسی محاسبه شده و در صورت تائید کارفرما به موضوع قرارداد اضافه می&zwnj;گردد.</p>
<p style="direction: rtl; text-align: justify;"><b>ماده 6 : تعهدات کارفرما</b></p>
' . html_entity_decode($taahod) . '
<p style="direction: rtl; text-align: justify;"><b>ماده 7 : تعهدات پیمانکار</b></p>
<p style="direction: rtl; text-align: justify;">1-7 پیمانکار متعهد می گردد بدون اطلاع و موافقت کتبی از کارفرما اجرای همه یا بخشی از موضوع قرارداد را به غیر واگذار ننماید.</p>
<p style="direction: rtl; text-align: justify;">2-7 پیمانکار متعهد می گردد در زمان پشتیبانی موضوع قرارداد، هرگونه اشکال احتمالی در موضوع تحویلی قرارداد (software bug) را که ناشی از خطای سیستمی باشد و بهره برداری از موضوع قرارداد را مختل نماید، حداکثر به مدت پنج ساعت از زمان دریافت خبر از نماینده کارفرما مبنی بر بروز اشکال (مشروط به اطلاع رسانی در زمان های کاری شرکت پیمانکار از نه صبح تا پنج عصر در روزهای غیر تعطیل ) برطرف نماید.</p>
<p style="direction: rtl; text-align: justify;">3-7 پیمانکار متعهد می گردد پشتیبانی سیستمی موضوع قرارداد را که شامل به روزرسانی امنیتی موضوع قرارداد و رفع به هم ریختگی های ظاهری سی اس اس مبنی بر بروز شدن مرورگرها و کلیه به روز رسانی های سیستمی (core update) می باشد، را به مدت یک سال به صورت رایگان اجرا نماید.</p>
<p style="direction: rtl; text-align: justify;">4-7 پیمانکار متعهد می گردد پیشنهادات اصلاحی کارفرما را حین انجام کار طبق پیوستهای موجود و صورتجلسات مشترک اجرا نماید.</p>
<p style="direction: rtl; text-align: justify;">پیمانکار متعهد می گرددد که موضوع قرارداد را دقیقاً طبق پیوستهای ضمیمه در صورتجلسات مشترک به کارفرما تحویل نماید.</p>
<p style="direction: rtl; text-align: justify;">5-7 پیمانکار متعهد می گردد ارزشهای معنوی و بصری موضوع قرارداد را برای کارفرما محفوظ نگاه داشته و در حفظ طرح و فایل های دریافتی آماده از قبیل لوگو و کلیه اطلاعات اولیه محتوایی و فیلم و عکس و... از سوی کارفرما کوشا باشد و در صورت اثبات خلاف آن، پیمانکار متعهد به جبران خسارات ناشی از آن می باشد.</p>
<p style="direction: rtl; text-align: justify;">6-7 پیمانکار موظف است تمامی اصلاحات درخواستی کارفرما را (در صورت وجود در مستندات پیوستی و صورتجلسات مشترک) در کوتاه ترین زمان ممکن تحویل نماید.</p>
<p style="direction: rtl; text-align: justify;">7-7 پیمانکار متعهد می گردد یک تا سه نفر را به عنوان نیروهای اجرایی و ارتباطی به کارفرما معرفی نماید، تا بستر ارتباطی فی مابین پیمانکار و کارفرما تامین گردد.</p>
<p style="direction: rtl; text-align: justify;"><b>ماده 8 : تعهدات طرفین</b></p>
<p style="direction: rtl; text-align: justify;">1-8 طرفین متعهد می گردند که مفاد این قرارداد بعلاوه کلیه اطلاعات، مستندات و اسناد طرف مقابل و اشخاص حقیقی و حقوقی طرف قرارداد آن را (که در جهت اجرای موضوع این قرارداد در اختیار یکدیگر قرار می دهند) محرمانه تلقی نموده و از افشاء آنها به هر دلیل و با هر انگیزه چه در دوره زمانی این قرارداد و چه پس از آن خودداری نمایند. مگر آنکه برای انجام زمینه های همکاری این&nbsp; قرارداد و یا بنا به حکم قانون یا مراجع قانونی، افشاء اطلاعات ضروری گردد.در صورت نیاز به انجام همکاری پیرامون موضوع این قرارداد، اطلاع رسانی بین طرفین به صورت مکتوب و توافق کتبی طرف مقابل ضروری می باشد.</p>
<p style="direction: rtl; text-align: justify;">2-8 طرفین می توانند هر زمان نسبت به تغییر نماینده خود اقدام نموده و این موضوع را به صورت کتبی به اطلاع طرف مقابل برسانند. ولی این تغییر به هیچ وجه نافی تصمیمات نماینده قبلی طی دوران مسئولیت خود تلقی نمی گردد.</p>
<p style="direction: rtl; text-align: justify;">3-8 طرفین قرارداد می توانند هر زمان نسبت به تغییر مفاد این قرارداد اقدام نمایند. در صورتیکه یکی از طرفین ضرورت تغییراتی را در قرارداد احساس نماید موضوع را کتبا به طرف مقابل اعلام خواهد کرد. طرف مقابل تغییر درخواست شده را بررسی و نتیجه را به صورت کتبی ظرف مدت هفت روز اعلام خواهد کرد. هرگونه تغییر در قرارداد طی یک الحاقیه جداگانه که به تایید طرفین می رسد، صورت خواهد گرفت.</p>
<p style="direction: rtl; text-align: justify;"><b>ماده 9 : قوه قهریه</b></p>
<p style="direction: rtl; text-align: justify;">1-9 &nbsp;این قرارداد تابع قانون قهریه (فورس ماژور) می باشد. بنابراین در صورت بروز موارد قوه قهریه و سایر موارد خارج از اختیار طرفین از قبیل (و نه محدود به) بلایای طبیعی، فوت و نقص عضو، جنگ، اعتصاب ، اغتشاش عمومی و موارد مشابه، تا زمانی که وضعیت فوق العاده و تبعات آن برطرف نشده باشد، به گونه ای که انجام تعهدات و خدمات این قرارداد را با اشکال مواجه کند مسئولیتی متوجه طرفین نخواهد بود.</p>
<p style="direction: rtl; text-align: justify;">2-9 مواردی همچون بروز مشکلات اداری و قانونی، افزایش (عادی و قابل پیش بینی) قیمت کالا یا مواد و ابزار تولید، افزایش (عادی و قالب پیش بینی) سطح دستمزد ها و تغییر (عادی و قابل پیش بینی) نرخ برابری ارزها جزء موارد قوه قهریه محسوب نمی گردد.</p>
<p style="direction: rtl; text-align: justify;"><b>ماده 10 : قوانین حاکم برحل اختلاف</b></p>
<p style="direction: rtl; text-align: justify;">1-10 در کلیه موارد پیش بینی نشده در قرارداد، قوانین جاری جمهوری اسلامی ایران حاکم بوده، در صورت بروز هر گونه اختلاف احتمالی ابتدا موضوع با مذاکره طرفین و در صورت عدم حصول نتیجه به حکم مرضی الطرفین که بعدا مشخص خواهد شد ارجاع می گردد، در غیر این صورت از طریق مراجع ذیصلاح قانونی قابل پیگیری می باشد.</p>
<p style="direction: rtl; text-align: justify;"><b>ماده 11 : حقوق مادی و معنوی</b></p>
<p style="direction: rtl; text-align: justify;">1-11 پیمانکار امتیاز سایت اینترنتی و انحصار حق استفاده از تمامی سفارشات اجرایی به خواست کارفرما و کلیه عایدات و منافع آن را به کارفرما واگذار می نماید لذا پیمانکار حق استفاده از اطلاعات آنها بدون مجوز کارفرما را نخواهد داشت.</p>
<p style="direction: rtl; text-align: justify;">حق فسخ: در شرایط عادی فسخ و یا تغییر قرارداد تنها با توافق طرفین امکان پذیر می باشد.</p>
<p style="direction: rtl; text-align: justify;"><b>ماده 12 : فسخ قرارداد</b></p>
<p style="direction: rtl; text-align: justify;">این قرارداد با توافق طرفین و با شرایط ذیل قابل فسخ میباشد.</p>
<p style="direction: rtl; text-align: justify;">1-12 در صورت فسخ از سوی کارفرما، بدون دلیل موجه، پیمانکار مستحق دریافت 10% از کل هزینه ی پروژه بعلاوه هزینه ی حق العمل متناسب با درصد پیشرفت پروژه می باشد.</p>
<p style="direction: rtl; text-align: justify;">2-12 در صورت فسخ از سوی مجری، 100% مبلغ عیناً به کارفرما عودت داده میشود.</p>
<p style="direction: rtl; text-align: justify;"><b>ماده 13 : نسخ قرارداد</b></p>
<p style="direction: rtl; text-align: justify;">&nbsp;این قرارداد در سیزده ماده و پنج تبصره و دو نسخه و در پنج صفحه ' . $peyvast . ' تنظیم شده است و هر یک از نسخ حکم واحد را دارد.</p>
<p style="direction: rtl; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
<p style="direction: rtl; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;امضاء پیمانکار&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;امضاء کارفرما&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;تاریخ</p>
<p style="direction: rtl; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;' . $companyNameMe . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $companyName . '</p>';
        return $draft;
    }
}


if (!function_exists('my_jdate')) {
    function my_jdate($date, $type)
    {
        $timestamp = (strtotime($date));
        require_once('jdf.php');
        $jalali_date = jdate($type, $timestamp);
        return $jalali_date;
    }
}

if (!function_exists('j2g')) {
    function j2g($date, $mode='-')
    {
        $date=str_replace('/','-',$date);
        $date=explode('-',$date);
        if(count($date)>2)
        {
            require_once('jdf.php');
            $gregorian_date = jalali_to_gregorian($date[0],$date[1],$date[2],$mode);
            return $gregorian_date;
        }
    }
}
if (!function_exists('to_gregorian')) {
    function to_gregorian($y,$m,$d, $mode='')
    {
        require_once('jdf.php');
        $gregorian_date = jalali_to_gregorian($y,$m,$d,$mode);
        return $gregorian_date;
    }
}

if (!function_exists('dateDiffDomain')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param  int $code
     * @param  string $message
     * @param  array $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function dateDiffDomain($startDate, $endDate)
    {
        $startArry = date_parse($startDate);
        $endArry = date_parse($endDate);
        $start_date = gregoriantojd($startArry["month"], $startArry["day"], $startArry["year"]);
        $end_date = gregoriantojd($endArry["month"], $endArry["day"], $endArry["year"]);
        $m=round(($end_date - $start_date), 0);

     return round(($end_date - $start_date), 0);
    }
}


if (!function_exists('auto_send_30')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param  int $code
     * @param  string $message
     * @param  array $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function auto_send_30($name)
    {
        if (Cache::get($name)) {
            return 0;
        } else {
            $expiresAt = Carbon::now()->addDay(25);
            Cache::put($name, true, $expiresAt);
            return 1;
        }

    }
}

if (!function_exists('auto_send_5')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param  int $code
     * @param  string $message
     * @param  array $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function auto_send_5($name)
    {
        if (Cache::get($name)) {
            return 0;
        } else {
            $expiresAt = Carbon::now()->addDay(4);
            Cache::put($name, true, $expiresAt);
            return 1;
        }

    }
}

if (!function_exists('ticket_send')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param  int $code
     * @param  string $message
     * @param  array $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function ticket_send()
    {
        if (Cache::get('ticket_send')) {
            return 0;
        } else {
            $expiresAt = Carbon::now()->addMinutes(3);
            Cache::put('ticket_send', true, $expiresAt);
            return 1;
        }

    }
}

if (!function_exists('file_store')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param  int $code
     * @param  string $message
     * @param  array $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function file_store($u_file, $u_path, $u_prefix)
    {
        $file = $u_file;
        $originalName = $u_file->getClientOriginalName();
        $destinationPath = $u_path;
        $extension = $file->getClientOriginalExtension();
        $fileName = $u_prefix . md5(time() . '-' . $originalName) . '.' . $extension;
        $file->move($destinationPath, $fileName);
        $f_path = $destinationPath . "" . $fileName;
        return $f_path;
    }
}


if (!function_exists('telegram_list')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param  int $code
     * @param  string $message
     * @param  array $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function telegram_list()
    {
        header('Content-Type: text/html; charset=utf-8');
        $token = "bot302257015:AAHkzRIZtbjHwRIeo6RspxJ3lcCkyOgiR1g";
        $url = "https://api.telegram.org/bot302257015:AAHkzRIZtbjHwRIeo6RspxJ3lcCkyOgiR1g/getUpdates";
        $update = file_get_contents($url);
        $arrayUpdate = json_decode($update, true);
        foreach ($arrayUpdate['result'] as $key) {
            if (isset($key['message']['from']['username'])) {
                ChatId::firstOrCreate([
                    'chat_id' => $key['message']['from']['id'],
                    'username' => $key['message']['from']['username']
                ]);
            }
        }


    }
}


if (!function_exists('telegram_notify')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param  int $code
     * @param  string $message
     * @param  array $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function telegram_notify($user_id, $text)
    {
        telegram_list();
        $telegram = new Api('302257015:AAGc5nRTcAgjUd2CCZOnEQPqbJKQLodw8Dw');
        $user = User::where('id', $user_id)->first();
        $username = $user->company__telegram;
        $chat_id = ChatId::where('username', $username)->first();
        if ($chat_id != "") {
            $telegram->sendMessage([
                'chat_id' => $chat_id->chat_id,
                'text' => $text,
            ]);
        }
    }
}


if (!function_exists('date__remaining')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param  int $code
     * @param  string $message
     * @param  array $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function date__remaining($date__start, $day__sum)
    {
        $date__day = time() + (86400 * $day__sum);
        $date__later = date('Y-m-d', $date__day);
        $date__start = new DateTime($date__start);
        $date__end = new DateTime($date__later);
        $diff = $date__start->diff($date__end);
        echo $diff->format('%d') . ' روز';
    }
}

if (!function_exists('abort')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param  int $code
     * @param  string $message
     * @param  array $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function abort($code, $message = '', array $headers = [])
    {
        app()->abort($code, $message, $headers);
    }
}

if (!function_exists('abort_if')) {
    /**
     * Throw an HttpException with the given data if the given condition is true.
     *
     * @param  bool $boolean
     * @param  int $code
     * @param  string $message
     * @param  array $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function abort_if($boolean, $code, $message = '', array $headers = [])
    {
        if ($boolean) {
            abort($code, $message, $headers);
        }
    }
}

if (!function_exists('abort_unless')) {
    /**
     * Throw an HttpException with the given data unless the given condition is true.
     *
     * @param  bool $boolean
     * @param  int $code
     * @param  string $message
     * @param  array $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function abort_unless($boolean, $code, $message = '', array $headers = [])
    {
        if (!$boolean) {
            abort($code, $message, $headers);
        }
    }
}

if (!function_exists('action')) {
    /**
     * Generate the URL to a controller action.
     *
     * @param  string $name
     * @param  array $parameters
     * @param  bool $absolute
     * @return string
     */
    function action($name, $parameters = [], $absolute = true)
    {
        return app('url')->action($name, $parameters, $absolute);
    }
}

if (!function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param  string $abstract
     * @param  array $parameters
     * @return mixed|\Illuminate\Foundation\Application
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return empty($parameters)
            ? Container::getInstance()->make($abstract)
            : Container::getInstance()->makeWith($abstract, $parameters);
    }
}

if (!function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string $path
     * @return string
     */
    function app_path($path = '')
    {
        return app('path') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string $path
     * @param  bool $secure
     * @return string
     */
    function asset($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
}


if (!function_exists('directory')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string $path
     * @param  bool $secure
     * @return string
     */
    function directory($path, $secure = null)
    {
        return url($path);
//        return 'https://adib-it.com/' . $path;
    }
}

if (!function_exists('auth')) {
    /**
     * Get the available auth instance.
     *
     * @param  string|null $guard
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    function auth($guard = null)
    {
        if (is_null($guard)) {
            return app(AuthFactory::class);
        } else {
            return app(AuthFactory::class)->guard($guard);
        }
    }
}

if (!function_exists('back')) {
    /**
     * Create a new redirect response to the previous location.
     *
     * @param  int $status
     * @param  array $headers
     * @param  mixed $fallback
     * @return \Illuminate\Http\RedirectResponse
     */
    function back($status = 302, $headers = [], $fallback = false)
    {
        return app('redirect')->back($status, $headers, $fallback);
    }
}

if (!function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param  string $path
     * @return string
     */
    function base_path($path = '')
    {
        return app()->basePath() . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('bcrypt')) {
    /**
     * Hash the given value.
     *
     * @param  string $value
     * @param  array $options
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return app('hash')->make($value, $options);
    }
}

if (!function_exists('broadcast')) {
    /**
     * Begin broadcasting an event.
     *
     * @param  mixed|null $event
     * @return \Illuminate\Broadcasting\PendingBroadcast|void
     */
    function broadcast($event = null)
    {
        return app(BroadcastFactory::class)->event($event);
    }
}

if (!function_exists('cache')) {
    /**
     * Get / set the specified cache value.
     *
     * If an array is passed, we'll assume you want to put to the cache.
     *
     * @param  dynamic  key|key,default|data,expiration|null
     * @return mixed
     *
     * @throws \Exception
     */
    function cache()
    {
        $arguments = func_get_args();

        if (empty($arguments)) {
            return app('cache');
        }

        if (is_string($arguments[0])) {
            return app('cache')->get($arguments[0], isset($arguments[1]) ? $arguments[1] : null);
        }

        if (is_array($arguments[0])) {
            if (!isset($arguments[1])) {
                throw new Exception(
                    'You must set an expiration time when putting to the cache.'
                );
            }

            return app('cache')->put(key($arguments[0]), reset($arguments[0]), $arguments[1]);
        }
    }
}

if (!function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string $key
     * @param  mixed $default
     * @return mixed
     */
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
    }
}

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->make('path.config') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('cookie')) {
    /**
     * Create a new cookie instance.
     *
     * @param  string $name
     * @param  string $value
     * @param  int $minutes
     * @param  string $path
     * @param  string $domain
     * @param  bool $secure
     * @param  bool $httpOnly
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    function cookie($name = null, $value = null, $minutes = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        $cookie = app(CookieFactory::class);

        if (is_null($name)) {
            return $cookie;
        }

        return $cookie->make($name, $value, $minutes, $path, $domain, $secure, $httpOnly);
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate a CSRF token form field.
     *
     * @return \Illuminate\Support\HtmlString
     */
    function csrf_field()
    {
        return new HtmlString('<input type="hidden" name="_token" value="' . csrf_token() . '">');
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get the CSRF token value.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    function csrf_token()
    {
        $session = app('session');

        if (isset($session)) {
            return $session->token();
        }

        throw new RuntimeException('Application session store not set.');
    }
}

if (!function_exists('database_path')) {
    /**
     * Get the database path.
     *
     * @param  string $path
     * @return string
     */
    function database_path($path = '')
    {
        return app()->databasePath($path);
    }
}

if (!function_exists('decrypt')) {
    /**
     * Decrypt the given value.
     *
     * @param  string $value
     * @return string
     */
    function decrypt($value)
    {
        return app('encrypter')->decrypt($value);
    }
}

if (!function_exists('dispatch')) {
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param  mixed $job
     * @return mixed
     */
    function dispatch($job)
    {
        return app(Dispatcher::class)->dispatch($job);
    }
}

if (!function_exists('elixir')) {
    /**
     * Get the path to a versioned Elixir file.
     *
     * @param  string $file
     * @param  string $buildDirectory
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    function elixir($file, $buildDirectory = 'build')
    {
        static $manifest = [];
        static $manifestPath;

        if (empty($manifest) || $manifestPath !== $buildDirectory) {
            $path = public_path($buildDirectory . '/rev-manifest.json');

            if (file_exists($path)) {
                $manifest = json_decode(file_get_contents($path), true);
                $manifestPath = $buildDirectory;
            }
        }

        $file = ltrim($file, '/');

        if (isset($manifest[$file])) {
            return '/' . trim($buildDirectory . '/' . $manifest[$file], '/');
        }

        $unversioned = public_path($file);

        if (file_exists($unversioned)) {
            return '/' . trim($file, '/');
        }

        throw new InvalidArgumentException("File {$file} not defined in asset manifest.");
    }
}

if (!function_exists('encrypt')) {
    /**
     * Encrypt the given value.
     *
     * @param  string $value
     * @return string
     */
    function encrypt($value)
    {
        return app('encrypter')->encrypt($value);
    }
}

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }

        if (strlen($value) > 1 && Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}

if (!function_exists('event')) {
    /**
     * Dispatch an event and call the listeners.
     *
     * @param  string|object $event
     * @param  mixed $payload
     * @param  bool $halt
     * @return array|null
     */
    function event(...$args)
    {
        return app('events')->dispatch(...$args);
    }
}

if (!function_exists('factory')) {
    /**
     * Create a model factory builder for a given class, name, and amount.
     *
     * @param  dynamic  class|class,name|class,amount|class,name,amount
     * @return \Illuminate\Database\Eloquent\FactoryBuilder
     */
    function factory()
    {
        $factory = app(EloquentFactory::class);

        $arguments = func_get_args();

        if (isset($arguments[1]) && is_string($arguments[1])) {
            return $factory->of($arguments[0], $arguments[1])->times(isset($arguments[2]) ? $arguments[2] : null);
        } elseif (isset($arguments[1])) {
            return $factory->of($arguments[0])->times($arguments[1]);
        } else {
            return $factory->of($arguments[0]);
        }
    }
}

if (!function_exists('info')) {
    /**
     * Write some information to the log.
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    function info($message, $context = [])
    {
        return app('log')->info($message, $context);
    }
}

if (!function_exists('logger')) {
    /**
     * Log a debug message to the logs.
     *
     * @param  string $message
     * @param  array $context
     * @return \Illuminate\Contracts\Logging\Log|null
     */
    function logger($message = null, array $context = [])
    {
        if (is_null($message)) {
            return app('log');
        }

        return app('log')->debug($message, $context);
    }
}

if (!function_exists('method_field')) {
    /**
     * Generate a form field to spoof the HTTP verb used by forms.
     *
     * @param  string $method
     * @return \Illuminate\Support\HtmlString
     */
    function method_field($method)
    {
        return new HtmlString('<input type="hidden" name="_method" value="' . $method . '">');
    }
}

if (!function_exists('mix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param  string $path
     * @param  string $manifestDirectory
     * @return \Illuminate\Support\HtmlString
     *
     * @throws \Exception
     */
    function mix($path, $manifestDirectory = '')
    {
        static $manifest;

        if (!starts_with($path, '/')) {
            $path = "/{$path}";
        }

        if ($manifestDirectory && !starts_with($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        if (file_exists(public_path($manifestDirectory . '/hot'))) {
            return new HtmlString("http://localhost:8080{$path}");
        }

        if (!$manifest) {
            if (!file_exists($manifestPath = public_path($manifestDirectory . '/mix-manifest.json'))) {
                throw new Exception('The Mix manifest does not exist.');
            }

            $manifest = json_decode(file_get_contents($manifestPath), true);
        }

        if (!array_key_exists($path, $manifest)) {
            throw new Exception(
                "Unable to locate Mix file: {$path}. Please check your " .
                'webpack.mix.js output paths and try again.'
            );
        }

        return new HtmlString($manifestDirectory . $manifest[$path]);
    }
}

if (!function_exists('old')) {
    /**
     * Retrieve an old input item.
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    function old($key = null, $default = null)
    {
        return app('request')->old($key, $default);
    }
}

if (!function_exists('policy')) {
    /**
     * Get a policy instance for a given class.
     *
     * @param  object|string $class
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    function policy($class)
    {
        return app(Gate::class)->getPolicyFor($class);
    }
}

if (!function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param  string $path
     * @return string
     */
    function public_path($path = '')
    {
        return app()->make('path.public') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('redirect')) {
    /**
     * Get an instance of the redirector.
     *
     * @param  string|null $to
     * @param  int $status
     * @param  array $headers
     * @param  bool $secure
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {
        if (is_null($to)) {
            return app('redirect');
        }

        return app('redirect')->to($to, $status, $headers, $secure);
    }
}

if (!function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string $key
     * @param  mixed $default
     * @return \Illuminate\Http\Request|string|array
     */
    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('request');
        }

        if (is_array($key)) {
            return app('request')->only($key);
        }

        return data_get(app('request')->all(), $key, $default);
    }
}

if (!function_exists('resolve')) {
    /**
     * Resolve a service from the container.
     *
     * @param  string $name
     * @return mixed
     */
    function resolve($name)
    {
        return app($name);
    }
}

if (!function_exists('resource_path')) {
    /**
     * Get the path to the resources folder.
     *
     * @param  string $path
     * @return string
     */
    function resource_path($path = '')
    {
        return app()->resourcePath($path);
    }
}

if (!function_exists('response')) {
    /**
     * Return a new response from the application.
     *
     * @param  string $content
     * @param  int $status
     * @param  array $headers
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    function response($content = '', $status = 200, array $headers = [])
    {
        $factory = app(ResponseFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($content, $status, $headers);
    }
}

if (!function_exists('route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param  string $name
     * @param  array $parameters
     * @param  bool $absolute
     * @return string
     */
    function route($name, $parameters = [], $absolute = true)
    {
        return app('url')->route($name, $parameters, $absolute);
    }
}

if (!function_exists('secure_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string $path
     * @return string
     */
    function secure_asset($path)
    {
        return asset($path, true);
    }
}

if (!function_exists('secure_url')) {
    /**
     * Generate a HTTPS url for the application.
     *
     * @param  string $path
     * @param  mixed $parameters
     * @return string
     */
    function secure_url($path, $parameters = [])
    {
        return url($path, $parameters, true);
    }
}




if (!function_exists('session')) {
    /**
     * Get / set the specified session value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string $key
     * @param  mixed $default
     * @return mixed
     */
    function session($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('session');
        }

        if (is_array($key)) {
            return app('session')->put($key);
        }

        return app('session')->get($key, $default);
    }
}

if (!function_exists('storage_path')) {
    /**
     * Get the path to the storage folder.
     *
     * @param  string $path
     * @return string
     */
    function storage_path($path = '')
    {
        return app('path.storage') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('trans')) {
    /**
     * Translate the given message.
     *
     * @param  string $id
     * @param  array $replace
     * @param  string $locale
     * @return \Illuminate\Contracts\Translation\Translator|string
     */
    function trans($id = null, $replace = [], $locale = null)
    {
        if (is_null($id)) {
            return app('translator');
        }

        return app('translator')->trans($id, $replace, $locale);
    }
}

if (!function_exists('trans_choice')) {
    /**
     * Translates the given message based on a count.
     *
     * @param  string $id
     * @param  int|array|\Countable $number
     * @param  array $replace
     * @param  string $locale
     * @return string
     */
    function trans_choice($id, $number, array $replace = [], $locale = null)
    {
        return app('translator')->transChoice($id, $number, $replace, $locale);
    }
}

if (!function_exists('__')) {
    /**
     * Translate the given message.
     *
     * @param  string $key
     * @param  array $replace
     * @param  string $locale
     * @return \Illuminate\Contracts\Translation\Translator|string
     */
    function __($key = null, $replace = [], $locale = null)
    {
        return app('translator')->getFromJson($key, $replace, $locale);
    }
}

if (!function_exists('url')) {
    /**
     * Generate a url for the application.
     *
     * @param  string $path
     * @param  mixed $parameters
     * @param  bool $secure
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function url($path = null, $parameters = [], $secure = null)
    {
        if (is_null($path)) {
            return str_replace('index.php/','',app(UrlGenerator::class));
        }
        return str_replace('index.php/','',app(UrlGenerator::class)->to($path, $parameters, $secure));
    }
}

if (!function_exists('validator')) {
    /**
     * Create a new Validator instance.
     *
     * @param  array $data
     * @param  array $rules
     * @param  array $messages
     * @param  array $customAttributes
     * @return \Illuminate\Contracts\Validation\Validator
     */
    function validator(array $data = [], array $rules = [], array $messages = [], array $customAttributes = [])
    {
        $factory = app(ValidationFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($data, $rules, $messages, $customAttributes);
    }
}

if (!function_exists('view')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string $view
     * @param  array $data
     * @param  array $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function view($view = null, $data = [], $mergeData = [])
    {
        $factory = app(ViewFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData);
    }
}

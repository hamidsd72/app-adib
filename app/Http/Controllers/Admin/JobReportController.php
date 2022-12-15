<?php

namespace App\Http\Controllers\Admin;
use App\User;
use App\Model\ServicePackage;
use App\Model\JobReport;
use App\Model\Setting;
use App\Model\RollCall;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobReportController extends Controller {
    public function controller_title($type) {
        if ($type == 'sum') {
            return 'گزارش فعالیت ها';
        } elseif ('single') {
            return 'گزارش فعالیت';
        }
    } 
    public function controller_paginate() {
        return Setting::select('paginate')->where('user_id', $this->user_id())->first()->paginate;
    }
    public function __construct() {
        $this->middleware('auth');
    }
    public function toEnNumber($input) {
        $replace_pairs = array(
              '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
              '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'
        );
        
        return strtr( $input, $replace_pairs );
    }
    function user_id() {
        if ( auth()->user()->hasRole('مدیر ارشد') || auth()->user()->hasRole('مدیر') ) {
            return auth()->user()->id;
        } else {
            return auth()->user()->reagent_id;
        }
    }
    public function index($id=null) {
        if (isset($id)) {
            $items = User::where('reagent_id', $this->user_id() )->where('id',$id)->paginate($this->controller_paginate());
        } else {
            $items = User::where('reagent_id', $this->user_id() )->paginate($this->controller_paginate());
        }
        $users = User::where('reagent_id', $this->user_id() )->get(['id','first_name','last_name']);
        return view('admin.service.job-report.index', compact('id','items','users'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }
    public function store(Request $request) {
        if ( in_array('all',$request->user_id) ) {
            $items = User::where('reagent_id', $this->user_id() )->get(['id','first_name','last_name','reagent_id','reagent_code','referrer_id','referrer_code']);
        } else {
            $items = User::where('reagent_id', $this->user_id() )->where('id', $request->user_id )->get(['id','first_name','last_name','reagent_id','reagent_code','referrer_id','referrer_code']);
        }
        try {
            $strat  = $request->date;
            $end    = $request->date2;
            $user_work_times = RollCall::whereIn('user_id', $items->pluck('id'))->where('created_at','>=',j2g($this->toEnNumber($strat)))
            ->where('created_at','<=',j2g($this->toEnNumber($end)))->get(['user_id','created_at','updated_at']);

            foreach ($items as $item) {
                $item->reagent_id = JobReport::where('status','finish')->where('time','>',0)->where('user_id', $item->id)
                ->where('created_at','>=',j2g($this->toEnNumber($strat)))->where('created_at','<=',j2g($this->toEnNumber($end)))->get(['time','job_id']);
                
                // فعالیت ها
                $item->referrer_code = JobReport::where('status','finish')->where('time','>',0)->where('user_id', $item->id)
                ->where('created_at','>=',j2g($this->toEnNumber($strat)))->where('created_at','<=',j2g($this->toEnNumber($end)))->distinct()->get('job_id');

                foreach ($user_work_times->where('user_id',$item->id) as $work_time) {
                    // زمان کار به دقیقه
                    $item->referrer_id += $work_time->created_at->diffInMinutes( $work_time->updated_at , false);
                }
            }

            $work = RollCall::whereIn('user_id', $items->pluck('id'))->first(['user_id','created_at','updated_at']);
            return view('admin.service.job-report.create', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در عملیات بوجود آمده،مجددا تلاش کنید');
        }
    }
    public function adminAddReport(Request $request) {
        JobReport::create([
            'user_id'       => $request->userـid,
            'job_id'        => $request->job_id,
            'status'        => 'finish',
            'time'          => $request->time,
            'description'   => ' این گزارش توسط '.auth()->user()->first_name.' '.auth()->user()->last_name.' ثبت شده است '
        ]);
        return redirect()->back()->withInput()->with('flash_message', 'فعالیت ثبت شد');
    }
    public function show($id) {
        $item   = User::findOrFail($id);
        $items  = ServicePackage::where('reagent_id', $this->user_id())->where('user_id', $id)->orderByDesc('sort_by')->paginate($this->controller_paginate());
        return view('admin.service.job-report.show', compact('item','items'), ['title1' => $item->first_name.' '.$item->last_name, 'title2' => $this->controller_title('sum') ]);
    }
    public function edit($id) {
        $item = ServicePackage::where('reagent_id', $this->user_id())->findOrFail($id);
        $items = JobReport::where('time', '>', 0 )->where('job_id', $id )->orderByDesc('created_at')->paginate($this->controller_paginate());
        $id = $item->id;
        return view('admin.service.job-report.edit', compact('item','items','id'), ['title1' => $item->packageName()?$item->packageName()->title:'________', 'title2' => $this->controller_title('sum') ]);
    }
    public function map($id) {
        $item = JobReport::findOrFail($id);
        $lat = explode(',',$item->location)[0];
        $lng = explode(',',$item->location)[1];
        $map_api_key = Setting::first('map_api_key')->map_api_key;
        return view('user.map', compact('lat','lng','map_api_key'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum') ]);
    }
}



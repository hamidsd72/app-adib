<?php

namespace App\Http\Controllers\Admin;
use App\User;
use Carbon\Carbon;
use App\Model\RollCall;
use App\Model\Setting;
use App\Http\Controllers\Controller;

class RollCallController extends Controller {
    public function controller_title($type) {
        if ($type == 'sum') {
            return 'حضور و غیاب کاربران';
        } elseif ('single') {
            return 'حضور و غیاب کاربر';
        }
    } 
    public function controller_paginate() {
        return Setting::select('paginate')->where('user_id', $this->user_id())->first()->paginate;
    }
    public function __construct() {
        $this->middleware('auth');
    }
    function user_id() {
        if ( auth()->user()->hasRole('مدیر ارشد') || auth()->user()->hasRole('مدیر') ) {
            return auth()->user()->id;
        } else {
            return auth()->user()->reagent_id;
        }
    }
    public function index() {
        $items = RollCall::where('reagent_id', $this->user_id())->orderByDesc('id')->paginate($this->controller_paginate());
        // محاسبه ساعت کاری روز فرد
        foreach ($items as $item) {
            $item->reagent_id = $item->created_at->diffInMinutes($item->updated_at, false);
        }
        // گروه بندی به ترتیب روزهای شمسی
        // امسال
        foreach ($items->where('created_at','>',Carbon::now()->startOfYear()) as $item) {
            $item->text = 'امسال';
        }
        // سه ماه گذشته
        foreach ($items->where('created_at','>',Carbon::now()->startOfMonth()->subMonth(3)) as $item) {
            $item->text = 'سه ماهه اخیر ماه';
        }
        // این ماه
        foreach ($items->where('created_at','>',Carbon::now()->startOfMonth()) as $item) {
            $item->text = 'این ماه';
        }
        // این هفته
        foreach ($items->where('created_at','>',Carbon::now()->startOfWeek()) as $item) {
            $item->text = 'این هفته';
        }
        // امروز
        foreach ($items->where('created_at','>',Carbon::now()->startOfDay()) as $item) {
            $item->text = 'امروز';
        }

        $users = User::where('reagent_id', $this->user_id() )->get(['id','first_name','last_name']);
        return view('admin.roll-call.index', compact('items','users'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }
    public function show($id) {
        $items = RollCall::where('reagent_id', $this->user_id())->where('user_id',$id)->orderByDesc('id')->paginate($this->controller_paginate());
        foreach ($items as $item) {
            $item->reagent_id = $item->created_at->diffInMinutes($item->updated_at, false);
        }
        // گروه بندی به ترتیب روزهای شمسی
        // امسال
        foreach ($items->where('created_at','>',Carbon::now()->startOfYear()) as $item) {
            $item->text = 'امسال';
        }
        // سه ماه گذشته
        foreach ($items->where('created_at','>',Carbon::now()->startOfMonth()->subMonth(3)) as $item) {
            $item->text = 'سه ماهه اخیر ماه';
        }
        // این ماه
        foreach ($items->where('created_at','>',Carbon::now()->startOfMonth()) as $item) {
            $item->text = 'این ماه';
        }
        // این هفته
        foreach ($items->where('created_at','>',Carbon::now()->startOfWeek()) as $item) {
            $item->text = 'این هفته';
        }
        // امروز
        foreach ($items->where('created_at','>',Carbon::now()->startOfDay()) as $item) {
            $item->text = 'امروز';
        }
        $users = User::where('reagent_id', $this->user_id() )->get(['id','first_name','last_name']);
        return view('admin.roll-call.index', compact('id','items','users'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }
    // public function edit($id) {
    //     $item = Request::where('reagent_id', $this->user_id())->findOrFail($id);
    //     $items = User::where('reagent_id', $this->user_id() )->get(['id','first_name','last_name']);
    //     return view('admin.roll-call.edit', compact('item','items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum') ]);
    // }
}



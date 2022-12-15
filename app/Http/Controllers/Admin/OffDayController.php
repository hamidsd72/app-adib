<?php

namespace App\Http\Controllers\Admin;

use App\Model\Setting;
use App\Model\OffDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OffDayController extends Controller {
    public function controller_title($type) {
        if ($type == 'sum') {
            return 'روز و مناسبت تعطیل';
        } elseif ('single') {
            return 'روزها و مناسبت های تعطیل';
        }
    }
    function user_id() {
        if ( auth()->user()->hasRole('مدیر ارشد') || auth()->user()->hasRole('مدیر') ) {
            return auth()->user()->id;
        } else {
            return auth()->user()->reagent_id;
        }
    }
    public function toEnNumber($input) {
        $replace_pairs = array(
              '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
              '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'
        );
        
        return strtr( $input, $replace_pairs );
    }
    public function controller_paginate() {
        return Setting::select('paginate')->where('user_id', $this->user_id())->first()->paginate;
    }
    public function __construct() {
        $this->middleware('auth');
    }
    public function index() {
        $items = OffDay::where('user_id', $this->user_id() )->paginate($this->controller_paginate());
        return view('admin.off-day.index', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }
    public function store(Request $request) {
        $this->validate($request, [
            'title' => 'required|max:240',
            'date'  => 'required',
        ],
            [
                'title.required' => 'لطفا عنوان را وارد کنید',
                'title.max' => 'عنوان نباید بیشتر از 240 کاراکتر باشد',
                'title.required' => 'لطفا تاریخ را وارد کنید',
            ]);
        try {
            $item = new OffDay();
            $item->user_id  = $this->user_id();
            $item->title    = $request->title;
            $item->date     = Carbon::parse(j2g($this->toEnNumber($request->date)));
            $item->save();
            return redirect()->back()->withInput()->with('flash_message', ' روز تعطیل به تقویم شما با موفقیت اضافه شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در افزودن بوجود آمده،مجددا تلاش کنید');
        }
    }
    public function destroy($id) {
        OffDay::where('user_id', $this->user_id() )->findOrFail($id)->delete();
        return redirect()->back()->withInput()->with('flash_message', ' روز تعطیل از تقویم شما با موفقیت حذف اضافه شد.');
    }
    public function update(Request $request, $id) {
        $this->validate($request, [
            'title' => 'required|max:240',
            'date'  => 'required',
        ],
            [
                'title.required' => 'لطفا عنوان را وارد کنید',
                'title.max' => 'عنوان نباید بیشتر از 240 کاراکتر باشد',
                'title.required' => 'لطفا تاریخ را وارد کنید',
            ]);
        try {
            $item = OffDay::where('user_id', $this->user_id() )->findOrFail($id);
            $item->title    = $request->title;
            $item->date     = Carbon::parse(j2g($this->toEnNumber($request->date)));
            $item->update();
            return redirect()->back()->withInput()->with('flash_message', ' روز تعطیل به تقویم شما با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش بوجود آمده،مجددا تلاش کنید');
        }
    }

}



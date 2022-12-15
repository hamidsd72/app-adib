<?php

namespace App\Http\Controllers\Admin;
use App\User;
use App\Model\Request;
use App\Model\Setting;
use App\Http\Controllers\Controller;

class RequestController extends Controller {
    public function controller_title($type) {
        if ($type == 'sum') {
            return 'ثبت درخواست ها';
        } elseif ('single') {
            return 'ثبت درخواست';
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
        $items = Request::where('reagent_id', $this->user_id() )->orderByDesc('id')->paginate($this->controller_paginate());
        $users = User::where('reagent_id', $this->user_id() )->get(['id','first_name','last_name']);
        return view('admin.request.index', compact('items','users'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }
    public function toEnNumber($input) {
        $replace_pairs = array(
              '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
              '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'
        );
        
        return strtr( $input, $replace_pairs );
    }
    public function show($id) {
        $items = Request::where('reagent_id', $this->user_id() )->where('user_id',$id)->paginate($this->controller_paginate());
        $users = User::where('reagent_id', $this->user_id() )->get(['id','first_name','last_name']);
        return view('admin.request.index', compact('id','items','users'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }
    public function create() {
        $items = User::where('reagent_id', $this->user_id() )->get(['id','first_name','last_name']);
        return view('admin.request.create', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum') ]);
    }
    public function store(\Illuminate\Http\Request $request) {
        $this->validate($request, [
            'user_id'       => 'required',
            'date'          => 'required',
            'title'         => 'required|max:255',
            'description'   => 'required|max:2500'
        ],[
            'user_id.required' => 'لطفا نام کاربر را انتخاب کنید',
            'date.required' => 'لطفا تاریخ درخواست را انتخاب کنید',
            'title.required' => 'لطفا عنوان را وارد کنید',
            'title.max' => 'عنوان نباید بیشتر از ۲۵۵ کاراکتر باشد',
            'description.required' => 'لطفا توضیحات را وارد کنید',
            'description.max' => 'توضیحات نباید بیشتر از ۲۵۰۰ کاراکتر باشد'
        ]);
        User::where('reagent_id', $this->user_id() )->where('id',$request->user_id)->firstOrFail();
        try {
            $item = new Request();
            $item->user_id      = $request->user_id;
            $item->title        = $request->title;
            $item->date         = j2g($this->toEnNumber($request->date));
            $item->description  = $request->description;
            $item->employee_id  = auth()->user()->id;
            $item->reagent_id   = $this->user_id();
            $item->save();
            return redirect()->route('admin.request.index')->with('flash_message', 'درخواست با موفقیت ثبت شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ثبت درخواست بوجود آمده،مجددا تلاش کنید');
        }
    }
    public function edit($id) {
        $item = Request::where('reagent_id', $this->user_id())->findOrFail($id);
        $items = User::where('reagent_id', $this->user_id() )->get(['id','first_name','last_name']);
        return view('admin.request.edit', compact('item','items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum') ]);
    }
    public function update(\Illuminate\Http\Request $request, $id) {
        $this->validate($request, [
            'title'         => 'required|max:255',
            'date'          => 'required',
            'description'   => 'required|max:2500'
        ],[
            'title.required' => 'لطفا عنوان را وارد کنید',
            'date.required' => 'لطفا تاریخ درخواست را انتخاب کنید',
            'title.max' => 'عنوان نباید بیشتر از ۲۵۵ کاراکتر باشد',
            'description.required' => 'لطفا توضیحات را وارد کنید',
            'description.max' => 'توضیحات نباید بیشتر از ۲۵۰۰ کاراکتر باشد'
        ]);
        if ( auth()->user()->hasRole('مدیر ارشد') || auth()->user()->hasRole('مدیر') ) {
            $item = Request::where('reagent_id', $this->user_id() )->findOrFail($id);
        } else {
            $item = Request::where('reagent_id', $this->user_id() )->where('employee_id', auth()->user()->id)->find($id);
            if (!$item) {
                return redirect()->back()->withInput()->with('err_message', 'گزارش توسط شخص دیگری نوشته شده شما دسترسی به تغییر آن را ندارید');
            }
        }
        try {
            $item->title        = $request->title;
            $item->date         = j2g($this->toEnNumber($request->date));
            $item->description  = $request->description;
            $item->update();
            return redirect()->route('admin.request.index')->with('flash_message', 'درخواست با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش درخواست بوجود آمده،مجددا تلاش کنید');
        }
    }
    public function destroy($id) {
        if ( auth()->user()->hasRole('مدیر ارشد') || auth()->user()->hasRole('مدیر') ) {
            $item = Request::where('reagent_id', $this->user_id() )->findOrFail($id);
        } else {
            $item = Request::where('reagent_id', $this->user_id() )->where('employee_id', auth()->user()->id)->findOrFail($id);
        }
        try {
            $item->delete();
            return redirect()->route('admin.request.index')->with('flash_message', 'درخواست با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف درخواست بوجود آمده،مجددا تلاش کنید');
        }
    }
}



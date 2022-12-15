<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Model\Setting;
use App\Model\Job;
use App\Http\Controllers\Controller;

class JobController extends Controller {
    public function controller_title($type) {
        if ($type == 'sum') {
            return 'پروژه ها';
        } elseif ('single') {
            return 'پروژه';
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
        $items = Job::where('reagent_id', $this->user_id() )->orderByDesc('id')->paginate($this->controller_paginate());
        return view('admin.jobs.index', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }
    public function show($id) {
        $items = Job::findOrFail($id);
        return view('admin.jobs.index', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }
    public function create() {
        return view('admin.jobs.create', ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum') ]);
    }
    public function store(Request $request) {
        $this->validate($request, [
            'title'         => 'required|max:255',
        ],[
            'title.required' => 'لطفا عنوان را وارد کنید',
            'title.max' => 'عنوان نباید بیشتر از ۲۵۵ کاراکتر باشد',
        ]);
        try {
            $item = new Job();
            $item->title        = $request->title;
            $item->reagent_id   = $this->user_id();
            $item->save();
            return redirect()->route('admin.jobs.index')->with('flash_message', 'پروژه با موفقیت ثبت شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ثبت پروژه بوجود آمده،مجددا تلاش کنید');
        }
    }
    public function edit($id) {
        $item = Job::where('reagent_id', $this->user_id())->findOrFail($id);
        return view('admin.jobs.edit', compact('item'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum') ]);
    }
    public function update($id, Request $request) {
        $this->validate($request, [
            'title'         => 'required|max:255',
        ],[
            'title.required' => 'لطفا عنوان را وارد کنید',
            'title.max' => 'عنوان نباید بیشتر از ۲۵۵ کاراکتر باشد',
        ]);
        try {
            $item = Job::where('reagent_id', $this->user_id())->findOrFail($id);
            $item->title = $request->title;
            $item->update();
            return redirect()->route('admin.jobs.index')->with('flash_message', 'پروژه با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش پروژه بوجود آمده،مجددا تلاش کنید');
        }
    }
    public function destroy($id) {
        if ( auth()->user()->hasRole('مدیر ارشد') || auth()->user()->hasRole('مدیر') ) {
            $item = Job::where('reagent_id', $this->user_id())->findOrFail($id);
        }
        try {
            $item->delete();
            return redirect()->route('admin.jobs.index')->with('flash_message', 'درخواست با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف درخواست بوجود آمده،مجددا تلاش کنید');
        }
    }
}



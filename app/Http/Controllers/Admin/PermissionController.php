<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Permission;
use App\Model\Role;
use App\Model\Setting;

class PermissionController extends Controller {
    public function controller_title($type) {
        if ($type == 'sum') {
            return 'مجوزهای دسترسی';
        } elseif ('single') {
            return 'مجوز دسترسی';
        }
    }
    public function controller_paginate() {
        return Setting::select('paginate')->latest()->firstOrFail->paginate;
    }
    public function __construct() {
        $this->middleware('auth');
    }
    public function index() {
        $items = Role::where('id','>', 1)->get();
        $permissionList = Permission::get(['id','name','access']);
        return view('admin.permission.index', compact('items','permissionList'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }
    public function store(Request $request) {
        $access = array();
        if ($request->کاربران) { array_push($access, 'کاربران'); }
        if ($request->اعلانات) { array_push($access, 'اعلانات'); }
        if ($request->فعالیتها) { array_push($access, 'فعالیتها'); }
        if ($request->گزارشات) { array_push($access, 'گزارشات'); }
        if ($request->محتوا) { array_push($access, 'محتوا'); }
        if ($request->تنظیمات) { array_push($access, 'تنظیمات'); }

        $item = Permission::where('name', $request->id)->first();
        if ($item) {
            $item->access = implode(',',$access);
            $item->update();
        } else {
            $item           = new Permission();
            $item->user_id  = auth()->user()->id;
            $item->name     = $request->id;
            $item->access   = implode(',',$access);
            $item->save();
        }
        return redirect()->back()->with('flash_message', 'ویرایش دسترسی با موفقیت انجام شد');;
    }
    public function destroy($id) {
        Permission::findOrFail($id)->delete();
        return redirect()->back()->with('flash_message', 'حذف دسترسی با موفقیت انجام شد');
    }

}
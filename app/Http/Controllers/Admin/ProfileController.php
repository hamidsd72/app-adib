<?php

namespace App\Http\Controllers\Admin;
use App\User;
use App\Model\Setting;
use App\Model\Photo;
use App\Model\UserForm;
use App\Model\TourForm;
use App\Model\ProvinceCity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller {
    public function controller_title($type) {
        if ($type == 'sum') {
            return 'پروفایل';
        } elseif ('single') {
            return 'پروفایل';
        }
    }
    public function controller_paginate() {
        return Setting::firstOrFail('paginate')->paginate;
    }
    public function __construct() {
        $this->middleware('auth');
    }
    public function show() {
        $item  = User::find(auth()->user()->id);
        return view('admin.profile.show',compact('item'),['title1' => $this->controller_title('sum'), 'title2' => $this->controller_title('single')]);
    }
    public function edit() {
        $item=User::find(auth()->user()->id);
        return view('admin.profile.profile_edit',compact('item'),['title1' => $this->controller_title('sum'), 'title2' => $this->controller_title('single')]);
    }
    public function password_edit() {
        $item=User::find(auth()->user()->id);
        return view('admin.profile.password_edit',compact('item'),['title1' => $this->controller_title('sum'), 'title2' => $this->controller_title('single')]);
    }
    public function update(Request $request, $id) {
        $this->validate($request, [
            "name" => "nullable",
            "company__name" => "nullable",
            "company__phone" =>  "nullable",
            "company__fax" =>  "nullable",
            "company__telegram" =>  "nullable",
            "company__address" =>  "nullable",
            "company__site" =>  "nullable",
            "company__manager_phone" =>  "nullable",
            "company__representative_name" =>  "nullable",
            "company__representative_phone" =>  "nullable",
            "referred_to" =>  "nullable",
            "suspended" =>  "nullable",
            "draft_permission" =>  "nullable",
            "mohr" =>  "nullable",
            "emza" =>  "nullable",
            "profile" =>  "nullable",
        //     'whatsapp' => 'required',
        //     'date_birth' => 'required',
        //     'state_id' => 'required',
        //     'city_id' => 'required',
        //     'locate' => 'required',
        //     'address' => 'required',
        //     'education' => 'required',
        //     'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        // ],
        //     [
        //         'first_name.required'=>'لطفا نام خود را وارد کنید',
        //         'first_name.max'=>'نام نباید بیشتر از 240 کاراکتر باشد',
        //         'last_name.required'=>'لطفا نام خانوادگی خود را وارد کنید',
        //         'last_name.max'=>'نام خانوادگی نباید بیشتر از 240 کاراکتر باشد',
        //         'whatsapp.required'=>'لطفا شماره واتساپ فعال خود را وارد کنید',
        //         'date_birth.required'=>'لطفا تاریخ تولد خود را وارد کنید',
        //         'state_id.required'=>'لطفا استان خود را وارد کنید',
        //         'city_id.required'=>'لطفا شهر خود را وارد کنید',
        //         'locate.required'=>'لطفا منطقه خود را وارد کنید',
        //         'address.required'=>'لطفا آدرس خود را وارد کنید',
        //         'education.required'=>'لطفا مدرک تحصیلی خود را وارد کنید',
        //         'photo.image' => 'لطفا یک تصویر انتخاب کنید',
        //         'photo.mimes' => 'لطفا یک تصویر با پسوندهای (png,jpg,jpeg) انتخاب کنید',
        //         'photo.max' => 'لطفا حجم تصویر حداکثر 5 مگابایت باشد',
            ]);
        $item = User::find(auth()->user()->id);
        try {
            $item->update($request->all());
        // $item->first_name = $request->first_name;
        // $item->last_name = $request->last_name;
        // $item->whatsapp = $request->whatsapp;
        // $item->mobile = $request->mobile;
        // $item->email = $request->email;
        // $item->date_birth = num_to_en($request->date_birth);
        // $item->state_id = $request->state_id;
        // $item->city_id = $request->city_id;
        // $item->locate = $request->locate;
        // $item->address = $request->address;
        // $item->education = $request->education;
        // $item->update();
            if ($request->hasFile('photo')) {
                if ($item->photo) {
                    File::delete($item->photo);
                    $item->photo->delete();
                }
                $photo = new Photo();
                $photo->path = file_store($request->photo, 'source/asset/uploads/user/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'photo-');;
                $item->photo()->save($photo);
                img_resize(
                    $photo->path,//address img
                    $photo->path,//address save
                    100,// width: if width==0 -> width=auto
                    100// height: if height==0 -> height=auto
                // end optimaiz
                );
            }
        return redirect()->route('admin.profile.show')->with('flash_message', 'پروفایل با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message','مشکلی در ویرایش پروفایل بوجود آمده،مجددا تلاش کنید');
        }
    }
    public function password_update(Request $request, $id) {
        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
        ],
        [
            'password.required'=>'لطفا رمز عبور خود را وارد کنید',
            'password.min'=>'رمز عبور نباید کمتر از 6 کاراکتر باشد',
            'password.confirmed'=>'رمز عبور با تکرار آن برابر نیست',
        ]);
        $item = User::find(auth()->user()->id);
        try {
            if ($request->password) {
                $item->password = $request->password;
            }
            $item->update();
            return redirect()->route('admin.profile.show')->with('flash_message', 'رمز عبور با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message','مشکلی در تغیر رمز عبور بوجود آمده،مجددا تلاش کنید');
        }
    }
}

<?php
namespace App\Http\Controllers\Admin;

use App\User;
use App\Model\Setting;
use App\Model\Photo;
use App\Model\ProvinceCity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Model\About;

class UserController extends Controller {
    public function controller_title($type) {
        if ($type == 'sum') {
            return 'لیست کاربران';
        } elseif ('single') {
            return 'کاربران';
        }
    }
    public function controller_paginate() {
        return Setting::first('paginate')->paginate;
    }
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function userRole(Request $request) {
        $user = User::findOrFail($request->id);
        $user->role_id = $request->role_name;
        $user->save();
        return back()->with('flash_message', 'سمت با موفقیت تغییر یافت.');
    }
    public function index($type=null) {
        if (isset($type)) {
            $items = User::where('role_id' , $type)->paginate($this->controller_paginate());
        } else {
            $items = User::paginate($this->controller_paginate());
        }
        return view('admin.user.index', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }
    public function show($id) {
        $item = User::findOrFail($id);
        return view('admin.user.show', compact('item'), ['title1' => $this->controller_title('single'), 'title2' => ' پروفایل '.$this->controller_title('single')]);
    }
    public function create() {
        return view('admin.user.create', ['title1' => $this->controller_title('single'), 'title2' => ' افزودن '.$this->controller_title('single')]);
    }
    public function store(Request $request) {
        // $this->validate($request, [
        //     'company_name' => 'max:240',
        //     'first_name' => 'required|max:240',
        //     'last_name' => 'required|max:240',
        //     'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|numeric|unique:users',
        //     'email' => 'required|email|unique:users',
        //     'whatsapp' => 'required',
        //     'reagent_code' => 'integer',
        //     // 'date_birth' => 'required',
        //     // 'state_id' => 'required',
        //     // 'city_id' => 'required',
        //     // 'locate' => 'required',
        //     // 'address' => 'required',
        //     // 'education' => 'required',
        //     'password' => 'required|min:6|confirmed',
        //     'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        // ],
        // [
        //     'first_name.required' => 'لطفا نام خود را وارد کنید',
        //     'first_name.max' => 'نام نباید بیشتر از 240 کاراکتر باشد',
        //     'company_name.max' => 'نام شرکت نباید بیشتر از 240 کاراکتر باشد',
        //     'last_name.required' => 'لطفا نام خانوادگی خود را وارد کنید',
        //     'last_name.max' => 'نام خانوادگی نباید بیشتر از 240 کاراکتر باشد',
        //     'mobile.required' => 'لطفا موبایل خود را وارد کنید',
        //     'mobile.regex' => 'لطفا موبایل خود را وارد کنید',
        //     'mobile.digits' => 'لطفا فرمت موبایل را رعایت کنید',
        //     'mobile.numeric' => 'لطفا موبایل خود را بصورت عدد وارد کنید',
        //     'mobile.unique' => 'موبایل وارد شده یکبار ثبت نام شده',
        //     'email.required' => 'لطفا ایمیل خود را وارد کنید',
        //     'email.email' => 'فرمت ایمیل را رعایت کنید',
        //     'email.unique' => ' ایمیل وارد شده یکبار ثبت نام شده',
        //     'whatsapp.required' => 'لطفا شماره واتساپ فعال خود را وارد کنید',
        //     'reagent_code.integer' => 'مبلغ حقوق ساعتی را به عدد وارد کنید',
        //     'date_birth.required' => 'لطفا تاریخ تولد خود را وارد کنید',
        //     'state_id.required' => 'لطفا استان خود را وارد کنید',
        //     'city_id.required' => 'لطفا شهر خود را وارد کنید',
        //     'locate.required' => 'لطفا منطقه خود را وارد کنید',
        //     'address.required' => 'لطفا آدرس خود را وارد کنید',
        //     'education.required' => 'لطفا مدرک تحصیلی خود را وارد کنید',
        //     'password.required' => 'لطفا رمز عبور خود را وارد کنید',
        //     'password.min' => 'رمز عبور نباید کمتر از 6 کاراکتر باشد',
        //     'password.confirmed' => 'رمز عبور با تکرار آن برابر نیست',
        //     'photo.image' => 'لطفا یک تصویر انتخاب کنید',
        //     'photo.mimes' => 'لطفا یک تصویر با پسوندهای (png,jpg,jpeg) انتخاب کنید',
        //     'photo.max' => 'لطفا حجم تصویر حداکثر 5 مگابایت باشد',
        // ]);
             
        $item = new User();

        try {
            $item->user_name                        = $request->user_name;
            $item->name                             = $request->name;
            $item->email                            = $request->email;
            $item->company__name                    = $request->company__name;
            $item->company__phone                   = $request->company__phone;
            $item->company__fax                     = $request->company__fax;
            $item->company__telegram                = $request->company__telegram;
            $item->company__address                 = $request->company__address;
            $item->company__site                    = $request->company__site;
            $item->company__manager_phone           = $request->company__manager_phone;
            $item->company__representative_name     = $request->company__representative_name;
            $item->company__representative_phone    = $request->company__representative_phone;
            $item->referred_to                      = $request->referred_to;
            $item->suspended                        = $request->suspended;
            $item->draft_permission                 = $request->draft_permission;

            if ($request->password) {
                $item->password = $request->password;
            }

            if ($request->hasFile('mohr')) {
                $item->mohr = file_store($request->mohr, 'source/asset/uploads/user/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/mohr/', 'photo-');
                img_resize($item->mohr,$item->mohr,100,100);
            }

            if ($request->hasFile('emza')) {
                $item->emza = file_store($request->emza, 'source/asset/uploads/user/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/emza/', 'photo-');
                img_resize($item->emza,$item->emza,100,100);
            }

            if ($request->hasFile('profile')) {
                $item->profile = file_store($request->profile, 'source/asset/uploads/user/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/profile/', 'photo-');
                img_resize($item->profile,$item->profile,100,100);
            }

            $item->save();

            if ($request->hasFile('photo')) {
                $photo = new Photo();
                $photo->path = file_store($request->photo, 'source/asset/uploads/user/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'photo-');
                $item->photo()->save($photo);
                img_resize(
                    $photo->path,//address img
                    $photo->path,//address save
                    100,// width: if width==0 -> width=auto
                    100// height: if height==0 -> height=auto
                );
            }
            
            return redirect()->route('admin.user.list')->with('flash_message', 'کاربر با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد کاربر بوجود آمده،مجددا تلاش کنید');
        }
    }
    public function edit($id) {
        $item = User::findOrFail($id);
        return view('admin.user.edit', compact('item'), ['title1' => $this->controller_title('single'), 'title2' => ' ویرایش '.$this->controller_title('single')]);
    }
    public function update(Request $request, $id) {
        // $this->validate($request, [
        //     'first_name' => 'required|max:240',
        //     'last_name' => 'required|max:240',
        //     'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|numeric|unique:users,mobile,'.$id,
        //     'email' => 'required|email|unique:users,email,'.$id,
        //     'whatsapp' => 'required',
        //     'reagent_code' => 'integer',
        //     'date_birth' => 'required',
        //     'state_id' => 'required',
        //     'city_id' => 'required',
        //     'locate' => 'required',
        //     'address' => 'required',
        //     'education' => 'required',
        //     'password' => 'nullable|min:6|confirmed',
        //     'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        // ],
        //     [
        //         'first_name.required' => 'لطفا نام خود را وارد کنید',
        //         'first_name.max' => 'نام نباید بیشتر از 240 کاراکتر باشد',
        //         'last_name.required' => 'لطفا نام خانوادگی خود را وارد کنید',
        //         'last_name.max' => 'نام خانوادگی نباید بیشتر از 240 کاراکتر باشد',
        //         'mobile.required' => 'لطفا موبایل خود را وارد کنید',
        //         'mobile.regex' => 'لطفا موبایل خود را وارد کنید',
        //         'mobile.digits' => 'لطفا فرمت موبایل را رعایت کنید',
        //         'mobile.numeric' => 'لطفا موبایل خود را بصورت عدد وارد کنید',
        //         'mobile.unique' => 'موبایل وارد شده یکبار ثبت نام شده',
        //         'email.required' => 'لطفا ایمیل خود را وارد کنید',
        //         'email.email' => 'فرمت ایمیل را رعایت کنید',
        //         'email.unique' => ' ایمیل وارد شده یکبار ثبت نام شده',
        //         'reagent_code.integer' => 'مبلغ حقوق ساعتی را به عدد وارد کنید',
        //         'whatsapp.required' => 'لطفا شماره واتساپ فعال خود را وارد کنید',
        //         'date_birth.required' => 'لطفا تاریخ تولد خود را وارد کنید',
        //         'state_id.required' => 'لطفا استان خود را وارد کنید',
        //         'city_id.required' => 'لطفا شهر خود را وارد کنید',
        //         'locate.required' => 'لطفا منطقه خود را وارد کنید',
        //         'address.required' => 'لطفا آدرس خود را وارد کنید',
        //         'education.required' => 'لطفا مدرک تحصیلی خود را وارد کنید',
        //         'password.min' => 'رمز عبور نباید کمتر از 6 کاراکتر باشد',
        //         'password.confirmed' => 'رمز عبور با تکرار آن برابر نیست',
        //         'photo.image' => 'لطفا یک تصویر انتخاب کنید',
        //         'photo.mimes' => 'لطفا یک تصویر با پسوندهای (png,jpg,jpeg) انتخاب کنید',
        //         'photo.max' => 'لطفا حجم تصویر حداکثر 5 مگابایت باشد',
        //     ]);
        $item = User::findOrFail($id);
        try {
            $item->user_name                        = $request->user_name;
            $item->name                             = $request->name;
            $item->email                            = $request->email;
            $item->company__name                    = $request->company__name;
            $item->company__phone                   = $request->company__phone;
            $item->company__fax                     = $request->company__fax;
            $item->company__telegram                = $request->company__telegram;
            $item->company__address                 = $request->company__address;
            $item->company__site                    = $request->company__site;
            $item->company__manager_phone           = $request->company__manager_phone;
            $item->company__representative_name     = $request->company__representative_name;
            $item->company__representative_phone    = $request->company__representative_phone;
            $item->referred_to                      = $request->referred_to;
            $item->suspended                        = $request->suspended;
            $item->draft_permission                 = $request->draft_permission;

            if ($request->password) {
                $item->password = $request->password;
            }

            if ($request->hasFile('mohr')) {
                if ($item->mohr) File::delete($item->mohr);
                $item->mohr = file_store($request->mohr, 'source/asset/uploads/user/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/mohr/', 'photo-');
                img_resize($item->mohr,$item->mohr,100,100);
            }

            if ($request->hasFile('emza')) {
                if ($item->emza) File::delete($item->emza);
                $item->emza = file_store($request->emza, 'source/asset/uploads/user/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/emza/', 'photo-');
                img_resize($item->emza,$item->emza,100,100);
            }

            if ($request->hasFile('profile')) {
                if ($item->profile) File::delete($item->profile);
                $item->profile = file_store($request->profile, 'source/asset/uploads/user/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/profile/', 'photo-');
                img_resize($item->profile,$item->profile,100,100);
            }

            $item->update();

            if ($request->hasFile('photo')) {
                if ($item->photo) {
                    File::delete($item->photo->path);
                    $item->photo->delete();
                }
                $photo = new Photo();
                $photo->path = file_store($request->photo, 'source/asset/uploads/user/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'photo-');
                $item->photo()->save($photo);
                img_resize(
                    $photo->path,//address img
                    $photo->path,//address save
                    100,// width: if width==0 -> width=auto
                    100// height: if height==0 -> height=auto
                );
            }
            return redirect()->route('admin.user.list')->with('flash_message', 'کاربر با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش کاربر بوجود آمده،مجددا تلاش کنید');
        }
    }
    public function destroy($id) {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('flash_message', 'کاربر با موفقیت حذف شد.');
    }
    public function active($id, $type) {
        $item = User::find($id);
        try {
            $item->user_status = $type;
            $item->update();
            if ($type == 'blocked') {
                return redirect()->back()->with('flash_message', 'کاربر با موفقیت مسدود شد.');
            }
            if ($type == 'active') {
                return redirect()->back()->with('flash_message', 'کاربر با موفقیت فعال شد.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در تغییر وضعیت کاربر بوجود آمده،مجددا تلاش کنید');
        }
    }
    public function fastLogin($id) {
        auth()->loginUsingId($id, true);
        return redirect()->route('user.index');
    }
}



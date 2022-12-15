<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\User;
use App\Model\Leave;
use App\Model\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller {

    public function controller_paginate() {
        return Setting::first('paginate')->paginate;
    }

    public function toEnNumber($input) {
        $replace_pairs = array(
              '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
              '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'
        );
        
        return strtr( $input, $replace_pairs );
    }

    public function show()
    {
        $user = auth()->user();
        $data = $user;
        return view('panel.profile.edit', compact('data'), ['title' => 'ویرایش پروفایل']);
    }

    public function update(Request $input, $id)
    {
        $this->validate($input, [
            'name' => 'required|max:191',
            'password' => 'nullable|min:6|confirmed',
        ]);
        $user = User::where('id', $id)->first();

        $user->name=$input['name'];
        if (!blank($input->password)) {
            $user->password = bcrypt($input['password']);
        }
        if ($input->hasFile('profile')) {
            try {
                    $file = $input->profile;
                    $originalName = $file->getClientOriginalName();
                    $destinationPath = 'uploads/libraries/profiles/';
                    $extension = $file->getClientOriginalExtension();
                    $fileName = 'profile-' . md5(time() . '-' . $originalName) . '.' . $extension;
                    $file->move($destinationPath, $fileName);
                    $user->profile = $destinationPath . "" . $fileName;

            } catch (\Exception $e) {
                abort(500);
            }
        }
        if ($input->hasFile('mohr')) {
            try {
                    $file = $input->mohr;
                    $originalName = $file->getClientOriginalName();
                    $destinationPath = 'uploads/libraries/profiles/';
                    $extension = $file->getClientOriginalExtension();
                    $fileName = 'mohr-' . md5(time() . '-' . $originalName) . '.' . $extension;
                    $file->move($destinationPath, $fileName);
                    $user->mohr = $destinationPath . "" . $fileName;

            } catch (\Exception $e) {
                abort(500);
            }
        }
        if ($input->hasFile('emza')) {
            try {
                    $file = $input->emza;
                    $originalName = $file->getClientOriginalName();
                    $destinationPath = 'uploads/libraries/profiles/';
                    $extension = $file->getClientOriginalExtension();
                    $fileName = 'emza-' . md5(time() . '-' . $originalName) . '.' . $extension;
                    $file->move($destinationPath, $fileName);
                    $user->emza = $destinationPath . "" . $fileName;

            } catch (\Exception $e) {
                abort(500);
            }
        }

        $user->update();

        return Redirect()->back()->with('flash_message', 'پروفایل با موفقیت ویرایش شد.');

    }

    public function leave()
    {
        return view('panel.profile.leave', ['title' => 'درخواست مرخصی']);
    }

    public function my_leave() {
        $items = auth()->user()->leaves()->orderByDesc('id')->paginate($this->controller_paginate());
        return view('user.profile.my_leave', compact('items'), ['title' => 'درخواست های مرخصی من']);
    }

    public function leave_send(Request $request) {
        $this->validate($request, [
            'title'   => 'required',
            'as_date' => 'required',
            'to_date' => 'required',
            'as_time' => 'required',
            'to_time' => 'required',
            'type'    => 'required',
        ]);

        try {

            Leave::create([
                'user__id'    => auth()->user()->id,
                'role__id'    => auth()->user()->role()->id,
                'title'       => $request->title,
                'type'        => $request->type,
                'as_date'     => j2g($this->toEnNumber($request->as_date)),
                'to_date'     => j2g($this->toEnNumber($request->to_date)),
                'as_time'     => $request->as_time,
                'to_time'     => $request->to_time,
                'description' => $request->description
            ]);

            return redirect()->route('user.index')->with('flash_message', 'درخواست مرخصی شما ثبت شد، منتظر تایید مدیر باشید.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', 'مشگل در ثبت مرخصی ,لطفا مجددا درخواست کنید.');
        }

    }

}

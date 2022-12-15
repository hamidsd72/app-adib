<?php

namespace App\Http\Controllers\Admin;

use App\Model\Setting;
use App\Model\About;
use App\Model\AboutJoin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class RuleController extends Controller {
    public function controller_title($type) {
        if ($type == 'sum') {
            return 'قوانین';
        } elseif ('single') {
            return 'قوانین';
        }
    }
    public function controller_paginate() {
        return Setting::select('paginate')->where('user_id', $this->user_id())->first()->paginate;
    }
    public function __construct() {
        $this->middleware('auth');
    }
    public function edit() {
        $item = About::first();
        $items = AboutJoin::where('type','rule')->get();
        return view('admin.content.rule.edit', compact('item','items'), ['title1' => 'محتوا سایت', 'title2' => 'ویرایش قوانین سدار کارت']);
    }
    public function update(Request $request, $id) {
        if ( ! auth()->user()->hasRole('مدیر ارشد') ) { return false; }
        $this->validate($request, [
            'title_rule' => 'required|max:240',
            'text_rule' => 'required',
            'pic_rule' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ],
            [
                'title_rule.required' => 'لطفا عنوان را وارد کنید',
                'title_rule.max' => 'عنوان نباید بیشتر از 240 کاراکتر باشد',
                'text_rule.required' => 'لطفا متن را وارد کنید',
                'pic_rule.image' => 'لطفا یک تصویر انتخاب کنید',
                'pic_rule.mimes' => 'لطفا یک تصویر با پسوندهای (png,jpg,jpeg) انتخاب کنید',
                'pic_rule.max' => 'لطفا حجم تصویر حداکثر 5 مگابایت باشد',
            ]);
        $item = About::findOrFail($id);
        try {
            $item->title_rule = $request->title_rule;
            $item->text_rule = $request->text_rule;
            if ($request->hasFile('pic_rule')) {
                if (is_file($item->pic_rule))
                {
                    $old_path = $item->pic_rule;
                    File::delete($old_path);
                }
                $item->pic_rule = file_store($request->pic_rule, 'source/asset/uploads/rule/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'pic-');;
            }
            $item->update();
            if ($request->hasFile('pic_rule')) {
                img_resize(
                    $item->pic_rule,//address img
                    $item->pic_rule,//address save
                    700,// width: if width==0 -> width=auto
                    0// height: if height==0 -> height=auto
                // end optimaiz
                );
            }
            if(isset($request->title_join)) {
                $items=AboutJoin::where('type','rule')->get();
                foreach ($items as $itemss) {
                    $itemss->delete();
                }
                foreach ($request->title_join as $key=>$val) {
                    $pic=null;
                    if(isset($request->pic_join[$key]))
                    {
                        $pic=$request->pic_join[$key];
                    }
                    $about_join=new AboutJoin();
                    $about_join->title=$val;
                    $about_join->type='rule';
                    $about_join->text=$request->text_join[$key];
                    if (is_file($pic)) {
                        if(isset($request->pic_join1[$key]) and is_file($request->pic_join1[$key])) {
                            File::delete($request->pic_join1[$key]);
                        }
                        $about_join->pic = file_store($pic, 'source/asset/uploads/rule/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'pic_'.$key.'-');;
                    } elseif($request->pic_join1[$key]!=null) {
                        $about_join->pic=$request->pic_join1[$key];
                    }
                    $about_join->save();
                    img_resize(
                        $about_join->pic,//address img
                        $about_join->pic,//address save
                        700,// width: if width==0 -> width=auto
                        0// height: if height==0 -> height=auto
                    // end optimaiz
                    );
                }
            }
            return redirect()->back()->with('flash_message', 'قوانین با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش قوانین بوجود آمده،مجددا تلاش کنید');
        }
    }
}



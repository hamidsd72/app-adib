<?php

namespace App\Http\Controllers\User;

use App\Model\TimeForm;
use App\Http\Controllers\Controller;
use App\Model\Setting;
use Mail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Telegram\Bot\Laravel\Facades\Telegram;
use PHPMailer\PHPMailer\PHPMailer;

class TimeAdibController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }


    public function findLocation() {
        $location = 'UNKNOWN';
        if (getenv('HTTP_CLIENT_IP'))
            $location = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $location = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $location = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $location = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $location = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $location = getenv('REMOTE_ADDR');

        return $location;
    }
    
    public function toEnNumber($input) {
        $replace_pairs = array(
              '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
              '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'
        );
        
        return strtr( $input, $replace_pairs );
    }

    public function controller_paginate() {
        return Setting::first('paginate')->paginate;
    }

    public function index($status) {
        $items = TimeForm::orderByDesc('id');
        if ($status!='all') $items=$items->where('status',$status);

        if (auth()->user()->role_id>1) $items=$items->where('user_id',auth()->id());

        $items=$items->paginate($this->controller_paginate());

        foreach ($items as $data) {
            switch ($data->info) {
                case 'forget':
                    $data->info = 'فراموشی';
                    break;
                case 'mission':
                    $data->info = 'ماموریت از طرف شرکت';
                    break;
                case 'home_work':
                    $data->info = 'کار در منزل';
                    break;
                case 'meeting':
                    $data->info = 'جلسه';
                    break;
            }
        }

        return view('user.time_form.index', compact('items'), ['title' => 'ساعت ورود و خروج']);
    }

    public function status($id,$status) {
        if(auth()->user()->role_id!=1) abort(404);
        
        $item=TimeForm::findOrFail($id);
        try {
            $item->status=$status;
            $item->status_user_id=auth()->id();
            $item->update();
            return redirect()->back()->with('flash_message', 'با موفقیت تغییر وضعیت شد');

        } catch (\Exception $e) {
            return redirect()->back()->with('errr_message', 'خطا ذر تغییر وضعیت ,لطفا مجددا امتحان کنید');
        }
    }

    public function create() {
        return view('user.time_form.create', ['title' => 'ثبت ساعت ورود و خروج']);
    }

    public function create_post(Request $request) {
        $item = new TimeForm();
        try {
            $item->user_id =    auth()->user()->id;
            $item->name =       auth()->user()->name;
            $item->date_fa =    $request->date_fa;
            $item->date_en =    j2g($this->toEnNumber($request->date_fa));
            $item->time_login = $request->time_login;
            $item->time_exit =  $request->time_exit;
            $item->info =       $request->info;
            $item->text =       $request->text;
            $item->location=$this->findLocation();
            $item->divice=(request()->userAgent())??'';
            $item->save();
            return redirect()->route('user.time-login-index','all')->with('flash_message', 'با موفقیت ثبت شد');
        } catch (\Exception $e) {
            return redirect()->back()->with('errr_message', 'خطا در ثبت ,لطفا مجددا امتحان کنید');
        }
    }

}

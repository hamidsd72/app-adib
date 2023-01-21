<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Slider;
use App\Model\OffDay;
use App\User;
use App\Model\JobReport;
use App\Model\ServiceCat;
use App\Model\Ticket;
use App\Model\FormPrice;
use App\Model\About;
use App\Model\Service;
use App\Model\OffCode;
use App\Model\ServicePackage;
use App\Model\ServiceBuy;
use App\Model\ServiceFactor;
use App\Model\ServicePlus;
use App\Model\ServicePlusBuy;
use App\Model\Network;
use Carbon\Carbon;
use App\Model\RollCall;
use App\Model\Setting;
use App\Model\ServicePackagePrice;
use Illuminate\Support\Facades\Auth;
use App\Model\Contact;
use App\Model\Work;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller {
    
    public function __construct() {
       $this->middleware('auth');
    }
    function testuplo(Request $request) {
        try {
            $file = $request->image;
            $path = 'testuplo/files';
            $fileName = time().'.'.$file->getClientOriginalExtension();
            Storage::disk('adib-support')->putFileAs( env('UPLOAD_PATH_BASE').$path , $file , $fileName );
            return true;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    function fa_number($number) {
        $arr = array();
        for ($i=0; $i < strlen($number); $i++) { 
            switch ($number) {
                case $number[$i] == "0":
                    array_push($arr, "۰" );
                break;
                case $number[$i] == "1":
                    array_push($arr, "۱" );
                break;
                case $number[$i] == "2":
                    array_push($arr, "۲" );
                break;
                case $number[$i] == "3":
                    array_push($arr, "۳" );
                break;
                case $number[$i] == "4":
                    array_push($arr, "۴" );
                break;
                case $number[$i] == "5":
                    array_push($arr, "۵" );
                break;
                case $number[$i] == "6":
                    array_push($arr, "۶" );
                break;
                case $number[$i] == "7":
                    array_push($arr, "۷" );
                break;
                case $number[$i] == "8":
                    array_push($arr, "۸" );
                break;
                case $number[$i] == "9":
                    array_push($arr, "۹" );
                break;
            
                default:
                    array_push($arr, $number[$i] );
            } 
        }
        return implode("",$arr);
    }
    function user_id() {
        if ( auth()->user()->hasRole('مدیر ارشد') || auth()->user()->hasRole('مدیر') ) {
            return auth()->user()->id;
        } else {
            return auth()->user()->reagent_id;
        }
    }
    public function controller_paginate() {
        return Setting::select('paginate')->where('user_id', $this->user_id())->first()->paginate;
    }
    public function is_off_day() {
        $off_days = Setting::where('user_id', $this->user_id())->first()->off_day;
        if ( in_array(Carbon::now()->dayName, explode(',',$off_days) ) ) {
            return true;
        }
        return false;
    }
    public function index() {
        // dd(User::where('name','like','%خوانساری%')->get());  // 125
        $sliders = null;
        if ( ! auth()->user()->role()->id==4 ) {
            $sliders = Slider::all(['id','title','link','sort']);
        }
        $about = About::first();
        $setting = Setting::first(['support_call','title','logo_site']);
        $network = Network::where('status', 'active')->orderBy('sort')->get();
        return view('user.index', compact('sliders', 'about','network','setting'));
    }
    public function RollCall() {
        if ( auth()->user()->hasRole('مدیر ارشد') && auth()->user()->hasRole('مدیر') ) {
            $set                = Setting::where('user_id',$this->user_id())->first();
            $dailyStartTime     = $set->dailyStartTime;
            $dailyFinishTime    = $set->dailyFinishTime;

            $rollCall = RollCall::where('created_at','>', Carbon::now()->today())->where('user_id', auth()->user()->id)->first();
            // روزها یا رویدادهای تعطیل نمایندگی
            if( OffDay::where( 'user_id',$this->user_id() )->where( 'date', Carbon::now()->startOfDay() )->count() < 1 ) {
            // ساعت حضور و غیاب کارمند
            // اگر روز کاری بود
                if ( ! $this->is_off_day() ) {
                    // بروزرسانی ساعت حضور و غیاب کارمند
                    if ( $rollCall ) {
                        // ایا محدودیت کاری وحود دارد
                        if ( $dailyFinishTime ) {
                            // زمان محدودیت کاری
                            if ( Carbon::parse($dailyFinishTime)->diffInMinutes(Carbon::now(), false) < 0 ) {
                                $rollCall->updated_at = Carbon::now();
                                $rollCall->update();
                            }
                        } else {
                            $rollCall->updated_at = Carbon::now();
                            $rollCall->update();
                        }
                    } else {
                        // ایا محدودیت کاری وحود دارد
                        if ( $dailyStartTime ) {
                            // زمان محدودیت کاری
                            if ( Carbon::parse($dailyStartTime)->diffInMinutes(Carbon::now(), false) ) {
                                RollCall::create([
                                    "user_id"    => auth()->user()->id,
                                    "reagent_id" => $this->user_id()
                                ]);
                            }
                        } else {
                            RollCall::create([
                                "user_id"    => auth()->user()->id,
                                "reagent_id" => $this->user_id()
                            ]);
                        }
                    }
                }
                
            }
        }
    }
    public function updateRollCall() {
        $this->RollCall();
        return response()->json(['updatedRollCall' => 'ok'], 200);
    }
    public function tickets()  {
        $items = Contact::where('user_id', Auth::user()->id )->where('belongs_to_item', '=', 0)->orderBy('id','desc')->paginate($this->controller_paginate());
        foreach ($items as $item) {
            $item->mobile = Contact::where('belongs_to_item', $item->id )->count();
        }
        $serviceCat = ServiceCat::where('status', 'active')->get();
        return view('user.ticket.index', compact('items', 'serviceCat')); 
    }
    public function show_ticket($id)  {
        $item = Contact::where('user_id', Auth::user()->id )->where('id',$id)->first();
        try {
            $items = Contact::where('belongs_to_item', $item->id )->orderBy('id','desc')->paginate($this->controller_paginate());
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('err_message', 'فایل مورد نظر یافت نشد لطفا مجدد تلاش فرمائید');
        }
        $serviceCat = ServiceCat::where('status', 'active')->where('title', $item->category)->first();
        return view('user.ticket.show', compact('item','items','serviceCat')); 
    }
    public function services($cat_id)  {
        $ServiceCat = ServiceCat::where('id', $cat_id)->first();
        $items      = Service::where('status', 'active')->where('category_id', $ServiceCat->id)->paginate($this->controller_paginate());
        $form_price = FormPrice::all();
        // return view('user.service.index', compact('items','ServiceCat'));
        return view('user.services', compact('items','ServiceCat','form_price'));
    }
    public function service($id,$slug) {
        // $item = Service::where('slug', $slug)->where('id',$id)->first();
        $item = Service::findOrFail($id);
        $ServiceCat = ServiceCat::where('id', $item->category_id)->first();
        
        return view('user.sevices.show', compact('item', 'ServiceCat'));
    }
    public function packages_category() {
        $items = ServiceCat::where('type', 'package')->orderBy('id', 'ASC')->where('id', '!=', 4)->get();
        return view('user.package.category', compact('items'));
    }
    public function packages() {
        $items = ServicePackage::where('user_id', auth()->user()->id)->where('status', 'active')->orderByDesc('updated_at')->paginate($this->controller_paginate());
        $runningJob = JobReport::where('user_id', auth()->user()->id)->where('status', 'start')->first();
        return view('user.package.index', compact('items','runningJob'));
    }
    public function package($slug) {
        $item = ServicePackage::where('slug', $slug)->first();
        $map_api_key = Setting::first('map_api_key')->map_api_key;
        $agent = \App\Model\Agent::where('id', $item->custom)->first('long_lat');
        $lat = '35.73249';
        $lng = '51.42268';
        if ($agent&&$agent->long_lat) {
            $lat = explode(',',$agent->long_lat)[0];
            $lng = explode(',',$agent->long_lat)[1];
        }
        return view('user.package.show', compact('item','lat','lng','map_api_key'));

        /*    if($item->type=='learning')
        {
        }
        elseif($item->type=='sample')
        {
            $gold_package = ServicePackage::where('status', 'active')->where('custom', 1)->first();
            $service_custom = Service::where('category_id', '!=', 4)->whereNotIn('id', $gold_package->joins->pluck('service_id'))->get();
            return view('user.package.show', compact('item','service_custom'));
        }*/
    }
    public function job_create($package_id) {
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


        // $client  = @$_SERVER['HTTP_CLIENT_IP'];
        // $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        // $remote  = $_SERVER['REMOTE_ADDR'];
        // $location  = "Unknown";
        
        // if(filter_var($client, FILTER_VALIDATE_IP)) {
        //     $ip = $client;
        // } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
        //     $ip = $forward;
        // } else {
        //     $ip = $remote;
        // }

        // $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

        // if ($ip_data && $ip_data->geoplugin_latitude != null && $ip_data->geoplugin_longitude != null) {
        //     $location = $ip_data->geoplugin_latitude.','.$ip_data->geoplugin_longitude;
        // }
        if (ServicePackage::find($package_id)) {
            $runningJob = JobReport::where('user_id', auth()->user()->id)->where('status', 'start')->get();
    
            if ($runningJob->count()) {
                foreach ($runningJob as $job) {
                    $job->status = 'finish';
                    $job->time   = $job->created_at->diffInMinutes(Carbon::now(), false);
                    $job->save();
                }
            }
            JobReport::create([ 'user_id' => auth()->user()->id,'location' => $location,'job_id' => $package_id ]);
    
            return redirect()->back()->withInput()->with('flash_message', 'فعالیت آغاز شد');
        }
        return redirect()->back()->withInput()->with('err_message', 'فعالیت یافت نشد');
    }
    public function offline_job_create($package_id, $created_at, $description) {
        if (ServicePackage::find($package_id)) {
            $time = Carbon::parse(str_replace('%',' ',$created_at), 'UTC')->diffInMinutes(Carbon::now(), false);
            $attach = '';
            if ($request->hasFile('attach')) {
                $attach = file_store($request->attach, 'source/asset/uploads/job-report/' . auth()->user()->id . '/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/files/', 'file-');
            }
            JobReport::create([
                'user_id'       => auth()->user()->id,
                'job_id'        => $package_id,
                'status'        => 'finish',
                'description'   => $description,
                'time'          => $time,
                'attach'        => $attach,
            ]);
            return redirect()->back()->withInput()->with('flash_message', 'فعالیت ثبت شد');
        }
        return redirect()->back()->withInput()->with('err_message', 'فعالیت یافت نشد');
    }
    public function job_stop(Request $request) {
        $runningJob = JobReport::where('user_id', auth()->user()->id)->where('status', 'start')->get();
        if ($runningJob->count()) {
            foreach ($runningJob as $job) {
                $job->status        = 'finish';
                $job->time          = $job->created_at->diffInMinutes(Carbon::now(), false);
                $job->price         = intval($request->price);
                $job->description   = $request->description;
                if ($request->hasFile('attach')) {
                    $job->attach = file_store($request->attach, 'source/asset/uploads/job-report/' . auth()->user()->id . '/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/files/', 'file-');
                }
                $job->save();
            }
        }
        return redirect()->back()->withInput()->with('flash_message', 'فعالیت متوقف شد');
    }
    public function reserve($type, $slug) {
        if ($type == "package") {
            $item = ServicePackage::where('slug', $slug)->first();
        } elseif ($type == 'service') {
            $item = Service::where('slug', $slug)->first();
        }
        if (isset($item)) {
            try {

                $service_buy = new ServiceBuy();
                $service_buy->buy_id = $item->id;
                $service_buy->buy_type = $type;
                $service_buy->buy_id = $item->id;
            } catch (\Exception $error) {
                return redirect()->back()->withInput()->with('err_message', 'مشکلی به وجود آمده، لطفا با پشتیبانی تماس بگیرید.');
            }

        } else {
            return redirect()->back()->withInput()->with('err_message', 'سرویس مورد نظر یافت نشد لطفا مجدد تلاش فرمائید');

        }

    }
    public function validation_email(Request $request) {
        $p_id = $request->get('p_id');
        $star = $request->get('value_star');
        return response()->json(['success1' => true, 'p' => $p_id]);
    }
    public function login_package_buy(Request $request, $slug, $price_id = null) {
        if (!is_null($price_id)) {
            session(['price_id' => $price_id]);
        }
        session(['package_slug' => $slug]);
        if (isset($request->service)) {
            session(['service_gold' => $request->service]);
        }

        if (isset($request->plus)) {
            session(['plus' => $request->plus]);
        }
        if (Auth::check()) {
            $off_code=OffCode::where('code',$request->off_code)->where('status','active')->first();
            //isset code
            if($off_code)
            {
                // not used code
                if($off_code->used=='no')
                {
                    // used code all user
                    if($off_code->user_id==0)
                    {
                        $factor=ServiceFactor::where('user_id',Auth::user()->id)->where('off_code',$request->off_code)->wherein('pay_status',['paid','credit'])->first();
                        //used user code all user
                        if(!$factor)
                        {
                            session(['off_id' => $off_code->id]);
                        }

                    }
                    // used code personal user
                    else {
                        if($off_code->user_id==Auth::user()->id) {
                            session(['off_id' => $off_code->id]);
                        }
                    }
                }
            }
            return redirect()->route('user.package.buy');
        }
        if ($request->type == "login") {
            return redirect()->route('login');
        } else {
            return redirect()->route('user.mobile');
        }
    }
    public function package_buy() {
        $order_code = 10001;
        $slug = '';
        $service_gold = '';
        $plus = "";
        $plus_price = 0;

        if (session()->has('package_slug')) {
            $slug = session('package_slug');
            if (session()->has('plus')) {
                $plus = session('plus');
            }
            if (session()->has('service_gold')) {
                $service_gold = session('service_gold');
            }
            $package = ServicePackage::where('slug', $slug)->first();
            if ($service_gold != "") {
                if ($package->custom_service_count < count($service_gold)) {
                    return redirect()->route('user.index')->with('err_message', 'دوست عزیز ; موردی مشکوک به ساختارشکنی مشاهده شده ، لطفا مجدد و با شرایط درست اقدام فرمائید ');
                }
            }
            if (session()->has('price_id')) {
                $package_price = ServicePackagePrice::find(session('price_id'));
            }
            try {
                $item = ServiceFactor::latest()->first();
                if ($item) {
                    $order_code = intval($item->order_code) + intval(rand(10, 100));
                }
                $factor = new ServiceFactor();
                $factor->order_code = $order_code;
                $factor->user_id = Auth()->user()->id;
                if (isset($package_price)) {
                    $factor->all_price = $package_price->price;
                    $factor->month_time = $package_price->month_time;
                } else {
                    $factor->all_price = $package->price;
                }
                $factor->package_id = $package->id;
                $factor->type = 'package';
                if ($package->custom == 1) {
                    $factor->custom = 1;
                }
                $factor->save();
                foreach ($package->join as $service) {
                    $servide_buy = new ServiceBuy();
                    $servide_buy->factor_id = $factor->id;
                    $servide_buy->price = $service->price;
                    $servide_buy->buy_id = $service->id;
                    $servide_buy->save();
                    if ($plus != "" and count($plus) > 0) {
                        foreach ($plus as $plu) {
                            $item_plus = ServicePlus::find($plu);
                            if (isset($item_plus) and $item_plus->service->id == $service->id) {
                                $new_plus = new ServicePlusBuy ();
                                $new_plus->service_id = $service->id;
                                $new_plus->plus_id = $item_plus->id;
                                $new_plus->price = $item_plus->price;
                                $new_plus->factor_id = $factor->id;
                                $new_plus->buy_id = $servide_buy->id;
                                $new_plus->user_id = auth()->user()->id;
                                $new_plus->save();
                                $plus_price += $item_plus->price;
                            }
                        }
                    }
                }

                if ($package->custom == 1) {
                    if ($service_gold != null) {
                        $service_arry = [];
                        foreach ($service_gold as $serv) {
                            array_push($service_arry, $serv);
                            $service_golds = Service::whereIn('id', $service_arry)->get();
                        }
                        foreach ($service_golds as $service) {
                            $servide_buy = new ServiceBuy();
                            $servide_buy->factor_id = $factor->id;
                            $servide_buy->price = $service->price;
                            $servide_buy->buy_id = $service->id;
                            $servide_buy->type = 1;
                            $servide_buy->save();
                            if ($plus != '' and count($plus) > 0) {
                                foreach ($plus as $plu) {
                                    $item_plus = ServicePlus::find($plu);
                                    if (isset($item_plus) and $item_plus->service->id == $service->id) {
                                        $new_plus = new ServicePlusBuy ();
                                        $new_plus->service_id = $service->id;
                                        $new_plus->plus_id = $item_plus->id;
                                        $new_plus->price = $item_plus->price;
                                        $new_plus->factor_id = $factor->id;
                                        $new_plus->buy_id = $servide_buy->id;
                                        $new_plus->user_id = auth()->user()->id;
                                        $new_plus->save();
                                        $plus_price += $item_plus->price;
                                    }
                                }
                            }
                        }
                    }
                }
                $factor->all_price += $plus_price;
                $factor->save();
                //off code set
                if (session()->has('off_id')) {
                    $off_code=OffCode::where('id',session('off_id'))->where('used','no')->where('status','active')->first();
                    if($off_code)
                    {
                        if($off_code->user_id==0)
                        {
                            $factor_set=ServiceFactor::where('user_id',Auth::user()->id)->where('off_code',$off_code->code)->wherein('pay_status',['paid','credit'])->first();
                            if(!$factor_set)
                            {
                                $total=$factor->all_price-($factor->all_price*$off_code->percent)/100;
                                $factor->off_code=$off_code->code;
                                $factor->off_percent=$off_code->percent;
                                $factor->total=$total;
                                $factor->save();
                            }
                        }
                        else {
                            if($off_code->user_id==Auth::user()->id)
                            {
                                $total=$factor->all_price-($factor->all_price*$off_code->percent)/100;
                                $factor->off_code=$off_code->code;
                                $factor->off_percent=$off_code->percent;
                                $factor->total=$total;
                                $factor->save();
                            }
                        }
                    }
                }
                session()->forget('package_slug');
                session()->forget('service_gold');
                session()->forget('plus');
                if (session()->has('price_id')) {
                    session()->forget('price_id');
                }
                if (session()->has('off_id')) {
                    session()->forget('off_id');
                }
                if (auth()->user()->reagent_code == 'rytl_user' || auth()->user()->sim == 'rightel') {
                    return redirect()->route('user.refah.pay', [$factor->id, "package"]);
                }
                if(!is_null($factor->total))
                {
                    return redirect()->route('user.zarinpal-pay-user', [$factor->id, $factor->total, Auth()->user()->id, "package"]);
                }
                else {
                    return redirect()->route('user.zarinpal-pay-user', [$factor->id, $factor->all_price, Auth()->user()->id, "package"]);
                }
            } catch (\Exception $e) {
                session()->forget('package_slug');
                session()->forget('service_gold');
                session()->forget('plus');
                if (session()->has('price_id')) {
                    session()->forget('price_id');
                }
                if (session()->has('off_id')) {
                    session()->forget('off_id');
                }
                return redirect()->back()->with('err_message','عملیات با مشکل مواجه شد، مجددا تلاش بفرمایید(catch)');
            }

        }
        session()->forget('package_slug');
        session()->forget('service_gold');
        session()->forget('plus');
        if (session()->has('price_id')) {
            session()->forget('price_id');
        }
        if (session()->has('off_id')) {
            session()->forget('off_id');
        }
        return redirect()->back()->with('err_message','عملیات با مشکل مواجه شد، مجددا تلاش بفرمایید(session)');
    }
    public function off_check($code,$price,Request $request) {
        $off_code=OffCode::where('code',$code)->where('status','active')->first();
        //isset code
        if($off_code)
        {
            // not used code
            if($off_code->used=='no')
            {
                // used code all user
                if($off_code->user_id==0)
                {
                    $factor=ServiceFactor::where('user_id',Auth::user()->id)->where('off_code',$code)->wherein('pay_status',['paid','credit'])->first();
                    //used user code all user
                    if($factor)
                    {
                        return
                            [
                                'type'=>'danger',
                                'msg'=>'کد تخفیف توسط شما استفاده شده',
                            ];
                    }
                    $total=$price-($price*$off_code->percent)/100;
                    return
                        [
                            'type'=>'success',
                            'percent'=>$off_code->percent,
                            'total'=>$total,
                            'msg'=>'کد تخفیف اعمال شد',
                        ];
                }
                // used code personal user
                else {
                    if($off_code->user_id==Auth::user()->id) {
                        $total=$price-($price*$off_code->percent)/100;
                        return
                            [
                                'type'=>'success',
                                'percent'=>$off_code->percent,
                                'total'=>$total,
                                'msg'=>'کد تخفیف اعمال شد',
                            ];
                    }
                    else {
                        return
                            [
                                'type'=>'danger',
                                'msg'=>'کد تخفیف اشتباه می باشد',
                            ];
                    }
                }
            }
            // used code Check out the credit
            else {
                return
                    [
                        'type'=>'danger',
                        'msg'=>'اعتبار کد تخفیف به اتمام رسیده',
                    ];
            }
        }
        // not code
        else {
            return
            [
                'type'=>'danger',
                'msg'=>'کد تخفیف اشتباه می باشد',
            ];
        }
    }
}

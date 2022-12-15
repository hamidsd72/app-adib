<?php

namespace App\Http\Controllers\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Sms;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Model\Filep; 
use App\Model\ProvinceCity;
use App\Model\Slider;
use App\Model\ServiceCat;
use App\Model\Photo;
use App\Model\Custom;
use App\Model\About;
use App\Model\Setting;
use App\Model\OffCode;
use App\Model\ServicePackage;
use App\Model\Network;
use App\Model\ServiceFactor;
use App\Model\ServicePlus;
use App\Model\ServicePlusBuy;
use App\Model\ServicePackagePrice;
use Illuminate\Support\Facades\Auth;
use App\Model\Contact;
use Illuminate\Support\Facades\Cookie;

class GuestController extends Controller
{
    public function index()
    {
        if (auth()->user()) {
            return redirect()->route('user.index');
        }

        // return view('auth.register2', compact('network','serviceCat','sliders', 'about', 'packages', 'customers', 'service_custom','gold_package','slidersPhotos'));
        $setting = Setting::first();
        return view('auth.register' , compact('setting'));
        
    }

    public function create()
    {
        if (auth()->user()) {
            return redirect()->route('user.index');
        }

        $show_modal = true;
        return view('auth.register1', compact('show_modal'));
    }
}
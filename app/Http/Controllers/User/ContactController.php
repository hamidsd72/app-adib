<?php

namespace App\Http\Controllers\User;

use App\Model\About;
use App\Model\Network;
use App\Model\Setting;
use App\Http\Controllers\Controller;

class ContactController extends Controller {
    public function show() {
        $about = About::first();
        $network = Network::where('status', 'active')->orderBy('sort')->get();
        $setting = Setting::first('title');
        return view('user.contact.show',compact('about','network','setting'));
    }
}

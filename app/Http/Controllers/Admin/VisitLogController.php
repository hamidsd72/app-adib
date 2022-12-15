<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Model\Setting;
use App\Model\Visit;
use App\Model\Meta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;


class VisitLogController extends Controller
{
    public function controller_title($type)
    {
        if ($type == 'sum') {
            return 'بازدید ها';
        } elseif ('single') {
            return 'بازدیدها';
        }
    }

    public function controller_paginate()
    {
        $settings = Setting::select('paginate')->latest()->firstOrFail();
        return $settings->paginate;
    }

    public function __construct()
    {
        $this->middleware(['auth','isAdmin']);
    }

    public function index()
    {
//        $visitlogs = VisitLog::paginate($this->controller_paginate());
        $visit=Visit::first();
        $users = User::get();
        $online = 0;
        foreach ($users as $user) {
            if (Cache::has('user-is-online-' . $user->id)) {
                $online++;
            }
        }
        return view('admin.visit_logs.index', compact( 'online','visit'), ['title1' => 'گزارش', 'title2' => $this->controller_title('sum')]);
    }



}



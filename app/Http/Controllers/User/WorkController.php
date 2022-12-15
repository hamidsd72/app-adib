<?php

namespace App\Http\Controllers\User;

use App\Model\VisitComment;
use App\Http\Controllers\Controller;
use App\Model\Library;
use App\Model\WorkTimesheet;
use App\Model\TimesheetCircle;
use App\Model\VisitDoneJob;
use Carbon\Carbon;
use http\Env\Response;
use App\Model\Setting;
use App\Model\Ticket;
use App\Model\Phase;
use App\User;
use App\Model\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function controller_paginate() {
        return Setting::first('paginate')->paginate;
    }
    
    public function index($id=null) {
        if (is_null($id)) $items=Work::where('user_id',auth()->id())->orderByDesc('updated_at')->paginate($this->controller_paginate());
        else $items=Work::where('id',$id)->get();
        return view('user.works.index',compact('items'));
    }

    public function search(Request $request) {
        $items = Work::where('user_id',auth()->id())->where('title', 'like', '%'.$request->search.'%')->orderByDesc('updated_at')->paginate($this->controller_paginate());
        return view('user.works.index',compact('items'));
    }

    public function create()
    {
        $users = User::where('suspended',0)->get();
        $companies = User::where('suspended',0)->where('role_id',5)->get();
        $types = WorkTimesheet::types();
        return view('user.works.create',compact('users','companies','types'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
           'referrer_id' => 'required',
           'company_id' => 'required',
           'type' => 'required',
           'title' => 'required',
           'description' => 'required',
        ],[
            'referrer_id.required' => 'این فیلد الزامی است',
            'company_id.required' => 'این فیلد الزامی است',
            'type.required' => 'این فیلد الزامی است',
            'title.required' => 'این فیلد الزامی است',
            'description.required' => 'این فیلد الزامی است',
        ]);
        
        $item=new Work();
        $item->title=$request->title;
        $item->description=$request->description;
        $item->referrer_id=$request->referrer_id;
        $item->company_id=$request->company_id;
        $item->type=$request->type;
        $item->user_id=auth()->id();
        $item->save();

        return redirect()->route('user.works')->with('flash_message','با موفقیت ثبت شد');
    }

    public function edit($id)
    {
        $item=Work::findOrFail($id);
        $users = User::where('suspended',0)->get();
        $companies = User::where('suspended',0)->where('role_id',5)->get();
        $types = WorkTimesheet::types();
        return view('user.works.edit',compact('users','companies','item','types'));
    }

    public function update($id,Request $request)
    {
        $item=Work::findOrFail($id);
        $this->validate($request,[
            'referrer_id' => 'required',
            'company_id' => 'required',
            'type' => 'required',
            'title' => 'required',
            'description' => 'required',
        ],[
            'referrer_id.required' => 'این فیلد الزامی است',
            'company_id.required' => 'این فیلد الزامی است',
            'type.required' => 'این فیلد الزامی است',
            'title.required' => 'این فیلد الزامی است',
            'description.required' => 'این فیلد الزامی است',
        ]);

        $item->title=$request->title;
        $item->description=$request->description;
        $item->referrer_id=$request->referrer_id;
        $item->company_id=$request->company_id;
        $item->type=$request->type;
        $item->update();

        return redirect()->route('user.works')->with('flash_message','با موفقیت ثبت شد');
    }

    public function stop(Request $request)
    {
        $item=Work::find($request->id);

        //END WORKSHEET IF EXIST
        $today=Carbon::now();
        $time=$today->format('H:i:s');
        $date=$today->format('Y-m-d');

        $workTimesheet=WorkTimesheet::WorkTimeSheetByStatus('work',$item->id,'doing');

        if ($workTimesheet){
            $workTimesheet->status='finished';
            $workTimesheet->endTime=$time;
            $workTimesheet->endDate=$date;
            $workTimesheet->update();
        }

        return redirect()->back()->with('flash_message','با موفقیت پایان یافت');
    }


}

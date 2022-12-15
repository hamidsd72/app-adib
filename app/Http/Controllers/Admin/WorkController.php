<?php

namespace App\Http\Controllers\Amdin;

use App\Models\VisitComment;
use App\Http\Controllers\Controller;
use App\Models\Library;
use App\Model\WorkTimesheet;
use App\TimesheetCircle;
use App\Models\VisitDoneJob;
use Carbon\Carbon;
use http\Env\Response;
use App\Models\Visit;
use App\Models\Ticket;
use App\Models\Phase;
use App\Model\Work;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkController extends Controller {

    public function __construct() { $this->middleware('auth'); }

    public function index($id=null) {
        if (is_null($id)) $items=Work::where('user_id',auth()->id())->orderByDesc('updated_at')->get();
        else $items=Work::where('id',$id)->get();
        return view('panel.work.index',compact('items'))->with('title','کارهای من');
    }
    public function create()
    {
        $users = User::where('suspended',0)->get();
        $companies = User::where('suspended',0)->where('role_id',5)->get();
        return view('panel.work.create',compact('users','companies'));
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

        return redirect()->back()->with('flash_message','با موفقیت ثبت شد');
    }

    public function edit($id)
    {
        $item=Work::findOrFail($id);
        $users = User::where('suspended',0)->get();
        $companies = User::where('suspended',0)->where('role_id',5)->get();
        return view('panel.work.edit',compact('users','companies','item'));
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

        return redirect()->back()->with('flash_message','با موفقیت ثبت شد');
    }

    public function stop(Request $request)
    {
        $item=Work::findOrFail($request->id);

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

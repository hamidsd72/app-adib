<?php

namespace App\Http\Controllers\User;

use App\Models\VisitComment;
use App\Http\Controllers\Controller;
use App\Models\Library;
use App\Model\WorkTime;
use App\Model\WorkTimesheet;
use App\Model\TimesheetCircle;
use App\Models\VisitDoneJob;
use Carbon\Carbon;
use http\Env\Response;
use App\User;
use App\Models\Visit;
use App\Model\Ticket;
use App\Model\Income;
use App\Models\Phase;
use App\Models\Hour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\In;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TimeExport;

class WorkHourController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        if (auth()->user()->role_id==1 || auth()->user()->role_id==3) return redirect()->route('admin.workhour-dashboard');
        $users = User::where('role_id',4)->where('suspended',0)->whereHas('startHour')->get()->load('startHour');
        return view('admin.workhour.index',compact('users'))->with('title','ساعات کاری');
    }

    public function dashboard(Request $request)
    {
        $users=User::where(['role_id'=>4,'suspended'=>0])->get();
        $users1=User::where(['role_id'=>4,'suspended'=>0])->whereHas('startHour')->get()->load('startHour');
        $companies = \App\Models\User::where('suspended',0)->where('role_id',5)->get();
        return view('admin.workhour.dashboard',compact('users','users1','companies'))->with('title','انتخاب کاربر برای ساعات کاری');
    }

    public function fetch(Request $request)
    {
        if ($request->type==1){

            $this->validate($request,
                [
                    'from_date'=>'required',
                ]
            );
            if (!$request->user_id){
                $request->user_id=auth()->id();
                $request->request->add(['user_id' => auth()->id()]);
            }
            $user=User::find($request->user_id);
            $from_date=$this->get_correct_date($request->from_date);
            $users=User::where(['role_id'=>4,'suspended'=>0])->whereHas('startHour',function ($query) use ($from_date){
                $query->where('startDate',$from_date);
            })->get();
            $groups=WorkTimesheet::whereBetween('startDate', [$from_date, $from_date])->with('user')->groupBy('startDate')->get();
            
            return view('panel.workhour.hoursByDate',compact('groups','users'))->with('title',"ساعات کاری $request->from_date");

        }else{

            $this->validate($request,
            [
                'from_date'=>'required',
                'to_date'=>'required',
            ]
            );
            if (!$request->user_id){
                $request->user_id=auth()->id();
                $request->request->add(['user_id' => auth()->id()]);
            }
            $user=User::find($request->user_id);
            $from_date=$this->get_correct_date($request->from_date);
            $to_date=$this->get_correct_date($request->to_date);
            if ($request->type==3){
                $company_id=$request->company_id;

                $user=User::find($company_id);
                $groups1=WorkTimesheet::where('type','work')->whereHas('work',function ($q) use ($company_id){
                    $q->where('company_id',(int)$company_id);
                })->whereBetween('startDate', [$from_date, $to_date])->with(['user'])->groupBy('startDate')->get();
                $groups2=WorkTimesheet::where('type','ticket')->whereHas('ticket',function ($q) use ($company_id){
                    $q->where('user__id',(int)$company_id);
                })->whereBetween('startDate', [$from_date, $to_date])->with(['user'])->groupBy('startDate')->get();
                $groups=$groups1->merge($groups2);
                $items1=WorkTimesheet::where('type','work')->whereHas('work',function ($q) use ($company_id){
                    $q->where('company_id',(int)$company_id);
                })->whereBetween('startDate', [$from_date, $to_date])->get();
                $items2=WorkTimesheet::where('type','ticket')->whereHas('ticket',function ($q) use ($company_id){
                    $q->where('user__id',(int)$company_id);
                })->whereBetween('startDate', [$from_date, $to_date])->get();

                $items=$items1->merge($items2);

                $items_fani=Ticket::where('user__id',(int)$company_id)->whereIN('role__id',[2,9])->whereBetween('created_at', [$from_date, $to_date])->get();
                return view('panel.workhour.hoursByCompany',compact('groups','items','items_fani'))->with('title',"ساعات کاری شرکت  $user->company__name");
            }else{
                $groups=WorkTimesheet::where('user_id',$request->user_id)->whereBetween('startDate', [$from_date, $to_date])->with('user')->groupBy('startDate')->get();
                $items=WorkTimesheet::where('user_id',$request->user_id)->whereBetween('startDate', [$from_date, $to_date])->get();
            }
            return view('panel.workhour.hours',compact('groups','items'))->with('title',"ساعات کاری $user->name");

        }
    }

    public function exportExcel(Request $request)
    {
        if($request->rand)
        {
            $rand=$request->rand;
            if ($request->type==1){

                $this->validate($request,
                    [
                        'from_date'=>'required',
                    ]
                );
                if (!$request->user_id){
                    $request->user_id=auth()->id();
                    $request->request->add(['user_id' => auth()->id()]);
                }
                $user=User::find($request->user_id);
                $from_date=$this->get_correct_date($request->from_date);
                $users=User::where(['role_id'=>4,'suspended'=>0])->whereHas('startHour',function ($query) use ($from_date){
                    $query->where('startDate',$from_date);
                })->get();
                $groups=WorkTimesheet::whereBetween('startDate', [$from_date, $from_date])->with('user')->groupBy('startDate')->get();

                $t_title="ساعات کاری $request->from_date";
                $type='time';
                return Excel::download(new TimeExport($groups,$users,$t_title,$type), 'TimeExport'.$rand.'.xlsx');

            }else{

                $this->validate($request,
                    [
                        'from_date'=>'required',
                        'to_date'=>'required',
                    ]
                );
                if (!$request->user_id){
                    $request->user_id=auth()->id();
                    $request->request->add(['user_id' => auth()->id()]);
                }
                $user=User::find($request->user_id);
                $from_date=$this->get_correct_date($request->from_date);
                $to_date=$this->get_correct_date($request->to_date);
                if ($request->type==3){
                    $company_id=$request->company_id;
                    $user=User::find($company_id);
                    $groups1=WorkTimesheet::whereHas('work',function ($q) use ($company_id){
                        $q->where('company_id',$company_id);
                    })->whereBetween('startDate', [$from_date, $to_date])->with(['user'])->groupBy('startDate')->get();
                    $groups2=WorkTimesheet::whereHas('ticket',function ($q) use ($company_id){
                        $q->where('user__id',$company_id);
                    })->whereBetween('startDate', [$from_date, $to_date])->with(['user'])->groupBy('startDate')->get();
                    $groups=$groups1->merge($groups2);
                    $items1=WorkTimesheet::whereHas('work',function ($q) use ($company_id){
                        $q->where('company_id',$company_id);
                    })->whereBetween('startDate', [$from_date, $to_date])->get();
                    $items2=WorkTimesheet::whereHas('ticket',function ($q) use ($company_id){
                        $q->where('user__id',$company_id);
                    })->whereBetween('startDate', [$from_date, $to_date])->get();
                    $items=$items1->merge($items2);

                    $items_fani=Ticket::where('user__id',$company_id)->whereIN('role__id',[2,9])->whereBetween('created_at', [$from_date, $to_date])->get();

                    $t_title="ساعات کاری شرکت  $user->company__name";
                    $type='company';
                    return Excel::download(new TimeExport($groups,$items,$t_title,$type,$items_fani), 'CompanyExport'.$rand.'.xlsx');
                }else{
                    $groups=WorkTimesheet::where('user_id',$request->user_id)->whereBetween('startDate', [$from_date, $to_date])->with('user')->groupBy('startDate')->get();
                    $items=WorkTimesheet::where('user_id',$request->user_id)->whereBetween('startDate', [$from_date, $to_date])->get();
                }
                $t_title="ساعات کاری $user->name";
                $type='user';
                return Excel::download(new TimeExport($groups,$items,$t_title,$type), 'UserExport'.$rand.'.xlsx');
            }
        }
    }

    public function get_correct_date($date)
    {
        $explode_from=explode(',',$date);
        $exploded_date=to_gregorian($explode_from[0],$explode_from[1],$explode_from[2]);
        $date_y=$exploded_date[0];
        $date_m=$exploded_date[1]<10?'0'.$exploded_date[1]:$exploded_date[1];
        $date_d=$exploded_date[2]<10?'0'.$exploded_date[2]:$exploded_date[2];
        return $date_y.'-'.$date_m.'-'.$date_d;
    }

    public function finalize(Request $request)
    {
        //return \response()->json($request);
        $item=WorkTime::find($request->workTime_id);
//        try{

            if ($request->pure_workhour>0){
                $item->pure_workhour=$request->pure_workhour;
            }else{
                $item->pure_workhour=null;
            }

            if ($request->overtime_workhour>0){
                $item->overtime_workhour=$request->overtime_workhour;
            }else{
                $item->overtime_workhour=null;
            }

            $item->status=(int)$request->status;

            $item->update();
            return back();

//        }catch (\Exception $e){
//            return back();
//        }
    }

    public function income_store(Request $request)
    {
        $user=auth()->user();
        $item=Income::where('user_id',$user->id)->whereDate('created_at', Carbon::today())->first();
        if ($item){
            $item[$request->type]=$request->income;
            $item->update();
        }else{
            $item=new Income();
            $item->name=$user->name;
            $item->user_id=$user->id;
            $item[$request->type]=$request->income;
            $item->save();
        }
        return true;
    }
    public function workhour_edit(Request $request ,$id)
    {
        $this->validate($request, [
            'start_min' => 'required|min:0|max:59',
            'start_hour' => 'required|min:0|max:23',
            'end_min' => 'required|min:0|max:59',
            'end_hour' => 'required|min:0|max:23',
        ],[
            'start_min.required'=>'دقیقه شروع الزامی می باشد',
            'start_min.min'=>'دقیقه شروع کمتر از 0 نباشد ',
            'start_min.max'=>'دقیقه شروع بیشتر از 59 نباشد ',
            'start_hour.required'=>'ساعت شروع الزامی می باشد',
            'start_hour.min'=>'ساعت شروع کمتر از 0 نباشد ',
            'start_hour.max'=>'ساعت شروع بیشتر از 59 نباشد ',
            'end_min.required'=>'دقیقه پایان الزامی می باشد',
            'end_min.min'=>'دقیقه پایان کمتر از 0 نباشد ',
            'end_min.max'=>'دقیقه پایان بیشتر از 59 نباشد ',
            'end_hour.required'=>'ساعت پایان الزامی می باشد',
            'end_hour.min'=>'ساعت پایان کمتر از 0 نباشد ',
            'end_hour.max'=>'ساعت پایان بیشتر از 59 نباشد ',
        ]);
//        if($request->start_min || $request->start_hour || $request->end_min || $request->end_hour)
//        {
//            return back()->with('status','تمام ورودی ها را به درستی وارد کنید');
//        }
        $start='';
        if(intval($request->start_hour)<10) {$start.='0';$start.=intval($request->start_hour);}
        else{$start.=intval($request->start_hour);}
        $start.=':';
        if(intval($request->start_min)<10){$start.='0';$start.=intval($request->start_min);}
        else{$start.=intval($request->start_min);}
        $start.=':01';
        $end='';
        if(intval($request->end_hour)<10) {$end.='0';$end.=intval($request->end_hour);}
        else{$end.=intval($request->end_hour);}
        $end.=':';
        if(intval($request->end_min)<10){$end.='0';$end.=intval($request->end_min);}
        else{$end.=intval($request->end_min);}
        $end.=':59';
        try{
//            dd($id);
            $item=WorkTimesheet::findOrFail($id);
            $item->startTime=$start;
            $item->endTime=$end;
            $item->update();

            return back()->with('status','باموفقیت ویرایش شد');
        } catch (\Exception $e) {
            abort(500);
        }
    }

    public function incomes(Request $request)
    {
        return Income::all();
    }
}

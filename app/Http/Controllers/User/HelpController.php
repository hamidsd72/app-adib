<?php

namespace App\Http\Controllers\User;
use App\HelpComment;
use App\Http\Controllers\Controller;
use App\Model\Library;
use App\HelpDoneJob;
use App\User;
use App\Model\Help;
use Illuminate\Http\Request;

class HelpController extends Controller {

    public function __construct() { $this->middleware('auth'); }

    public function index($type=null) {
        $helps=Help::orderBy('updated_at', 'desc')->get();
        return view('panel.help.index',compact('helps'),['title' => 'لیست مساعدات']);
    }

    public function edit($id) {
        $item = Help::find($id);
        $customers=User::where('role_id','5')->where('suspended',0)->orderBy('created_at','desc')->get();
        $experts=User::where('role_id','2')->where('suspended',0)->orderBy('created_at','desc')->get();
        return view('panel.help.edit',compact('item','customers','experts'),['title' => 'لیست مساعدات']);
    }

    public function store(Request $request) {
        try {
            $help=new Help();
            $help->description=$request->description;
            $help->price=$request->final_price;
            $help->save();
            return Redirect()->back()->with('flash_message', 'مساعده ثبت و به ریاست ارجاع گردید.');
        } catch (\Exception $exception){
            // dd($exception);
            return Redirect()->back()->with('err_message', 'مشگل در ثبت مساعده , مجددا امتحان کنید');
        }
    }

    public function update(Request $request,$id)
    {
        if (!empty($request->user_mobile)){
            $user=User::where('company__manager_phone',$request->user_mobile)->first();
            if ($user) return Redirect()->back()->with('err_message', 'این شماره در سیستم موجود است');
        }
        $visit=Help::find($id);
        try {
            $user_id=mt_rand(999,9999);
            $visit->title=$request->title;
            $visit->type=$request->type;
            $visit->visit_date=$request->visit_date;
            $visit->user_id=$request->user_id ? $request->user_id : 0;
            $visit->expert_id=$request->expert_id ? $request->expert_id : 0;
            $visit->user_name=$request->user_name;
            $visit->user_mobile=$request->user_mobile;
            $visit->description=$request->description;
            $visit->expert_id=$request->expert_id;
            if (!empty($request->expert_id)){
                $visit->status=1;
            }
            $visit->update();
            return Redirect()->back()->with('flash_message', 'مساعده ویرایش و ارجاع داده شد.');
        }
        catch (\Exception $exception){
            // dd($exception);
            return Redirect()->back()->with('err_message', 'مشگل در ویرایش مساعده , مجددا امتحان کنید');
        }
    }

    public function show($id) {
        $visit=Help::find($id);
        return view('panel.help.show',compact('visit'));
    }

    public function comment_store(Request $request) {
        $this->validate($request, [
            'comment__content' => 'required'
        ]);

        $visit = Help::find($request->visit__id);

        try {

            $comment = new HelpComment();
            $comment->user_id = auth()->user()->id;
            $comment->comment = $request->comment__content;

            $visit->comments()->save($comment);

            if ($request->hasFile('comment__attachment')) {

                foreach ($request->comment__attachment as $file) {
                    $originalName = $file->getClientOriginalName();
                    $destinationPath = 'uploads/libraries/help/';
                    $extension = $file->getClientOriginalExtension();
                    $fileName = 'visit-' . md5(time() . '-' . $originalName) . '.' . $extension;
                    $file->move($destinationPath, $fileName);
                    $f_path = $destinationPath . "" . $fileName;
                    $library = new Library();
                    $library->file__path = $f_path;
                    $comment->libraries()->save($library);
                }

            }
            
            return Redirect()->back()->with('flash_message', 'پاسخ شما ثبت شد.');
        } catch (\Exception $e) {
            // dd($e);
            return Redirect()->back()->with('err_message', 'مشگل در ثبت پاسخ , مجددا امتحان کنید');
        }

    }

    public function done_job_store(Request $request) {
        $visit = Help::findOrFail($request->id);
        try {
            $donJob=new HelpDoneJob();
            $donJob->user_id=auth()->user()->id;
            $donJob->title=$request->title;
            $donJob->description=$request->description;
            $visit->doneJob()->save($donJob);
            return Redirect()->back()->with('flash_message', 'ثبت شد');
        } catch (\Exception $e) {
            // dd($e);
            return Redirect()->back()->with('err_message', 'مشگل در ثبت , مجددا امتحان کنید');
        }
    }


}

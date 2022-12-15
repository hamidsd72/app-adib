<?php

namespace App\Http\Controllers\User;

use App\Model\VisitComment;
use App\Http\Controllers\Controller;
use App\Model\Library;
use App\Model\WorkTimesheet;
use App\Model\TimesheetCircle;
use App\Model\WorkReport;
use App\Model\VisitDoneJob;
use Carbon\Carbon;
use http\Env\Response;
use App\User;
use App\Notifications\Withdrawal;
use App\Model\Work;
use App\Model\Visit;
use App\Model\Ticket;
use App\Model\Phase;
use Illuminate\Http\Request;

class WorkTimesheetController extends Controller {

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

    public function store(Request $request) {
        Work::where('id',$request->type_id)->update(['updated_at'=>date('Y-m-d H:i:s')]);
        $user_id = auth()->user()->id;
        $role_id = auth()->user()->role_id;
        $today=Carbon::now();
        $time=$today->format('H:i:s');
        $date=$today->format('Y-m-d');

        $type=WorkTimesheet::getTypeIfReferred($request->type,$request->type_id);

        if (!$type) return Redirect()->back()->with('flash_message', 'این کار به شما ارجاع داده نشده است');

        // continue work timesheet if its finished
        $finished=WorkTimesheet::where('startDate',$date)
            ->where('user_id',$user_id)
            ->where('type',$request->type)
            ->where('type_id',(int)$request->type_id)
            ->where('status','finished')->first();

        if ($finished){
            // pause if work is running
            $this->pauseIfDoing($request);
            $finished->status="doing";

            // make circle after start
            $tsh=new TimesheetCircle();
            $tsh->timesheet_id=$finished->id;
            $tsh->user_id=$user_id;
            $tsh->paused_at=$finished->endDate.' '.$finished->endTime;
            $tsh->resumed_at=$today;
            $tsh->reason_type=$request->type;
            $tsh->reason_id=$request->type_id;
            $tsh->save();

            $finished->endDate='';
            $finished->endTime='';
            $finished->update();

            return Redirect()->back()->with('flash_message', 'کار ادامه یافت.');
        }

        // continue work timesheet if its paused
        $doing=WorkTimesheet::where('startDate',$date)
            ->where('user_id',$user_id)
            ->where('type',$request->type)
            ->where('type_id',(int)$request->type_id)
            ->where('status','paused')->first();

        if ($doing){
            // set resumed_at
            if (count($doing->circle)){
                $doing->circle->last()->resumed_at=$today;
                $doing->circle->last()->update();
            }
            
            // pause if any work is running
            $this->pauseIfDoing($request);

            $doing->status="doing";
            $doing->endTime=null;
            $doing->update();
            return Redirect()->back()->with('flash_message', 'کار ادامه یافت.');
        }

        $this->pauseIfDoing($request);

        $item=new WorkTimesheet();
        $item->user_id=$user_id;
        $item->type=$request->type;
        $item->type_id=$request->type_id;
        $item->startTime=$time;
        $item->startDate=$date;
        $item->location=$this->findLocation();
        $item->divice=(request()->userAgent())??'';

        $item->status='doing';

        $item->save();
        return Redirect()->back()->with('flash_message', 'کار آغاز شد.');
    }

    public function pauseIfDoing($request) {
        $user_id = auth()->id();
        $role_id = auth()->user()->role_id;
        $today=Carbon::now();
        $time=$today->format('H:i:s');
        $date=$today->format('Y-m-d');

        // pause work timesheet if has it is already started
        $pause=WorkTimesheet::where('startDate',$date)
            ->where('user_id',$user_id)
            ->where('status','doing')->first();
        //return $item;
        if ($pause){
            $pause->status='paused';
            $pause->endTime=$time;
            $pause->endDate=$date;
            $pause->update();
            $tsh=new TimesheetCircle();
            $tsh->timesheet_id=$pause->id;
            $tsh->user_id=$user_id;
            $tsh->paused_at=$today;
            $tsh->resumed_at=$today;
            $tsh->reason_type=$request->type;
            $tsh->reason_id=$request->type_id;
            $tsh->save();
        }

        return true;
    }

    public function pause(Request $request) {

        $this->pauseIfDoing($request);

        return Redirect()->back()->with('flash_message', 'کار مکث شد');

    }

    public function stop(Request $request) {
        //END WORKSHEET IF EXIST
        $today=Carbon::now();
        $time=$today->format('H:i:s');
        $date=$today->format('Y-m-d');
        
        $workTimesheet=WorkTimesheet::WorkTimeSheetByStatus($request->type,$request->type_id,'doing');

        if ($workTimesheet){
            $workTimesheet->status='finished';
            $workTimesheet->endTime=$time;
            $workTimesheet->endDate=$date;
            if ($request->description) {
                WorkReport::create([
                    "timesheet_id" => $workTimesheet->id,
                    "description" => $request->description,
                    "UpdateAntivirusDate" => $request->UpdateAntivirusDate,
                    "DeleteTempFile" => $request->DeleteTempFile,
                    "RunScanDiskOrCheckDisk" => $request->RunScanDiskOrCheckDisk,
                    "RunAcompleteVirussystemScan" => $request->RunAcompleteVirussystemScan,
                    'SureForSavingImportantDataOnServer' => $request->SureForSavingImportantDataOnServer,
                    "CheckCompleteShutdown" => $request->CheckCompleteShutdown,
                    "CheckAndReMoveUnnecessaryStartup" => $request->CheckAndReMoveUnnecessaryStartup,
                    "CheckingForLeaseDuration" => $request->CheckingForLeaseDuration,
                    "UpdatingAntivirus" => $request->UpdatingAntivirus,
                    "RunAndCompleteVirusSystemScan" => $request->RunAndCompleteVirusSystemScan,
                    "PhysicalChecksOfSystemHardware" => $request->PhysicalChecksOfSystemHardware,
                    "ForMonthlyCheckUPS" => $request->ForMonthlyCheckUPS,
                    "ServerName" => $request->ServerName,
                    "BackupType" => $request->BackupType,
                    'BackupStatus' => $request->BackupStatus,
                    "LastBackupDate" => $request->LastBackupDate,
                    "HardRemainingSpace" => $request->HardRemainingSpace,
                    // "report" => $request->report,
                    "seen" => 'notSee',
                ]);
                // send email to custor
                // $user = $workTimesheet->work()->first()->company()->first();
                // $user->notify(new Withdrawal(
                //     $request->description,
                //     $request->UpdateAntivirusDate,
                //     $request->DeleteTempFile,
                //     $request->RunScanDiskOrCheckDisk,
                //     $request->RunAcompleteVirussystemScan,
                //     $request->SureForSavingImportantDataOnServer,
                //     $request->CheckCompleteShutdown,
                //     $request->CheckAndReMoveUnnecessaryStartup,
                //     $request->CheckingForLeaseDuration,
                //     $request->UpdatingAntivirus,
                //     $request->RunAndCompleteVirusSystemScan,
                //     $request->PhysicalChecksOfSystemHardware,
                //     $request->ForMonthlyCheckUPS,
                //     $request->ServerName,
                //     $request->BackupType,
                //     $request->BackupStatus,
                //     $request->LastBackupDate,
                //     $request->HardRemainingSpace
                // ));

            }
            $workTimesheet->end_location=$this->findLocation();
            $workTimesheet->end_divice=(request()->userAgent())??'';
            $workTimesheet->update();
        }

        return back()->with('flash_message','با موفقیت پایان یافت');
    }

}

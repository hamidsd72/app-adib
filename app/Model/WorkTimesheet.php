<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WorkTimesheet extends Model {

    protected $table = 'adib_it_work_timesheets';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function circle() {
        return $this->hasMany('App\Model\TimesheetCircle', 'timesheet_id');
    }
    
    public function circleTime() {
        return $this->hasMany('App\Model\TimesheetCircle', 'timesheet_id')->get(['paused_at','resumed_at']);
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function ($check) {
            $check->comments()->delete();
        });
    }

    public function ticket() {
        return $this->belongsTo('App\Model\Ticket', 'type_id');
    }

    public function phase() {
        return $this->belongsTo('App\Model\Phase', 'type_id');
    }

    public function work() {
        return $this->belongsTo('App\Model\Work', 'type_id');
    }

    public function job() {
        return $this->belongsTo('App\Model\Job', 'type_id');
    }

    public function reports() {
        return $this->hasMany('App\Model\WorkReport', 'timesheet_id');
    }

    public static function getTypeIfReferred($type, $type_id) {
        $user_id = auth()->user()->id;
        switch ($type) {
            case 'ticket':
                $ticket = Ticket::find($type_id);
                return $ticket->referred_to != $user_id ? null : $ticket;
                break;
            case 'phase':
                $phase = Phase::find($type_id);
                if (!$phase) {
                    return null;
                }
                return $phase->user__id != $user_id ? null : $phase;
                break;
            case 'work':
                $work = Work::find($type_id);
                if (!$work) {
                    return null;
                }
                return $work->user_id != $user_id ? null : $work;
                break;
            case 'job':
                $job = Job::find($type_id);
                if (!$job) {
                    return null;
                }
                return $job->referred_to != $user_id ? null : $job;
                break;
            default:
                return null;
                break;
        }
    }

    public static function getType($type, $type_id) {
        switch ($type) {
            case 'ticket':
                $ticket = Ticket::find($type_id);
                return $ticket;
                break;
            case 'phase':
                $phase = Phase::find($type_id);
                if (!$phase) {
                    return null;
                }
                return $phase;
                break;
            case 'work':
                $work = Work::find($type_id);
                if (!$work) {
                    return null;
                }
                return $work;
                break;
            case 'job':
                $job = Job::find($type_id);
                if (!$job) {
                    return null;
                }
                return $job;
                break;
            default:
                return null;
                break;
        }
    }

    public static function getTypeColumns($type, $type_id) {
        switch ($type) {
            case 'ticket':
                $ticket = Ticket::find($type_id);
                return [
                    'type' => 'تیکت',
                    'title' => $ticket->ticket__title,
                ];
                break;
            case 'phase':
                $phase = Phase::find($type_id);
                if (!$phase) {
                    return [
                        'type' => '',
                        'title' => '',
                    ];
                }
                return [
                    'type' => 'فاز',
                    'title' => $phase->phase__name,
                ];
                break;
            case 'work':
                $work = Work::find($type_id);
                if (!$work) {
                    return [
                        'type' => '',
                        'title' => '',
                    ];
                }
                return [
                    'type' => "کار ($work->type)",
                    'title' => $work->title,
                ];
            case 'job':
                $job = Job::find($type_id);
                if (!$job) {
                    return [
                        'type' => '',
                        'title' => '',
                    ];
                }
                return [
                    'type' => "جاب",
                    'title' => $job->title,
                ];
                break;
            default:
                return null;
                break;
        }
    }

    public static function WorkTimeSheetByStatus($type, $type_id, $status) {
        $date = Carbon::now()->format('Y-m-d');
        return WorkTimesheet::where('startDate', $date)
            ->where('type_id', $type_id)
            ->where('type', $type)
            ->where('user_id', auth()->user()->id)
            ->where('status', $status)->first();
    }

    public static function types() {
        return [
            'ticket',
            'phase',
            'work',
            'job',
            'outwork',
        ];
    }

    public static function getRoute($type, $type_id) {
        switch ($type) {
            case 'ticket':
                return url("ticket", $type_id);
                break;
            case 'phase':
                return url('user_phase_show', $type_id);
                break;
            case 'work':
                return url('works', $type_id);
                break;
            case 'job':
                return url("job", $type_id);
                break;
            default:
                return null;
                break;
        }
    }

    public function getPassedSeconds() {
        $item=$this;
        $today=Carbon::now();
        $time=$today->format('H:i:s');
        $startDate = $item->startDate;
        $endTime = $item->endTime ? $item->endTime : $time;
        $st = Carbon::parse($item->startDate . ' ' . $item->startTime);
        $et = Carbon::parse($startDate . ' ' . $endTime);
        $seconds = 0;
        if (count($item->circle)){
                foreach ($item->circle as $index => $row) {
                    // if its first circle
                    $paused_at = Carbon::parse($row->paused_at);
                    if (count($item->circle) == 1) {
                        $seconds += $st->diffInSeconds($paused_at);
                        $resumed_at = Carbon::parse($row->resumed_at);
                        $seconds += $et->diffInSeconds($resumed_at);
                    } else {

                        if ($index == 0) {
                            $seconds += $paused_at->diffInSeconds($st);
                        } else {
                            if ($index == count($item->circle) - 1) {
                                $resumed_at = Carbon::parse($item->circle[$index - 1]->resumed_at);
                                $seconds += $paused_at->diffInSeconds($resumed_at);
                                $resumed_at = Carbon::parse($row->resumed_at);
                                $seconds += $et->diffInSeconds($resumed_at);
                            } else {
                                $resumed_at = Carbon::parse($item->circle[$index - 1]->resumed_at);
                                $seconds += $paused_at->diffInSeconds($resumed_at);
                                /* dd($item->circle[$index-1]->resumed_at.'-'.$paused_at);*/
                            }
                        }


                    }

                }

        }
        else{
            $seconds += $et->diffInSeconds($st);
        }

        return $seconds;

    }

    public function pausedMinutes($item) {
        $circle = $item->circleTime();
        $circleTime = 0;
        foreach ($circle as $c) {
            $circleTime += Carbon::parse($c->paused_at)->diffInSeconds( Carbon::parse($c->resumed_at) , false);
        }
        return intval($circleTime/60);
    }

    public static function getPassedMinutes($item) {
        $today=Carbon::now();
        $time=$today->format('H:i:s');
        $startDate = $item->startDate;
        $endTime = $item->endTime ? $item->endTime : $item->endTime;
        $st = Carbon::parse($item->startDate . ' ' . $item->startTime);
        if($item->status=='doing') $et = Carbon::parse($startDate . ' ' . date('H:i'));
        else  $et = Carbon::parse($startDate . ' ' . $endTime);
        $minutes = 0;
        if (count($item->circle)){
            foreach ($item->circle as $index => $row) {
                // if its first circle
                $paused_at = Carbon::parse($row->paused_at);
                $resumed_at = Carbon::parse($row->resumed_at);
                $minutes += $resumed_at->diffInMinutes($paused_at);

                //                if (count($item->circle) == 1) {
                // //                    $minutes += $st->diffInMinutes($paused_at);
                //                    $resumed_at = Carbon::parse($row->resumed_at);
                //                    $minutes += $resumed_at->diffInMinutes($paused_at);
                // //                    $minutes
                //                } else {
                
                //                    if ($index == 0) {
                //                        $minutes += $paused_at->diffInMinutes($st);
                //                    } else {
                //                        if ($index == count($item->circle) - 1) {
                //                            $resumed_at = Carbon::parse($item->circle[$index - 1]->resumed_at);
                //                            $minutes += $paused_at->diffInMinutes($resumed_at);
                //                            $resumed_at = Carbon::parse($row->resumed_at);
                //                            $minutes += $et->diffInMinutes($resumed_at);
                //                        } else {
                //                            $resumed_at = Carbon::parse($item->circle[$index - 1]->resumed_at);
                //                            $minutes += $paused_at->diffInMinutes($resumed_at);
                //                            /* dd($item->circle[$index-1]->resumed_at.'-'.$paused_at);*/
                //                        }
                //                    }
                
                
                //                }

            }
        }
        $minutes=$et->diffInMinutes($st)-$minutes;

        return $minutes;

    }

}

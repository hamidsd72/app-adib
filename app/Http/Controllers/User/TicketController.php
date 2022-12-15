<?php

namespace App\Http\Controllers\User;

use App\Model\Comment;
use App\Http\Controllers\Controller;
use App\Model\Library;
use App\Model\WorkTimesheet;
use App\Model\Ticket;
use App\Model\Hour;
use App\User;
use App\Model\Role;
use Mail;
use Carbon\Carbon;
use App\Model\Setting;
use Illuminate\Http\Request;

class TicketController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function ticketStatus($ticket__status) {
        switch ($ticket__status) {
            case 'pending':
                return 'در انتظار پاسخ';
                break;
            case 'answered':
                return 'پاسخ داده شده';
                break;
            case 'waiting_answered':
                return 'پاسخ داده شده - در انتظار پاسخ';
                break;
            case 'closed':
                return 'بسته شده';
                break;
            case 'doing':
                return 'در حال پیگیری';
                break;
            case 'finished':
                return 'به پایان رسیده';
                break;
            case 'unpaid':
                return 'پرداخت نشده';
                break;
            case 'paid':
                return 'پرداخت شده';
                break;
            default:
                return $ticket__status;
                break;
        }
    }
        
    public function ticketPriority($ticket__priority) {
        switch ($ticket__priority) {
            case 'high':
                return 'ز';
                break;
            case 'normal':
                return 'م';
                break;
            case 'low':
                return 'ک';
                break;
            default:
                return $ticket__priority;
                break;
        }
    }

    public function customer_show() {
        $roles = Role::whereNotIn('id', [1, 5, 8])->get();
        $users = User::where('role_id', 5)->get();
        return view('user.ticket.ticket.customer.create', compact('roles','users'), ['title' => 'ثبت تیکت']);
    }

    public function customer_send(Request $request) {
        $this->validate($request, [
            'role__id' => 'required',
            'user__id' => 'required',
            'ticket__priority' => 'required',
            'ticket__title' => 'required',
            'ticket__content' => 'required',
        ]);
        $user = auth()->user();
        $user_id = $user->id;
        $data = Ticket::where('user__id', $user_id)->get();
        $ticket = Ticket::create([
            'user__id' => $request->user__id,
            'creator_id' => $user_id,
            'role__id' => $request->role__id,
            'ticket__status' => 'pending',
            'ticket__title' => $request->ticket__title,
            'ticket__priority' => $request->ticket__priority,
            'ticket__content' => $request->ticket__content,
        ]);

        $type='';
        switch ($request->role__id) {
            case '2':
                $type = 'بخش شبکه و سخت افزار';
                break;
            case '3':
                $type = 'بخش مالی';
                break;
            case '4':
                $type = 'بخش سایت و سئو';
                break;
            case '6':
                $type = 'بخش فروش';
                break;
            case '9':
                $type = 'مدیریت فنی';
                break;
        }

        if ($request->hasFile('comment__attachment')) {

            try {
                foreach ($request->comment__attachment as $image) {

                    $file = $image;
                    $originalName = $file->getClientOriginalName();
                    $destinationPath = 'uploads/libraries/tickets/';
                    $extension = $file->getClientOriginalExtension();
                    $fileName = 'tikcet-' . md5(time() . '-' . $originalName) . '.' . $extension;
                    $file->move($destinationPath, $fileName);
                    $f_path = $destinationPath . "" . $fileName;
                    $library = new Library();
                    $library->file__path = $f_path;
                    $ticket->libraries()->save($library);

                }
            } catch (\Exception $e) {
                abort(500);
            }
        }

        return redirect()->route('user.ticket.index')->with('flash_message', 'تیکت با موفقیت ثبت شد');
    }
    
    public function ticket_search(Request $request) {
        $role_id = auth()->user()->role_id;

        if ($role_id == 1 || $role_id == 8) {
            if ($request->number) {
                $data = Ticket::where([
                    'id' => $request->number,
                    'ticket__type' => 'services'
                ])->orderBy('id', 'DESC')->get();
            } else {
                $data = Ticket::where([
                    'user__id' => $request->name,
                    'ticket__type' => 'services'
                ])->orderBy('id', 'DESC')->get();
            }
        } elseif ($role_id == 9 || $role_id == 2) {
            if ($request->number) {
                $data = Ticket::where([
                    'id' => $request->number,
                    'ticket__type' => 'services'
                ])->whereIN('role__id',[2,9])->orderBy('id', 'DESC')->get();
            } else {
                $data = Ticket::where([
                    'user__id' => $request->name,
                    'ticket__type' => 'services'
                ])->whereIN('role__id',[2,9])->orderBy('id', 'DESC')->get();
            }
        }
        else {
            if ($request->number) {
                $data = Ticket::where([
                    'id' => $request->number,
                    'role__id' => $role_id,
                    'ticket__type' => 'services'
                ])->orderBy('id', 'DESC')->get();
            } else {
                $data = Ticket::where([
                    'user__id' => $request->name,
                    'role__id' => $role_id,
                    'ticket__type' => 'services'
                ])->orderBy('id', 'DESC')->get();
            }
        }

        $companies = User::where('role_id', 5)->orderBy('id')->get();

        return view('user.ticket.ticket.home', compact('companies', 'data'), [
            'title' => 'لیست تیکت ها',
            'invoices' => 'yes'
        ]);
    }

    public function auto_closed() {
        
        $companies = User::where('role_id', 5)->orderBy('id')->get();
        $datas = Ticket::where('auto_closed' ,1)->orderBy('updated_at', 'DESC')->with([
            'comments',
            'user'
        ])->paginate(1000);

        return view('user.ticket.ticket.auto', compact('companies', 'datas'), [
            'title' => 'لیست تیکت های باز',
            'invoices' => 'yes'
        ]);
    }

    public function createHour($date, $datum) {
        // if ($date <= -2) {
        //     Hour::create([
        //         'ticket_id' => $datum->id,
        //         'company_id' => $datum->user__id,
        //         'user_id' => $datum->referred_to ? $datum->referred_to : 111,
        //         'hour' => 60
        //     ]);
        //     $datum->update(['ticket__status' => 'finished', 'auto_closed' => 1]);
        // }
    }

    public function fetchTickets() {

        $user = auth()->user();
        $role_id = $user->role_id;
        $data = Ticket::where([
            'ticket__type' => 'services',
            'ticket__status' => 'pending'
        ])->orWhere([
            'ticket__type' => 'services',
            'ticket__status' => 'doing'
        ])->orWhere([
            'ticket__type' => 'services',
            'ticket__status' => 'answered'
        ])->orderBy('updated_at', 'DESC')->with('comments')->get();

        foreach ($data as $datum) {
            // IF Customer Posted Ticket
            if (count($datum->comments)) {
                $last = $datum->comments->last();
                if (!is_null($last)) {
                    if ($last->user__id != $datum->user__id) {
                        $date = dateDiffDomain(date('Y-m-d 00:00:00'), $last->created_at);
                        $this->createHour($date, $datum);
                    }
                }
            }
            else
             {
                if (!is_null($datum->creator)) {
                    if ($datum->creator->role_id != 5) {
                        $date = dateDiffDomain(date('Y-m-d 00:00:00'), $datum->created_at);
                        $this->createHour($date, $datum);
                    }
                }
            }
            // IF Adib Posted Ticket
            if ($datum->send__id == 1) {
                if (count($datum->comments)) {
                    $last = $datum->comments->last();
                    $date = dateDiffDomain(date('Y-m-d 00:00:00'), $last->created_at);
                    if (!is_null($last)) {
                        if ($last->user__id != $datum->user__id) {
                            $this->createHour($date, $datum);
                        }
                    }
                } else {
                    $date = dateDiffDomain(date('Y-m-d 00:00:00'), $datum->created_at);
                    $this->createHour($date, $datum);
                }
            }
        }


        return $data = Ticket::where(function ($query) {
         $query->where('ticket__status', 'pending')
             ->orWhere('ticket__status' , 'doing')
             ->orWhere('ticket__status' , 'answered')
             ->orWhere('ticket__status' , 'waiting_answered');
        })->where('ticket__type','services')->orderBy('updated_at', 'DESC')->get();
    }

    public function index() {

        $data = Ticket::whereIN('ticket__status',['pending','doing','waiting_answered','answered_doning'])->orderByDesc('updated_at')->with([
            'comments',
            'user'
        ])->get();

        foreach ($data as $d) {
            $d->ticket__status   = $this->ticketStatus($d->ticket__status);
            $d->ticket__priority = $this->ticketPriority($d->ticket__priority);
        }

        $companies = User::where('role_id', 5)->orderBy('id')->get();
        return view('user.ticket.ticket.home', compact('companies', 'data'), [
            'title' => 'لیست تیکت های باز',
            'invoices' => 'yes'
        ]);
    }

    public function answered() {

        $data = Ticket::whereIN('ticket__status',['answered']);

        if(auth()->user()->role_id==1 || auth()->user()->role_id==8) {
            $data=$data->where('ticket__type', 'services');
        }
        elseif(auth()->user()->role_id==9 || auth()->user()->role_id==2) {
            $data=$data->where('ticket__type', 'services')->whereIN('role__id',[2,9]);
        }
        elseif(auth()->user()->role_id==3 || auth()->user()->role_id==6 || auth()->user()->role_id==7) {
            $data=$data->where('ticket__type', 'services')->where('role__id',auth()->user()->role_id);
        }
        else {
            $data=$data->where('ticket__type', 'services')->where('referred_to',auth()->user()->id);
        }

        $data=$data->orderBy('updated_at', 'DESC')->with([
            'comments',
            'user'
        ]);
        
        $data=$data->get();
        $companies = User::where('role_id', 5)->orderBy('id')->get();

        return view('user.ticket.ticket.answered', compact('companies', 'data'), [
            'title' => 'لیست تیکت های پاسخ داده شده',
            'invoices' => 'yes'
        ]);
    }

    function aasort(&$array, $key) {
        $sorter = array();
        $ret = array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii] = $va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }
        $array = $ret;
    }

    function sortByOrder($a, $b) {
        return $a['hour'] - $b['hour'];
    }

    function isTrust($name) {
        if (!is_null($name) && $name != '' && $name != '---' && $name != '--' && $name != '-' && $name != ' ') {
            return true;
        }
        return false;
    }

    public function index2() {
        $user = auth()->user();
        $role_id = $user->role_id;
        if ($role_id == 1) {
            $items = Ticket::where([
                'ticket__type' => 'services',
                'ticket__status' => 'closed'
            ])->orderBy('updated_at', 'DESC')->paginate(20);
        } elseif ($role_id == 9 || $role_id==2) {
            $items = Ticket::where([
                'ticket__type' => 'services',
                'ticket__status' => 'closed'
            ])->whereIN('role__id',[9,2])->orderBy('updated_at', 'DESC')->paginate(20);
        } elseif ($role_id == 7) {
            $items = Ticket::where([
                'role__id' => 4,
                'ticket__type' => 'services',
                'ticket__status' => 'closed'
            ])->orderBy('updated_at', 'DESC')->paginate(20);
        } else {
            $items = Ticket::where([
                'role__id' => $role_id,
                'ticket__type' => 'services',
                'ticket__status' => 'closed'
            ])->orderBy('updated_at', 'DESC')->paginate(20);
        }

        return view('user.ticket.ticket.index', compact('items'), [
            'title' => 'لیست تیکت های بسته شده',
            'invoices' => 'yes'
        ]);
    }

    public function invoices() {
        $user = auth()->user();
        $role_id = $user->role_id;
        $companies = User::where('role_id', 5)->orderBy('id')->get();
        $data = Ticket::where(['role__id' => $role_id, 'ticket__type' => 'invoices'])->get();

        return view('user.ticket.ticket.home', compact('data', 'companies'), ['title' => 'لیست فاکتور ها']);
    }

    public function create() {
        $users = User::where('role_id', 5)->get();
        return view('user.ticket.ticket.create', compact('users'), ['title' => 'ثبت تیکت']);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'role__id' => 'required',
            'user__id' => 'required',
            'seen__id' => 'required',
            'ticket__status' => 'required',
            'ticket__priority' => 'required',
            'ticket__type' => 'required',
            'ticket__title' => 'required',
        ]);

        $ticket_id = null;
        if ($request->ticket__type == 'invoices') {
            $ticket_type = "unpaid";
            $role__id = 3;
        } else {
            $ticket_type = "answered";
            $role__id = $request->role__id;
        }

        if (isset($request->ticket__content)) {
            $t_content = $request->ticket__content;
        } else {
            $t_content = "بدون توضیحات";
        }

        try {

            $ticket = Ticket::create([
                'user__id' => $request->user__id,
                'role__id' => $role__id,
                'seen__id' => $request->seen__id,
                'creator_id' => auth()->user()->id,
                'send__id' => 1,
                'ticket__type' => $request->ticket__type,
                'ticket__priority' => $request->ticket__priority,
                'ticket__status' => $ticket_type,
                'ticket__title' => $request->ticket__title,
                'ticket__content' => $t_content
            ]);

            $ticket_id = $ticket->id;
            if ($request->hasFile('comment__attachment')) {

                try {
                    foreach ($request->comment__attachment as $image) {
                        $file = $image;
                        $originalName = $file->getClientOriginalName();
                        $destinationPath = 'uploads/libraries/tickets/';
                        $extension = $file->getClientOriginalExtension();
                        $fileName = 'tikcet-' . md5(time() . '-' . $originalName) . '.' . $extension;
                        $file->move($destinationPath, $fileName);
                        $f_path = $destinationPath . "" . $fileName;
                        $library = new Library();
                        $library->file__path = $f_path;
                        $ticket->libraries()->save($library);

                    }
                } catch (\Exception $e) {
                    abort(500);
                }
            }

            $user = auth()->user();
            $role_id = $user->role_id;
            if ($role_id == 1) {
                $data = Ticket::where(['ticket__type' => 'services', 'ticket__status' => 'pending'])
                    ->orderBy('id', 'DESC')->get();
                $data2 = Ticket::where(['ticket__type' => 'services', 'ticket__status' => 'doing'])
                    ->orderBy('id', 'DESC')->get();
                $data3 = Ticket::where(['ticket__type' => 'services', 'ticket__status' => 'answered'])
                    ->orderBy('id', 'DESC')->get();
                $data4 = Ticket::where(['ticket__type' => 'services', 'ticket__status' => 'finished'])
                    ->orderBy('id', 'DESC')->get();
            } else {
                $data = Ticket::where([
                    'role__id' => $role_id,
                    'ticket__type' => 'services',
                    'ticket__status' => 'pending'
                ])
                    ->orderBy('id', 'DESC')->get();
                $data2 = Ticket::where([
                    'role__id' => $role_id,
                    'ticket__type' => 'services',
                    'ticket__status' => 'doing'
                ])
                    ->orderBy('id', 'DESC')->get();
                $data3 = Ticket::where([
                    'role__id' => $role_id,
                    'ticket__type' => 'services',
                    'ticket__status' => 'answered'
                ])
                    ->orderBy('id', 'DESC')->get();
                $data4 = Ticket::where([
                    'role__id' => $role_id,
                    'ticket__type' => 'services',
                    'ticket__status' => 'finished'
                ])
                    ->orderBy('id', 'DESC')->get();
            }

            $customer_user = User::where('id', $request->user__id)->first();

            if ($customer_user->company__telegram != null) {

                require_once('jdf.php');
                $jalali_date = jdate("Y/m/j (H:i)");

                $message = $customer_user->name . " عزیز \n";
                $message .= "تیکت جدید برای شما ثبت گردید لطفا بررسی فرمایید. \n";
                $message .= "نام تیکت : " . $request->ticket__title . "\n";
                $message .= "تاریخ ثبت تیکت : " . $jalali_date;

            }

            if (!is_null($ticket_id)) {
            //    $this->sendTelegram($ticket_id);
            }


            if ($customer_user->email != null) {
                try {
                    $mail = Mail::send('emails.email', ['ticket' => $ticket, 'customer_user' => $user], function ($m) use ($ticket, $customer_user) {
                    //    $customer_user->email
                        $m->to('golshahimohammadreza@gmail.com')->subject($ticket->ticket__title);
                    });

                } catch (\Exception $e) {
                //    dd($e);
                }
            }

            return redirect()->route('user.ticket.index');

        } catch (\Exception $e) {
            abort(500);
        }
    }

    public function comment_store(Request $request) {
        $this->validate($request, [
            'comment__content' => 'required'
        ]);

        $ticket = Ticket::where('id', $request->ticket__id)->first();
        $user = auth()->user();
        $user_id = $user->id;
        $role_id = $user->role_id;

        $today = Carbon::now();
        $date = $today->format('Y-m-d');

        $workTimesheet_doing = WorkTimesheet::WorkTimeSheetByStatus('ticket', $ticket->id, 'doing');

        if ($role_id == 4 && !$workTimesheet_doing) {
            return Redirect()->back()->with('err_message', 'ابتدا کار را شروع نمایید تا ساعت کار مربوطه به آن ثبت شود');
        }

        // ثبت و جمع ساعت کاری
        $hour = Hour::where('ticket_id', $ticket->id)->first();
        if (!$hour) {
            Hour::create([
                'ticket_id' => $ticket->id,
                'company_id' => $ticket->user ? $ticket->user->id : 0,
                'user_id' => $user_id,
                'hour' => $request->hour
            ]);
        } else {
            $hour->hour = (int)$request->hour;
            $hour->update();
        }

        //    try {

        $comment = new Comment();

        $comment->user__id = $user_id;
        $comment->comment__content = $request->comment__content;
        if ($role_id == 4) {
            $ticket->referred_to = 111;
            $ticket->touch();
            $ticket->update();
            $comment->confirmation = 0;
        } else {
            $ticket->update($request->only('ticket__status'));
        }

        $ticket->comments()->save($comment);

        if ($request->hasFile('comment__attachment')) {

            //    try {
            foreach ($request->comment__attachment as $image) {

                $file = $image;
                $originalName = $file->getClientOriginalName();
                $destinationPath = 'uploads/libraries/tickets/';
                $extension = $file->getClientOriginalExtension();
                $fileName = 'tikcet-' . md5(time() . '-' . $originalName) . '.' . $extension;
                $file->move($destinationPath, $fileName);
                $f_path = $destinationPath . "" . $fileName;
                $library = new Library();
                $library->file__path = $f_path;
                $comment->libraries()->save($library);

            }
            //    } catch (\Exception $e) {
            //        abort(500);
            //    }
        }
        $date = $ticket->updated_at;
        $timestamp = (strtotime($date));
        require_once('jdf.php');
        $jalali_date = jdate("Y/m/j (H:i)", $timestamp);

        $customer_user = User::where('id', $ticket->user__id)->first();

        if ($customer_user->company__telegram != "") {

            $message = "به تیکت شما پاسخ داده شد \n";
            $message .= "شماره تیکت : " . $ticket->id . "\n";
            $message .= "نام تیکت : " . $ticket->ticket__title . "\n";
            $message .= "آخرین بروزرسانی : " . $jalali_date;

            //telegram_notify($customer_user->id, $message);

        }
        $this->tgCommnet($comment->id);


        $type='';
        switch ($request->role__id) {
            case '2':
                $type = 'بخش شبکه و سخت افزار';
                break;
            case '3':
                $type = 'بخش مالی';
                break;
            case '4':
                $type = 'بخش سایت و سئو';
                break;
            case '6':
                $type = 'بخش فروش';
                break;
            case '9':
                $type = 'مدیریت فنی';
                break;
        }
        $msg= '<h3 style="text-align: right;font-weight: bold;margin-top: 0px">کاربر گرامی </h3>';
        $msg.='<p>درخواست شما پاسخ داده شد.</p>';
        $msg.='<p>عنوان درخواست: '.$ticket->ticket__title.'</p>';
        $msg.='<p>وضعیت درخواست: پاسخ داده شد</p>';
        $msg.='<p>شناسه درخواست : '.$ticket->id.'</p>';
        $msg.='<hr style="text-align: center;border:1px dashed #848080;margin: 50px ">';
        $msg.='<p style="text-align: center">(پاسخ های ارسالی از طریق ایمیل قابل بررسی نیستند)</p>';
        $m=send_mail($customer_user->email,$ticket->ticket__title,$msg);
        if ($request->role__id == 9 || $request->role__id == 2) {
            $message = "تیکت:
            $ticket->id
            $user->company__name";

            // Sms::SendSms($message,'09125474829');
        }
        //    Mail::send('emails.email', ['ticket' => $ticket, 'customer_user'=> $customer_user], function ($m) use ($ticket, $customer_user) {
        //        $m->from('CRM@adibit.ir', 'به تیکت شماره '.$ticket->id.' پاسخ داده شده است');

        //        $m->to($customer_user->email, $customer_user->name)->subject($ticket->ticket__title);
        //    });

        //END WORKSHEET IF EXIST
        $today = Carbon::now();
        $time = $today->format('H:i:s');
        $date = $today->format('Y-m-d');

        $workTimesheet = WorkTimesheet::WorkTimeSheetByStatus('ticket', $ticket->id, 'doing');

        if ($workTimesheet) {
            $workTimesheet->status = 'finished';
            $workTimesheet->endTime = $time;
            $workTimesheet->endDate = $date;
            $workTimesheet->update();
        }

        return Redirect()->back()->with('status', 'پاسخ شما ثبت شد.');

        //    } catch (\Exception $e) {
        //        abort(500);
        //    }

    }

    public function show(Ticket $ticket) {
        $setting = Setting::first(['title','icon_site']);
        $merged_users1500 = User::whereIN('role_id', [1,2,3,4,6,7,9])->orderBy('role_id')->get();
        //    $hard_users = User::where('role_id', 2)->get();
        $specialUser = User::find(auth()->id());
        //    $merged_users1500 = collect($soft_users->merge($hard_users));
        $merged_users1500 = $merged_users1500->push($specialUser);

        $ticket->seen__id = 1;
        $ticket->save();
        $ticket->ticket__status   = $this->ticketStatus($ticket->ticket__status);
        $ticket->ticket__priority = $this->ticketPriority($ticket->ticket__priority);
        $data = $ticket;
        $roles=Role::whereIn('id',[1,2,3,4,6,7,9])->where('id','!=',$ticket->role__id)->get();
        return view('user.ticket.ticket.show', compact('data', 'merged_users1500','roles','setting'));
    }

    public function close($id) {
        $ticket = Ticket::where('id', $id)->first();
        $ticket->update(['ticket__status' => 'closed']);
        return redirect()->route('user.ticket.index');
    }

    public function doing($id) {
        $ticket = Ticket::where('id', $id)->first();
        $ticket->update(['ticket__status' => 'doing']);
        return redirect()->route('user.ticket.index');
    }

    public function finished(Request $request) {
        try {
            $hour = Hour::where('ticket_id', $request->ticket_id)->first();
            if (!$hour) {
                Hour::create([
                    'ticket_id' => $request->ticket_id,
                    'company_id' => $request->company_id,
                    'user_id' => $request->user_id,
                    'hour' => $request->hour
                ]);
            } else {
                $hour->hour = (int)$request->hour;
                $hour->update();
            }
            $ticket = Ticket::where('id', $request->ticket_id)->first();
            $ticket->update(['ticket__status' => 'finished']);

            return redirect()->route('user.ticket.index');
        } catch (\Exception $e) {
            abort(500);
        }

    }

    public function confirm($id) {
        $ticket = Ticket::where('id', $id)->first();
        $ticket->update(['ticket__status' => 'paid']);
        return redirect()->route('user.invoices');
    }

    public function has_ticket($id) {
        $ticket = Ticket::where(['role__id' => $id, 'seen__id' => 0, 'ticket__type' => 'services'])->count();
        if ($ticket == 0) {
            $msg = "nok";
        } else {
            $msg = "ok";
        }

        return response($msg);
    }

    public function reference(Request $request, $id) {
        $user = User::find($request->user__id);
        $ticket = Ticket::where('id', $id)->first();
        $ticket->referred_to = $request->user__id;
        $ticket->update();
        //    $ticket->update(['role__id' => $request->role__id, 'send__id' => 0]);

        //    if ($request->role__id == 2) {

        //        $message = "کارشناس گرامی، تیکت جدید برای بخش شما ارجاع گردید لطفا بررسی فرمایید. \n";
        //        $message .= "شماره تیکت : " . $ticket->id . "\n";
        //        $message .= "نام تیکت : " . $ticket->ticket__title . "\n";

        //        telegram_notify(115, $message);
        //        telegram_notify(153, $message);
        //    }

        //    if ($request->role__id == 4) {

        //        $message = "کارشناس گرامی، تیکت جدید برای بخش شما ارجاع گردید لطفا بررسی فرمایید. \n";
        //        $message .= "شماره تیکت : " . $ticket->id . "\n";
        //        $message .= "نام تیکت : " . $ticket->ticket__title . "\n";

        //        telegram_notify(230, $message);
        //    }

        return redirect()->route('user.ticket.index')->with('flash_message', "تیکت ارجاع داده شد به " . $user->name . " ");
    }

    public function reference_move(Request $request, $id) {
        $role = Role::findOrFail($request->role__id);
        $ticket = Ticket::where('id', $id)->first();
        $ticket->role__id = $request->role__id;
        $ticket->update();
        return redirect('user/ticket/'.$id)->with('status', "تیکت انتقال داده شد به بخش  " . $role->description . " ");
    }

    public function comment_confirm($id = null) {
        $comment = Comment::find($id);
        $type='';

        //    dd($comment->ticket->user->email);
        if ($comment) {
            $comment->ticket->ticket__status = 'answered';
            $comment->ticket->update();
            $comment->confirmation = 1;
            $comment->touch();
            $comment->update();

            switch ($comment->ticket->role__id) {
                case '2':
                    $type = 'بخش شبکه و سخت افزار';
                    break;
                case '3':
                    $type = 'بخش مالی';
                    break;
                case '4':
                    $type = 'بخش سایت و سئو';
                    break;
                case '6':
                    $type = 'بخش فروش';
                    break;
                case '9':
                    $type = 'مدیریت فنی';
                    break;
            }
            $msg= '<h3 style="text-align: right;font-weight: bold;margin-top: 0px">کاربر گرامی </h3>';
            $msg.=$comment->comment__content;
            $msg.='<hr style="text-align: center;border:1px dashed #848080;margin: 50px ">';
            $msg.='<p>شناسه درخواست : '.$comment->ticket->id.'</p>';
            $msg.='<p>عنوان درخواست: '.$comment->ticket->ticket__title.'</p>';
            $msg.='<p>وضعیت درخواست: پاسخ داده شده</p>';
            $msg.='<p>واحد رسیدگی کننده:'.$type.'</p>';
            $msg.='<hr style="text-align: center;border:1px dashed #848080;margin: 50px ">';
            $msg.='<p style="text-align: center">(پاسخ های ارسالی از طریق ایمیل قابل بررسی نیستند)</p>';
            send_mail($comment->ticket->user->email,$comment->ticket->ticket__title,$msg);

            return 'true';
        } else {
            return 'false';
        }
    }
    public function p_comment_confirm(Request $request,$id = null) {
        $comment = Comment::find($id);
        $type='';

        //    dd($request->all(),$comment->ticket);
        //    dd($comment->ticket->user->email);
        if ($comment) {
            $comment->ticket->ticket__status = $request->status;
            $comment->ticket->update();
            $comment->confirmation = 1;
            $comment->touch();
            $comment->update();

            switch ($comment->ticket->role__id) {
                case '2':
                    $type = 'بخش شبکه و سخت افزار';
                    break;
                case '3':
                    $type = 'بخش مالی';
                    break;
                case '4':
                    $type = 'بخش سایت و سئو';
                    break;
                case '6':
                    $type = 'بخش فروش';
                    break;
                case '9':
                    $type = 'مدیریت فنی';
                    break;
            }
            $msg= '<h3 style="text-align: right;font-weight: bold;margin-top: 0px">کاربر گرامی </h3>';
            $msg.=$comment->comment__content;
            $msg.='<hr style="text-align: center;border:1px dashed #848080;margin: 50px ">';
            $msg.='<p>شناسه درخواست : '.$comment->ticket->id.'</p>';
            $msg.='<p>عنوان درخواست: '.$comment->ticket->ticket__title.'</p>';
            $msg.='<p>وضعیت درخواست: پاسخ داده شده</p>';
            $msg.='<p>واحد رسیدگی کننده:'.$type.'</p>';
            $msg.='<hr style="text-align: center;border:1px dashed #848080;margin: 50px ">';
            $msg.='<p style="text-align: center">(پاسخ های ارسالی از طریق ایمیل قابل بررسی نیستند)</p>';
            send_mail($comment->ticket->user->email,$comment->ticket->ticket__title,$msg);

         return Redirect()->back()->with('flash_message', 'عملیات با موفقیت انجام شد');

        } else {
         return Redirect()->back()->with('err_message', 'مشکلی وجود دارد دباره تلاش کنید');
        }
    }

    public function comment_update(Request $request, $id = null) {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->comment__content = $request->comment__text;
            $comment->confirmation = 1;
            $comment->update();

            return Redirect()->back()->with('flash_message', 'تایید شد');
        } else {
            return Redirect()->back();
        }
    }

    public function tgCommnet($comment_id) {
        $tg_comment = Comment::find($comment_id);
        $tg_ticket = Ticket::find($tg_comment->ticket->id);
        $tg_ticket_code = $tg_ticket->id;
        $tg_ticket_title = $tg_ticket->ticket__title;
        $tg_ticket_creator = $tg_ticket->creator_id;
        $tg_user = $tg_comment->user__id;
        $tg_date = $tg_comment->created_at;
        $tg_content = $tg_comment->comment__content;
        if (!is_null($tg_user)) {
            $tg_user = User::find($tg_user);
            $tg_user_id = $tg_user;
            if (!is_null($tg_user)) {

                $tg_user = $tg_user->company__name;
                if (is_null($tg_user)) {
                    $tg_user = $tg_user_id->name;
                } else {
                    $tg_user = $tg_user_id->name . " ( " . $tg_user . " )";
                }
            }
        }
        if (!is_null($tg_date)) {
            $tg_date = (strtotime($tg_date));
            require_once('jdf.php');
            $tg_date = jdate("Y/m/j (H:i)", $tg_date);
        }

        if (!is_null($tg_ticket_creator)) {
            $tg_ticket_creator = User::find($tg_ticket_creator);
            $tg_ticket_creator_id = $tg_ticket_creator;
            if (!is_null($tg_ticket_creator)) {

                $tg_ticket_creator = $tg_ticket_creator->company__name;
                if (is_null($tg_ticket_creator)) {
                    $tg_ticket_creator = $tg_ticket_creator_id->name;
                } else {
                    $tg_ticket_creator = $tg_ticket_creator_id->name . " ( " . $tg_ticket_creator . " )";
                }
            }
        }

        $text = "$tg_user به تیکت $tg_ticket_code پاسخ داد\n"
            . "<b>ثبت: </b> $tg_date\n"
            . "<b>عنوان تیکت: </b> $tg_ticket_title\n"
            . "<b>متن پاسخ: </b> \n $tg_content";

        // Telegram::sendMessage([
        //     'chat_id' => -1001174338090,
        //     'parse_mode' => 'HTML',
        //     'text' => $text
        // ]);

    }

    public function sendTelegram($ticket_id) {
        $tg_ticket = Ticket::find($ticket_id);
        $tg_title = $tg_ticket->ticket__title;
        $tg_type = $tg_ticket->ticket__type;
        $tg_priorty = $tg_ticket->ticket__priority;
        $tg_user = $tg_ticket->user__id;
        $tg_creator = $tg_ticket->creator_id;
        $tg_date = $tg_ticket->created_at;
        $tg_content = $tg_ticket->ticket__content;
        $tg_code = $tg_ticket->id;

        if (!is_null($tg_user)) {
            $tg_user = User::find($tg_user);
            $tg_user_id = $tg_user;
            if (!is_null($tg_user)) {

                $tg_user = $tg_user->company__name;
                if (is_null($tg_user)) {
                    $tg_user = $tg_user_id->name;
                } else {
                    $tg_user = $tg_user_id->name . " ( " . $tg_user . " )";
                }
            }
        }
        if (!is_null($tg_creator)) {
            $tg_creator = User::find($tg_creator);
            $tg_creator_id = $tg_creator;
            if (!is_null($tg_creator)) {

                $tg_creator = $tg_creator->company__name;
                if (is_null($tg_creator)) {
                    $tg_creator = $tg_creator_id->name;
                } else {
                    $tg_creator = $tg_creator_id->name . " ( " . $tg_creator . " )";
                }
            }
        }
        if ($tg_type == 'services') {
            $tg_type = 'فنی و پشتیبانی سایت و سئو';
        } else {
            $tg_type = 'فروش و حسابداری';
        }

        if (!is_null($tg_priorty)) {
            switch ($tg_priorty) {
                case 'low':
                    $tg_priorty = 'کم';
                    break;
                case 'normal':
                    $tg_priorty = 'متوسط';
                    break;
                case 'high':
                    $tg_priorty = 'زیاد';
                    break;
            }
        }

        if (!is_null($tg_date)) {
            $tg_date = (strtotime($tg_date));
            require_once('jdf.php');
            $tg_date = jdate("Y/m/j (H:i)", $tg_date);
        }

        $text = "تیکت جدید در سایت ثبت شده است\n"
            . "<b>کد: </b> #$tg_code\n"
            . "<b>بخش: </b> $tg_type\n"
            . "<b>عنوان: </b> $tg_title\n"
            . "<b>اولویت: </b> $tg_priorty\n"
            . "<b>فرستنده: </b> $tg_creator\n"
            . "<b>گیرنده: </b> $tg_user\n"
            . "<b>ثبت: </b> $tg_date\n"
            . "<b>متن تیکت: </b> \n $tg_content";


        // return Telegram::sendMessage([
        //     'chat_id' => -1001174338090,
        //     'parse_mode' => 'HTML',
        //     'text' => $text
        // ]);

        return true;
    }

}

<?php

namespace App\Http\Controllers\User\TodoList;

use App\Model\TodoListCat;
use App\Model\TodoList;
use App\Model\TodoListUser;
use App\Model\TodoListRefUserCat;
use App\Model\TodoListRefUser;
use App\Model\TodoListComment;
use App\Model\Library;
use App\Model\Company;
use App\Model\Contract;
use App\User;
use App\Http\Request\TodoList\TodoListRequest;
use App\Notification\TodoList\TodoListNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TodoListController extends Controller {

    public function controller_title($type) {
        switch ($type) {
            case 'index':
                return 'فعالیت ها';
                break;
            case 'create':
                return 'افزودن فعالیت';
                break;
            case 'edit':
                return 'ویرایش فعالیت';
                break;
            case 'show':
                return 'گزارش فعالیت';
                break;
            case 'url_back':
                return route('todo-list.index');
                break;
            case 'ref_change':
                if (in_array(auth()->id(), [111,125])) {
                    return true;
                }
                return false;
                break;
            case 'edit_todo':
                if (in_array(auth()->id(), [111,125])) {
                    return true;
                }
                else
                {
                    if(TodoList::where('user_id_create',auth()->id())->first())
                    {
                        return true;
                    }
                }
                return false;
                break;
            case 'todo_list_id':
                $todo_list_id = [];
                if (in_array(auth()->id(), [111,125])) {
                    $todo_list_id = TodoList::select('id')->get()->toArray();
                } else {
                    $cat_id_cc = TodoListUser::select('cat_id')->where('user_id', auth()->id())->get()->toArray();
                    $cat_id_ref = TodoListRefUser::select('cat_id')->where('user_id', auth()->id())->get()->toArray();
                    $todo_list_id = TodoList::where('type_ref', 'one')->where('user_id', auth()->id())
                        ->orWhere('type_ref', 'multi')->whereIN('user_id', $cat_id_ref)
                        ->orWhereIN('cat_id', $cat_id_cc)->select('id')->get()->toArray();
                }
                return $todo_list_id;
                break;
            default:
                return '';
                break;
        }
    }

    public function __construct() {
        $this->middleware(['auth']);
    }

    public function index() {
        $week_num_today = Carbon::now()->dayOfWeek;
        $today = Carbon::now();
        $week_num_today_1 = Carbon::now()->addDays(1)->dayOfWeek;
        $today_1 = Carbon::now()->addDays(1);
        $week_num_today_2 = Carbon::now()->addDays(2)->dayOfWeek;
        $today_2 = Carbon::now()->addDays(2);
        $mount = Carbon::now()->addMonths(1);

        $todo_list_id = $this->controller_title('todo_list_id');

        $items = TodoList::whereIN('id', $todo_list_id)->orderByDesc('id')->get();

        $item_before = TodoList::where('type_reminder', 'date')->whereIN('id', $todo_list_id)->whereDate('reminder', '<', $today)->where('end_date_work', null)
        ->whereIN('status', ['doing','pending'])->orwhere('type_reminder', 'date')->whereIN('id', $todo_list_id)->whereColumn('reminder', '>', 'end_date_work')
        ->whereIN('status', ['doing','pending'])->orderByDesc('id')->get();

        $item_before2 = TodoList::where('type_reminder', 'week')->whereIN('id', $todo_list_id)->whereIN('status', ['doing','pending'])->orderByDesc('id')->get();

        $item_before2 = $item_before2->filter(function ($item) {
            if (todo_before($item)) {
                return $item;
            }
        });

        $item_before = $item_before->merge($item_before2);

        $item_today = TodoList::where('type_reminder', 'date')->whereIN('id', $todo_list_id)->whereDate('reminder', $today)->whereIN('status', ['doing', 'pending'])
            ->orWhere('type_reminder', 'week')->whereIN('id', $todo_list_id)->where('reminder', 'LIKE', '%' . $week_num_today . '%')->whereIN('status', ['doing', 'pending'])
            ->orderByDesc('id')->get();

        $item_today_1 = TodoList::where('type_reminder', 'date')->whereIN('id', $todo_list_id)->whereDate('reminder', $today_1)->whereIN('status', ['doing', 'pending'])
            ->orWhere('type_reminder', 'week')->whereIN('id', $todo_list_id)->where('reminder', 'LIKE', '%' . $week_num_today_1 . '%')->whereIN('status', ['doing', 'pending'])
            ->orderByDesc('id')->get();

        $item_today_2 = TodoList::where('type_reminder', 'date')->whereIN('id', $todo_list_id)->whereDate('reminder', $today_2)->whereIN('status', ['doing', 'pending'])
            ->orWhere('type_reminder', 'week')->whereIN('id', $todo_list_id)->where('reminder', 'LIKE', '%' . $week_num_today_2 . '%')->whereIN('status', ['doing', 'pending'])
            ->orderByDesc('id')->get();

        $item_month = TodoList::where('type_reminder', 'date')->whereIN('id', $todo_list_id)->whereDate('reminder','>', $today_2)->whereDate('reminder','<=', $mount)->whereIN('status', ['doing', 'pending'])->orderByDesc('id')->get();

        $item_month_after = TodoList::where('type_reminder', 'date')->whereIN('id', $todo_list_id)->whereDate('reminder','>', $mount)->whereIN('status', ['doing', 'pending'])->orderByDesc('id')->get();

        $item_stop = TodoList::where('status', 'stop')->orderByDesc('id')->get();

        $item_end = TodoList::where('status', 'end')->orderByDesc('id')->get();

        return view('user.todo_list.index', compact('items', 'item_before', 'item_today', 'item_today_1', 'item_today_2','item_month','item_month_after','item_stop','item_end'), ['title' => $this->controller_title('index')]);
    }

    public function show($id, Request $request) {
        if (isset($request->id_not)) {
            auth()->user()->unreadNotifications->where('key', $request->id_not)->markAsRead();
            return redirect(route('todo-list.show', $id));
        }

        $url_back = $this->controller_title('url_back');
        $todo_list_id = $this->controller_title('todo_list_id');
        $item = TodoList::whereIN('id', $todo_list_id)->where('id', $id)->firstOrFail();
        $comments = TodoListComment::where('todo_list_id', $id)->orderByDesc('id')->get();
        $ref_change = $this->controller_title('ref_change');
        $users = User::where('role_id', '!=', 5)->where('suspended', 0)->get();
        return view('user.todo_list.show', compact('url_back', 'item', 'comments', 'ref_change', 'users'), ['title' => $this->controller_title('show')]);
    }

    public function create() {
        $url_back = $this->controller_title('url_back');
        $cats = TodoListCat::orderByDesc('id')->get();
        $contracts = Contract::where('active', 1)->get();
        $contracts_id = Contract::where('active', 1)->select('user__id')->get()->toArray();
        $companys = User::where('role_id', 5)->whereIN('id', $contracts_id)->get();
        $users = User::where('role_id', '!=', 5)->where('suspended', 0)->get();
        $groups = TodoListRefUserCat::where('status', 'active')->get();
        return view('user.todo_list.create', compact('url_back', 'cats', 'companys', 'contracts', 'users', 'groups'), ['title' => $this->controller_title('create')]);
    }

    public function store(TodoListRequest $request) {
        $user_id = explode('_', $request->user_id);
        if (count($user_id) != 2) {
            return redirect()->back()->withInput()->with('err_message', 'برای افزودن به مشکل خوردیم، مجدد تلاش کنید');
        }
        $user_group_id = TodoListRefUser::where('cat_id', $user_id[1])->orderBy('sort')->first();
        try {
            $item = TodoList::create([
                'cat_id' => $request->cat_id,
                'title' => $request->title,
                'company_id' => $request->company_id,
                'contract_id' => $request->contract_id,
                'type_ref' => $user_id[0] == 'u' ? 'one' : 'multi',
                'user_id' => $user_id[1],
                'user_group_id' => $user_group_id ? $user_group_id->user_id : '',
                'text' => $request->text,
                'priority' => $request->priority,
                'type_reminder' => $request->type_reminder,
                'reminder' => $request->type_reminder == 'date' ? j2g($request->reminder_date) : json_encode($request->reminder),
                'reminder_fa' => $request->type_reminder == 'date' ? $request->reminder_date : null,
                'percent' => 0,
                'status' => 'pending',
                'start_date' => j2g($request->start_date),
                'end_date' => j2g($request->end_date),
                'start_date_fa' => $request->start_date,
                'end_date_fa' => $request->end_date,
                'time' => 0,
                'user_id_create' => auth()->id(),
            ]);
            TodoListComment::create([
                'user_id' => auth()->id(),
                'todo_list_id' => $item->id,
                'text' => 'این فعالیت را ایجاد کرد',
                'change_item' => set_report_table($item),
            ]);
            if($item->type=='one')
            {
                $user = User::find($item->user_id);
                if ($user) {
                    $user->notifyNow(new TodoListNotification($item,'ایجاد فعالیت'));
                }
            }
            else
            {
                $user_cat=TodoListRefUser::where('cat_id',$item->user_id)->select('user_id')->get()->toArray();
                $users=User::whereIN('id',$user_cat)->get();
                foreach ($users as $user)
                {
                    $user->notifyNow(new TodoListNotification($item,'ایجاد فعالیت'));
                }
            }
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت افزوده شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای افزودن به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function edit($id) {
        $url_back = $this->controller_title('url_back');
        $cats = TodoListCat::orderByDesc('id')->get();
        $edit = $this->controller_title('edit_todo');
        if(!$edit)
        {
            return redirect()->back()->withInput()->with('err_message', 'دسترسی غیر مجاز');
        }
        $item = TodoList::findOrFail($id);

        $contracts = Contract::where('active', 1)->get();
        $contracts_id = Contract::where('active', 1)->select('user__id')->get()->toArray();
        $companys = User::where('role_id', 5)->whereIN('id', $contracts_id)->get();
        $users = User::where('role_id', '!=', 5)->where('suspended', 0)->orderBy('role_id')->get();
        $groups = TodoListRefUserCat::where('status', 'active')->get();
        return view('user.todo_list.edit', compact('url_back', 'item', 'cats', 'companys', 'contracts', 'users', 'groups'), ['title' => $this->controller_title('edit')]);
    }

    public function update(TodoListRequest $request, $id) {
        $todo_list_id = $this->controller_title('todo_list_id');
        $item = TodoList::whereIN('id', $todo_list_id)->where('id', $id)->firstOrFail();
        $old = $item;
        $user_id = explode('_', $request->user_id);
        if (count($user_id) != 2) {
            return redirect()->back()->withInput()->with('err_message', 'برای ویرایش به مشکل خوردیم، مجدد تلاش کنید');
        }
        $user_group_id = TodoListRefUser::where('cat_id', $user_id[1])->orderBy('sort')->first();
        try {
            $item->cat_id = $request->cat_id;
            $item->title = $request->title;
            $item->company_id = $request->company_id;
            $item->contract_id = $request->contract_id;
            $item->type_ref = $user_id[0] == 'u' ? 'one' : 'multi';
            $item->user_id = $user_id[1];
            $item->user_group_id = $user_group_id ? $user_group_id->user_id : '';
            $item->text = $request->text;
            $item->priority = $request->priority;
            $item->type_reminder = $request->type_reminder;
            $item->reminder = $request->type_reminder == 'date' ? j2g($request->reminder_date) : json_encode($request->reminder);
            $item->reminder_fa = $request->type_reminder == 'date' ? $request->reminder_date : null;
            $item->start_date = j2g($request->start_date);
            $item->end_date = j2g($request->end_date);
            $item->start_date_fa = $request->start_date;
            $item->end_date_fa = $request->end_date;
            $item->update();

            TodoListComment::create([
                'user_id' => auth()->id(),
                'todo_list_id' => $id,
                'text' => 'این فعالیت را ویرایش کرد',
                'change_item' => set_report_table($item, $old),
            ]);

            if($item->type=='one')
            {
                $user = User::find($item->user_id);
                if ($user) {
                    $user->notifyNow(new TodoListNotification($item,'ویرایش فعالیت'));
                }
            }
            else
            {
                $user_cat=TodoListRefUser::where('cat_id',$item->user_id)->select('user_id')->get()->toArray();
                $users=User::whereIN('id',$user_cat)->get();
                foreach ($users as $user)
                {
                    $user->notifyNow(new TodoListNotification($item,'ویرایش فعالیت'));
                }
            }
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت ویرایش شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای ویرایش به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function destroy($id) {
        $todo_list_id = $this->controller_title('todo_list_id');
        $item = TodoList::whereIN('id', $todo_list_id)->where('id', $id)->firstOrFail();
        try {
            if (count($item->childs)) {
                return redirect()->back()->withInput()->with('err_message', 'برای حذف فعالیت، ابتدا باید فعالیت زیرمجموعه متصل به این فعالیت حذف شوند');
            }
            if (count($item->comments)) {
                return redirect()->back()->withInput()->with('err_message', 'برای حذف فعالیت، ابتدا باید کامنتهای متصل به این فعالیت حذف شوند');
            }
            if (count($item->users_cc)) {
                foreach ($item->users_cc as $cc) {
                    $cc->delete();
                }
            }
            $item->delete();
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت حذف شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای حذف به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function status($id, $type) {
        $todo_list_id = $this->controller_title('todo_list_id');
        $item = TodoList::whereIN('id', $todo_list_id)->where('id', $id)->firstOrFail();
        if ($item->type_ref == 'multi' && $item->user_group_id != auth()->id()) {
            return redirect()->back()->withInput()->with('err_message', 'دسترسی غیر مجاز');
        } elseif ($item->type_ref == 'one' && $item->user_id != auth()->id()) {
            return redirect()->back()->withInput()->with('err_message', 'دسترسی غیر مجاز');
        }
        try {
            $item->status = $type;
            $item->update();

            TodoListComment::create([
                'user_id' => auth()->id(),
                'todo_list_id' => $id,
                'text' => 'این فعالیت را تغییر وضعیت داد',
                'change_item' => 'وضعیت: ' . $item->status_set($type),
            ]);
            return redirect()->back()->with('flash_message', 'با موفقیت تغییر وضعیت شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای تغییر وضعیت به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function report($id, $type, Request $request) {
        $todo_list_id = $this->controller_title('todo_list_id');
        $item = TodoList::whereIN('id', $todo_list_id)->where('id', $id)->firstOrFail();
        $user_id=$item->user_id;
        $user_group_id=$item->user_group_id;
        $old = $item;
        $this->validate($request, [
            'text' => 'required',
        ]);
        try {
            if ($type == 'one' && $item->user_id == auth()->id() && $item->status=='doing') {
                $item->time += $request->time;
                $item->percent = $request->percent;
                if (!blank($request->status)) {
                    $item->status = $request->status;
                }
                if (isset($request->reminder) && !blank($request->reminder)) {
                    $item->reminder = $item->type_reminder == 'date' ? j2g($request->reminder) : $item->reminder;
                    $item->reminder_fa = $item->type_reminder == 'date' ? $request->reminder : $item->reminder_fa;
                }
                $item->end_date_work=Carbon::now()->format('Y-m-d');
            }
            elseif ($type == 'multi' && $item->user_group_id == auth()->id() && $item->status=='doing') {
                $item->time += $request->time;
            }
            elseif ($type == 'multi_ref' && $item->user_group_id == auth()->id() && $item->status=='doing') {
                if ($item->group_ref_user_next($user_id, $user_group_id)[0] != 'end') {
                    $item->user_group_id = $item->group_ref_user_next($user_id, $user_group_id)[0];
                }
                $item->time += $request->time;
                $item->percent = $item->group_ref_user_percent($user_id, $user_group_id,'ref');
                if ($item->group_ref_user_percent($user_id, $user_group_id,'ref') == 100) {
                    $item->status = 'end';
                    $item->end_date_work=Carbon::now()->format('Y-m-d');
                }
            } elseif ($type == 'multi_back' && $item->user_group_id == auth()->id() && $item->status=='doing') {
                $item->user_group_id = $request->back_ref;
                $item->percent = $item->group_ref_user_percent($item->user_id, $request->back_ref,'back');
            }
            $item->update();

            if($item->type_ref=='multi')
            {
                if($type == 'multi_ref' || $type == 'multi_back')
                {
                    $user = User::find($item->user_group_id);
                    if ($user) {
                        $user->notifyNow(new TodoListNotification($item,'ارجاع فعالیت'));
                    }
                }
            }
            $comment = TodoListComment::create([
                'user_id' => auth()->id(),
                'todo_list_id' => $id,
                'text' => $request->text,
                'change_item' => set_report_table($item, $old),
            ]);


            if ($request->hasFile('docs')) {
                try {
                    foreach ($request->docs as $doc) {

                        $file = file_store($doc, 'assets/uploads/todo/' . g2j(date('Y/m/d'), 'Y-m-d') . '/docs/', 'doc-');
                        $library = new Library();
                        $library->file__path = $file;
                        $comment->libraries()->save($library);

                    }
                } catch (\Exception $e) {
                    dd($e);
                }
            }

            return redirect()->back()->with('flash_message', 'با موفقیت تغییر وضعیت شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای تغییر وضعیت به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function ref($id, Request $request) {
        if (!$this->controller_title('ref_change')) {
            return redirect()->back()->withInput()->with('err_message', 'دسترسی غیر مجاز');
        }
        $item = TodoList::findOrFail($id);
        $old = $item;
        if (blank($request->user_id) || $request->user_id == $item->user_id) {
            return redirect()->back()->withInput()->with('err_message', 'کاربر انتخاب نشده یا با مورد فعلی یکی می باشد');
        }
        if ($item->type_ref == 'multi') {
            return redirect()->back()->withInput()->with('err_message', 'دسترسی غیر مجاز');
        }
        try {
            $item->user_id = $request->user_id;
            $item->update();

            TodoListComment::create([
                'user_id' => auth()->id(),
                'todo_list_id' => $id,
                'text' => 'این فعالیت را ارجاع داد',
                'change_item' => set_report_table($item, $old),
            ]);

            $user = User::find($item->user_id);
                if ($user) {
                    $user->notifyNow(new TodoListNotification($item,'ارجاع فعالیت'));
                }
            return redirect()->back()->with('flash_message', 'با موفقیت ارجاع شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای ارجاع به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

}

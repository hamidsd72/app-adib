<?php

namespace App\Http\Controllers\User\TodoList;

use App\Model\TodoListRefUser;
use App\Model\TodoListRefUserCat;
use App\Model\TodoListUser;
use App\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TodoListRefController extends Controller
{
    public function controller_title($type)
    {
        switch ($type)
        {
            case 'index':
                return 'گروه کاربری فعالیت';
                break;
            case 'create':
                return 'افزودن  گروه فعالیت';
                break;
            case 'edit':
                return 'ویرایش گروه فعالیت';
                break;
            case 'url_back':
                return route('todo-list-ref.index');
                break;
            default:
                return '';
                break;
        }
    }
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $items=TodoListRefUserCat::orderByDesc('id')->get();
        return view('todo_list.ref.index', compact('items'), ['title' => $this->controller_title('index')]);
    }
    public function show($id)
    {

    }
    public function create()
    {
        $url_back=$this->controller_title('url_back');
        return view('todo_list.ref.create',compact('url_back'), ['title' => $this->controller_title('create')]);
    }
    public function store(Request $request)
    {
        try {
            $item = TodoListRefUserCat::create([
                'title' => $request->title,
                'status' => $request->status,
                'user_id_create' => auth()->id(),
            ]);
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت افزوده شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای افزودن به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
    public function edit($id)
    {
        $url_back=$this->controller_title('url_back');
        $item=TodoListRefUserCat::findOrFail($id);
        return view('todo_list.ref.edit',compact('url_back','item'), ['title' => $this->controller_title('edit')]);
    }
    public function update(Request $request,$id)
    {
        $item=TodoListRefUserCat::findOrFail($id);
        try {
            $item->update([
                'title' => $request->title,
                'status' => $request->status,
            ]);
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت ویرایش شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای ویرایش به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
    public function destroy($id)
    {
        $item=TodoListRefUserCat::findOrFail($id);
        try {
            if (count($item->users_ref)){
                return redirect()->back()->withInput()->with('err_message', 'برای حذف گروه، ابتدا باید کاربران متصل به این گروه حذف شوند');
            }
            $item->delete();
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت حذف شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای حذف به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function user_group($id)
    {
        $url_back=$this->controller_title('url_back');
        $ref=TodoListRefUserCat::findOrFail($id);
        $items=TodoListRefUser::where('cat_id',$id)->orderBy('sort')->get();
        $user_id=TodoListRefUser::where('cat_id',$id)->select('user_id')->get()->toArray();
        $users=User::where('role_id','!=',5)->where('suspended',0)->whereNotIN('id',$user_id)->orderBy('role_id')->get();
        $title='کاربران گروه '.$ref->title;
        return view('todo_list.ref.user_group',compact('url_back','items','ref','users'), ['title' => $title]);
    }
    public function user_group_store(Request $request,$id)
    {
        try {
            $item = TodoListRefUser::create([
                'cat_id' => $id,
                'user_id' => $request->user_id,
                'sort' => TodoListRefUser::where('cat_id',$id)->count()+1,
                'user_id_create' => auth()->id(),
            ]);
            return redirect()->back()->with('flash_message', 'اطلاعات با موفقیت افزوده شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای افزودن به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function user_group_sort(Request $request,$id)
    {
        $item=TodoListRefUser::findOrFail($id);
        try {
            $item->sort=$request->sort;
            $item->update();
            return redirect()->back()->with('flash_message', 'اطلاعات با موفقیت مرتب سازی شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای مرتب سازی به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function user_group_delete($id)
    {
        $item=TodoListRefUser::findOrFail($id);
        try {
            $item->delete();
            return redirect()->back()->with('flash_message', 'اطلاعات با موفقیت حذف شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای حذف به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
}

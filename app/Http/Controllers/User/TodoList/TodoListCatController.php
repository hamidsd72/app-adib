<?php

namespace App\Http\Controllers\User\TodoList;

use App\Model\TodoListCat;
use App\Model\TodoListUser;
use App\User;
use App\Model\Role;
use App\Http\Request\TodoList\TodoListCatRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TodoListCatController extends Controller
{
    public function controller_title($type)
    {
        switch ($type)
        {
            case 'index':
                return 'دسته بندی فعالیت';
                break;
            case 'create':
                return 'افزودن  دسته بندی فعالیت';
                break;
            case 'edit':
                return 'ویرایش دسته بندی فعالیت';
                break;
            case 'url_back':
                return route('todo-list-category.index');
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
        $items=TodoListCat::orderByDesc('id')->get();
        return view('todo_list.category.index', compact('items'), ['title' => $this->controller_title('index')]);
    }
    public function show($id)
    {

    }
    public function create()
    {
        $url_back=$this->controller_title('url_back');
        $roles=Role::orderBy('id')->get();
        return view('todo_list.category.create',compact('url_back','roles'), ['title' => $this->controller_title('create')]);
    }
    public function store(TodoListCatRequest $request)
    {
        try {
            $item = TodoListCat::create([
                'title' => $request->title,
            ]);
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت افزوده شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای افزودن به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
    public function edit($id)
    {
        $url_back=$this->controller_title('url_back');
        $item=TodoListCat::findOrFail($id);
        $roles=Role::orderBy('id')->get();
        return view('todo_list.category.edit',compact('url_back','item','roles'), ['title' => $this->controller_title('edit')]);
    }
    public function update(TodoListCatRequest $request,$id)
    {
        $item=TodoListCat::findOrFail($id);
        try {
            $item->update([
                'title' => $request->title,
            ]);
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت ویرایش شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای ویرایش به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
    public function destroy($id)
    {
        $item=TodoListCat::findOrFail($id);
        try {
            if (count($item->todo_lists)){
                return redirect()->back()->withInput()->with('err_message', 'برای حذف دسته بندی، ابتدا باید فعالیت متصل به این دسته بندی حذف شوند');
            }
            $item->delete();
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت حذف شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای حذف به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function user_cc($id)
    {
        $url_back = $this->controller_title('url_back');
        $cat=TodoListCat::findOrFail($id);
        $items = TodoListUser::where('cat_id', $id)->get();
        $user_id = TodoListUser::where('cat_id', $id)->select('user_id')->get()->toArray();
        $users = User::where('role_id', '!=', 5)->where('suspended', 0)->whereNotIN('id', $user_id)->orderBy('role_id')->get();
        $title = 'کاربران در جریان ' . $cat->title;
        return view('todo_list.category.user', compact('url_back', 'items', 'cat', 'users'), ['title' => $title]);
    }

    public function user_cc_store(Request $request, $id)
    {
        try {
            $item = TodoListUser::create([
                'cat_id' => $id,
                'user_id' => $request->user_id,
                'user_create_id' => auth()->id(),
            ]);
            return redirect()->back()->with('flash_message', 'اطلاعات با موفقیت افزوده شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای افزودن به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function user_cc_delete($id)
    {
        $item = TodoListUser::findOrFail($id);
        try {
            $item->delete();
            return redirect()->back()->with('flash_message', 'اطلاعات با موفقیت حذف شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای حذف به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

}

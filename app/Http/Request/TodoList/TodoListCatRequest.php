<?php

namespace App\Http\Request\TodoList;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TodoListCatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'title' => 'required|max:240|unique:todo_list_cats',
//                    'access_role' => 'required',
                ];
            }
            case 'PATCH':
            {
                $id=$this->request->get('id');
                return [
                    'title' => 'required|max:240|unique:todo_list_cats,title,'.$id,
//                    'access_role' => 'required',
                ];
            }
            default:
                break;
        }
    }
}

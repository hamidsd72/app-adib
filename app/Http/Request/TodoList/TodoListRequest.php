<?php

namespace App\Http\Request\TodoList;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TodoListRequest extends FormRequest
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
                if($this->request->get('type_reminder')=='date')
                {
                    return [
                        'cat_id' => 'required',
                        'user_id' => 'required',
                        'title' => 'required|max:240',
                        'priority' => 'required',
                        'company_id' => 'required',
                        'contract_id' => 'required',
                        'type_reminder' => 'required',
                        'reminder_date' => 'required',
                        'text' => 'required',
                    ];
                }
                else
                {

                    return [
                        'cat_id' => 'required',
                        'user_id' => 'required',
                        'title' => 'required|max:240',
                        'priority' => 'required',
                        'company_id' => 'required',
                        'contract_id' => 'required',
                        'type_reminder' => 'required',
                        'reminder' => 'required',
                        'text' => 'required',
                    ];
                }
            }
            case 'PATCH':
            {
                $id=$this->request->get('id');
                if($this->request->get('type_reminder')=='date')
                {
                    return [
                        'cat_id' => 'required',
                        'user_id' => 'required',
                        'title' => 'required|max:240',
                        'priority' => 'required',
                        'company_id' => 'required',
                        'contract_id' => 'required',
                        'type_reminder' => 'required',
                        'reminder_date' => 'required',
                        'text' => 'required',
                    ];
                }
                else
                {

                    return [
                        'cat_id' => 'required',
                        'user_id' => 'required',
                        'title' => 'required|max:240',
                        'priority' => 'required',
                        'company_id' => 'required',
                        'contract_id' => 'required',
                        'type_reminder' => 'required',
                        'reminder' => 'required',
                        'text' => 'required',
                    ];
                }
            }
            default:
                break;
        }
    }
}

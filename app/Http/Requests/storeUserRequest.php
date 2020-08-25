<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('user'); //获取当前需要排除的id
        if (empty($id)){//add
            return [
                'name' => 'required|unique:users,name|max:191',
                'display_name' => 'required|max:191',
                'password' => 'required',
            ];
        }else{//update
            return [
                'display_name' => 'required|max:191',
            ];
        }
    }
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'ID必填',
            'name.unique' => 'ID唯一',
            'name.max' => 'ID长度最大191',
            'password.required'  => '密码必填',
            'display_name.required'  => '名称必填',
            'display_name.max'  => '名称长度最大191',
        ];
    }
}

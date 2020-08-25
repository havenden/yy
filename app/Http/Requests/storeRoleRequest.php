<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeRoleRequest extends FormRequest
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
        $id = $this->route('role'); //获取当前需要排除的id
        if (empty($id)){//add
            return [
                'name' => 'required|unique:roles,name|max:191',
            ];
        }else{//update
            return [
                'name' => 'required|max:191',
            ];
        }
    }
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '标识必填',
            'name.unique' => '标识唯一',
            'name.max' => '标识长度最大191',
        ];
    }
}

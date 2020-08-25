<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeDiseaseRequest extends FormRequest
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

    public function rules()
    {
        $id = $this->route('disease'); //获取当前需要排除的id
        if (empty($id)){//add
            return [
                'name' => 'required|unique:diseases,name|max:191',
                'display_name' => 'required|max:191',
                'hid' => 'required',
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
            'name.required' => '标识必填',
            'hid.required' => '医院必填',
            'name.unique' => '标识唯一',
            'name.max' => '标识长度最大191',
            'display_name.required'  => '名称必填',
            'display_name.max'  => '名称长度最大191',
        ];
    }
}

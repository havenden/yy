<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeDoctorRequest extends FormRequest
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
        $id = $this->route('doctor'); //获取当前需要排除的id
        if (empty($id)){//add
            return [
                'name' => 'required|max:191',
                'hid' => 'required',
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
            'name.required' => '名称必填',
            'name.max' => '标识长度最大191',
            'hid.required'  => '医院必填',
        ];
    }
}

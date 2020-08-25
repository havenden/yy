<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeMemberRequest extends FormRequest
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
        return [
            'name' => 'required|max:191',
            'hospital' => 'required',
            'disease' => 'required',
        ];
    }
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '姓名必填',
            'name.max' => '姓名长度最大191',
            'hospital.required'  => '医院必填',
            'disease.required'  => '病种必填',
        ];
    }
}

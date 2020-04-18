<?php

namespace App\Http\Requests\Api\EmployeeController;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeStore extends FormRequest
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
            'name'=>'required',
            'salary'=>'required',
            'age'=>'required',
            'profile_image'=>'mimes:jpg,jpeg,png,bmp,tiff|max:2000',
            // 'profile_image'=>'mimes:jpg,jpeg,png,bmp,tiff|max:2000|dimensions:min_width=100,min_height=100',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TruemoneyRequest extends FormRequest
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
            //
            'brand_id' => 'required',
            'name' => 'required',
            'bank_account' => 'required',
            'pin' => 'required',
            'app_id' => 'required',
            'token' => 'required',
            'tmn_one_id' => 'required',
        ];
    }
}

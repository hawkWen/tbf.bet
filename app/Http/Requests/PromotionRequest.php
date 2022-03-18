<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromotionRequest extends FormRequest
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
            'name' => 'required',
            'min' => 'required|min:0',
            'max' => 'required|min:0',
            'cost' => 'required|min:0',
            'turn_over' => 'required|min:0',
            'withdraw_max' => 'required|min:0',
            'turn_over' => 'required',
            'min_break_promotion' => 'required|min:0'
        ];
    }
}

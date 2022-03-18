<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
            'logo' => 'required',
            'name' => 'required',
            'subdomain' => 'required|unique:brands',
            'line_id' => 'required',
            'telephone' => 'required',
            'game_id' => 'required',
            'agent_username' => 'required',
            'agent_password' => 'required',
            // 'stock' => 'required',
            // 'cost_service' => 'required',
            // 'cost_working' => 'required',
            // 'deposit_min' => 'required',
            // 'withdraw_min' => 'required',
            // 'withdraw_auto_max' => 'required'
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepositManualRequest extends FormRequest
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
            // |exists:customers
            'username' => 'required',
            'amount' => 'required|not_in:0',
            'bank_account_id' => 'required',
            'transfer_date' => 'required',

        ];
    }
}

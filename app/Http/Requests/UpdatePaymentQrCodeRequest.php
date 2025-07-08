<?php

namespace App\Http\Requests;

use App\Models\PaymentQrCode;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentQrCodeRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The Title field is required',
        ];
    }
}

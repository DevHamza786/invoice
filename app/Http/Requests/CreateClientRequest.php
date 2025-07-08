<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;

class CreateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return Client::$rules;
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'password_confirmation.required' => 'The confirm password field is required.',
            'password.same'                  => 'The password and confirm password must match',
        ];
    }
}

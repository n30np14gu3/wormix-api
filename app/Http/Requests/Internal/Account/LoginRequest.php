<?php

namespace App\Http\Requests\Internal\Account;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'referrer_id' => 'required|integer',
            'auth_key' => 'required|string'
        ];
    }

    public function messages(): array{
        return [
            'id.required' => 'id is required',
            'id.integer' => 'id must be an integer',

            'referrer_id.required' => 'referrer_id is required',
            'referrer_id.integer' => 'referrer_id must be an integer',

            'auth_key.required' => 'auth_key is required',
            'auth_key.string' => 'auth_key must be a string',
        ];
    }
}

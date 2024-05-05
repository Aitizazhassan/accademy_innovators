<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'first_name' => 'required|alpha_num|max:100',
            'last_name' => 'required|alpha_num|max:100',
            'email' => ['required', 'string', 'lowercase', 'email:rfc,dns', 'max:255', 'unique:users'],
            'employee_id' => 'required|alpha_num|size:20|unique:users',
            'phone_number' => 'required|numeric|digits:10',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}

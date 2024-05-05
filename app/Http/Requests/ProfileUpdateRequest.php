<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            // 'name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'alpha_num', 'max:100'],
            'last_name' => ['required', 'alpha_num', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email:rfc,dns', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'employee_id' => ['required', 'alpha_num', 'size:20', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone_number' => ['required', 'numeric', 'digits:10'],
            'status' => ['required'],
        ];
    }
}

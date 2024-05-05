<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $userId = $this->route('user')->id;

        return [
            // 'name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'alpha_num', 'max:100'],
            'last_name' => ['required', 'alpha_num', 'max:100'],
            'email' => ['required', 'string', 'max:255', Rule::unique(User::class)->ignore($userId)],
            'phone_number' => ['required', 'string', 'max:20'],
            'status' => ['required'],
        ];
    }
}

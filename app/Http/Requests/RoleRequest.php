<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $method = request()->method();
        switch($method)
        {
            case 'POST':
            return [
                'name' => 'required|unique:roles,name',
            ];
            break;
            case 'PUT':
            return [
                'name' => ['required', Rule::unique('roles', 'name')->ignore($this->route('role'))],
            ];
            break;
        }
    }

    /**
     * Get the error messages for the validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
           // 'permissions.required' => 'The permissions field is required. Please select at least one permission.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
        $method = request()->method();
        switch($method)
        {
            case 'POST':
            return [
                'job_number' => 'required',
                'name' => 'required|string|max:255|unique:projects,name',
                'client_name' => 'required|string',
                // 'company_name' => 'required|string',
                'phone' => 'required|string',
                'address' => 'required|string',
            ];
            break;
            case 'PUT':
            return [
                'job_number' => 'required',
                'name' => ['required', Rule::unique('projects', 'name')->ignore($this->route('project'))],
                'client_name' => 'required|string',
                // 'company_name' => 'required|string',
                'phone' => 'required|string',
                'address' => 'required|string',
            ];
            break;
        }
    }
}

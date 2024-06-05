<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class McqsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {

        return [
            'board_id' => 'required',
            'subject_id' => 'required',
            'chapter_id' => 'required',
            'topic_id' => 'required',
            'class_id' => 'required',
            'statement' => 'required',
            'optionA' => 'required',
            'optionB' => 'required',
            'optionC' => 'required',
            'optionD' => 'required',
        ];

    }

}

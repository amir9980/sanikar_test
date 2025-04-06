<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'string|required|between:3,255',
            'description' => 'string|nullable|between:5,1000',
            'start_date' => ['required', 'string', 'date'],
            'end_date' => ['required', 'string', 'date'],
        ];
    }
}

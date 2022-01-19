<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'nullable|string|max:100',
            'surname' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'role' => 'nullable|string|max:100',
        ];
    }
}

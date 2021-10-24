<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login' => 'required|alpha_dash|unique:users|max:50',
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'date_of_birth' => 'required|date',
            'password' => 'required|confirmed|string|min:8|max:100',
        ];
    }

    public function messages()
    {
        return [
            'password.confirmed' => "Hasła nie są zgodne."
        ];
    }
}

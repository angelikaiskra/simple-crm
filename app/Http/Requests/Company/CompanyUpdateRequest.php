<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
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
            'name' => 'nullable|string|max:300',
            'nip' => 'nullable|int|min:1000000000|max:9999999999',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:500',
            'creator_id' => 'nullable|integer',
        ];
    }
}

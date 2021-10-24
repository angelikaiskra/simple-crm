<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
//    public function authorize()
//    {
//        return true;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:300',
            'nip' => 'required|int|min:1000000000|max:9999999999',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:500',
            'creator_id' => 'required|integer',
        ];
    }
}

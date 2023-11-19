<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
    public function messages()
    {
        return array_merge(parent::messages(), [
            'email.unique' => ':attribute is taken.',
            'name.min' => ':attribute required.'
        ]);
    }
    public function attributes()
    {
        return [
            'email' => 'Email Address',
            'name' => 'Company Name'
        ];
    }
}

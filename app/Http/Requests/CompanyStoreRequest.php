<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
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
        return [
            'name' => 'required|string|',
            'email' => 'required|email|unique:companies,email',
            'website' => 'nullable|url',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
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

<?php

namespace App\Http\Requests\Authorized;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ['required', 'string', 'max:255'],
            "email" => ['required', 'email', 'max:255'],
            "mobile_number" => ['nullable', 'max:20', 'regex:/^8[0-9]*$/'],
            "utm_source" => [
                Rule::requiredIf(request('utm_medium') != null),
                'max:255'
            ],
            "utm_medium" => [
                Rule::requiredIf(request('utm_campaign') != null),
                'max:255'
            ],
            "utm_campaign" => ['nullable', 'max:255'],
        ];
    }
}

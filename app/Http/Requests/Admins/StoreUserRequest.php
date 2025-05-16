<?php

namespace App\Http\Requests\Admins;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->roles[0]->name === Role::ROLE_ADMIN;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:40', 'min:3'],
            'email' => ['required', 'email', 'max:40', 'min:3'],
            'password' => ['required', 'confirmed', 'max:16', 'min:6'],
            'mobile' => ['required', 'numeric', 'starts_with:8']
        ];
    }
}

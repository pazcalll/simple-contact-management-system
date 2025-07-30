<?php

namespace App\Http\Requests\Admins;

use App\Models\Role;
use App\Models\User;
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
        $role = Role::find(request()->post('role_id'));
        $isUplineRequired = $role->name !== Role::ROLE_MANAGER && $role->name !== Role::ROLE_ADMIN;

        return [
            'name' => ['required', 'max:40', 'min:3'],
            'email' => ['required', 'email', 'max:40', 'min:3', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'max:16', 'min:6'],
            'mobile' => ['required', 'numeric', 'starts_with:8', 'max_digits:11'],
            'role_id' => ['required', 'exists:roles,id'],
            'upline_id' => [
                $isUplineRequired ? 'required' : 'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $role = Role::find(request()->post('role_id'));
                    $isExist = User::where('id', $value)
                        ->role($role?->upline?->name)
                        ->exists();
                    if (!$isExist) $fail('Invalid upline');
                }
            ],
        ];
    }
}

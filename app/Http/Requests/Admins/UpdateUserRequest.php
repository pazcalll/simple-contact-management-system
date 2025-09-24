<?php

namespace App\Http\Requests\Admins;

use App\Models\Role;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->roles[0]->name === Role::ROLE_ADMIN;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = User::withCount(['downlines', 'leads'])->find($this->route('user'));
        $executableFunction = null;
        if ($user->downlines_count > 0 || $user->leads_count > 0) {
            $executableFunction = function ($attribute, $value, $fail) use ($user) {
                if ($value != $user->roles[0]->id) {
                    $fail('Cannot update user with downlines or leads assigned.');
                }
            };
        }

        $role = Role::find(request()->post('role_id'));
        $isUplineRequired = $role->name !== Role::ROLE_MANAGER && $role->name !== Role::ROLE_ADMIN;

        return [
            'name' => ['required', 'max:40', 'min:3'],
            'email' => ['required', 'email', 'max:40', 'min:3', 'unique:users,email,' . $this->route('user')],
            'password' => ['nullable', 'confirmed', 'max:16', 'min:6'],
            'mobile' => ['required', 'numeric', 'starts_with:8', 'max_digits:11'],
            'role_id' => ['required', 'exists:roles,id', $executableFunction],
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

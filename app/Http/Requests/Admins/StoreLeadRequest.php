<?php

namespace App\Http\Requests\Admins;

use App\Models\Role;
use App\Rules\HasManagerRole;
use App\Rules\HasStaffRole;
use App\Rules\HasSupervisorRole;
use App\Rules\HasTeamLeaderRole;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->roles[0]->name == Role::ROLE_ADMIN;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'name' => ['required', 'string', 'min:3', 'max:40'],
            'email' => ['required', 'email', 'min:3', 'max:40'],
            'mobile_number' => ['required', 'numeric', 'min_digits:8', 'max_digits:12', 'starts_with:8'],
            'source' => ['required', 'max:16'],
            'medium' => ['nullable', 'max:16'],
            'campaign' => ['nullable', 'max:16'],
            'manager_id' => ['nullable', 'exists:users,id', new HasManagerRole],
            'supervisor_id' => ['nullable', 'exists:users,id', new HasSupervisorRole],
            'team_leader_id' => ['nullable', 'exists:users,id', new HasTeamLeaderRole],
            'staff_id' => ['nullable', 'exists:users,id', new HasStaffRole],
        ];
    }
}

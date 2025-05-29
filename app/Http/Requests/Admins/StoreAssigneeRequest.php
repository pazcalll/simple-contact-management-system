<?php

namespace App\Http\Requests\Admins;

use App\Models\Role;
use App\Rules\HasManagerRole;
use App\Rules\HasStaffRole;
use App\Rules\HasSupervisorRole;
use App\Rules\HasTeamLeaderRole;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssigneeRequest extends FormRequest
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
            'lead_ids' => ['required', 'array'],
            'lead_ids.*' => ['required', 'exists:leads,id'],
            'manager_id' => ['nullable', 'exists:users,id', new HasManagerRole],
            'supervisor_id' => ['nullable', 'exists:users,id', new HasSupervisorRole],
            'team_leader_id' => ['nullable', 'exists:users,id', new HasTeamLeaderRole],
            'staff_id' => ['nullable', 'exists:users,id', new HasStaffRole],
        ];
    }
}

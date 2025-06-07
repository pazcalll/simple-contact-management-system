<?php

namespace App\Http\Requests\Admins;

use App\Models\Role;
use App\Rules\HasCorrectUpline;
use App\Rules\HasManagerRole;
use App\Rules\HasStaffRole;
use App\Rules\HasSupervisorRole;
use App\Rules\HasTeamLeaderRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssignedLeadRequest extends FormRequest
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
            'lead_status_id' => ['required', 'exists:lead_statuses,id'],
            'lead_ids' => ['required', 'array'],
            'lead_ids.*' => ['required', 'exists:leads,id'],
            'manager_id' => [
                Rule::requiredIf(request()->post('supervisor_id') != null),
                request('manager_id') ? 'exists:users,id' : null,
                new HasManagerRole
            ],
            'supervisor_id' => [
                Rule::requiredIf(request()->post('team_leader_id') != null),
                request('supervisor_id') ? 'exists:users,id' : null,
                new HasSupervisorRole,
                new HasCorrectUpline($this->request->get('manager_id'))
            ],
            'team_leader_id' => [
                Rule::requiredIf(request()->post('staff_id') != null),
                request('team_leader_id') ? 'exists:users,id' : null,
                new HasTeamLeaderRole,
                new HasCorrectUpline($this->request->get('supervisor_id'))
            ],
            'staff_id' => [
                'nullable',
                request('staff_id') ? 'exists:users,id' : null,
                new HasStaffRole,
                new HasCorrectUpline($this->request->get('team_leader_id'))
            ],
        ];
    }
}

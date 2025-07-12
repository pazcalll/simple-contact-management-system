<?php

namespace App\Http\Requests\Authorized;

use App\Models\Role;
use App\Rules\HasCorrectUpline;
use App\Rules\HasSupervisorRole;
use App\Rules\HasTeamLeaderRole;
use Illuminate\Foundation\Http\FormRequest;

class BulkAssignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if ($user == null) return false;
        if ($user->hasRole([Role::ROLE_STAFF])) return false;

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $validations = [
            'lead_status_id' => ['nullable', 'exists:lead_statuses,id'],
            'lead_ids' => ['required', 'array'],
            'lead_ids.*' => ['required', 'exists:leads,id'],
            'is_unassign' => ['nullable', 'in:on,off'],
        ];

        if ($this->user()->hasRole([Role::ROLE_ADMIN])) {
            $validations['is_supervisor'] = [
                'nullable',
                'exists:users,id',
                new HasCorrectUpline($this->user()->id),
                new HasSupervisorRole()
            ];
        }

        if ($this->user()->hasRole([Role::ROLE_SUPERVISOR])) {
            $validations['is_team_leader'] = [
                'nullable',
                'exists:users,id',
                new HasCorrectUpline($this->user()->id),
                new HasTeamLeaderRole()
            ];
        }

        if ($this->user()->hasRole([Role::ROLE_TEAM_LEADER])) {
            $validations['is_team_leader'] = [
                'nullable',
                'exists:users,id',
                new HasCorrectUpline($this->user()->id),
                new HasTeamLeaderRole()
            ];
        }

        return $validations;
    }
}

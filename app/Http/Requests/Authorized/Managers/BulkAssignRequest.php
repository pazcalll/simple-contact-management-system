<?php

namespace App\Http\Requests\Authorized\Managers;

use App\Models\Role;
use App\Rules\HasCorrectUpline;
use App\Rules\HasSupervisorRole;
use App\Rules\OwnedByAuthorizedUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BulkAssignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if ($user == null) return false;
        if ($user->roles[0]->name != Role::ROLE_MANAGER) return false;

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
            //
            'lead_status_id' => ['nullable', 'exists:lead_statuses,id'],
            'lead_ids' => ['required', 'array'],
            'lead_ids.*' => ['required', 'exists:leads,id'],
            'is_unassign' => ['nullable', 'in:on,off'],
            'supervisor_id' => [
                'nullable',
                'exists:users,id',
                new HasCorrectUpline($this->user()->id),
                new HasSupervisorRole()
            ]
        ];
    }
}

<?php

namespace App\Http\Requests\Authorized;

use App\Rules\OwnedByAuthorizedUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MassUpdateLeadStatusIdRequest extends FormRequest
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
            'lead_ids' => ['required', 'array'],
            'lead_ids.*' => ['required', 'exists:leads,id', new OwnedByAuthorizedUser],
            'lead_status_id' => ['required', 'exists:lead_statuses,id'],
        ];
    }
}

<?php

namespace App\Http\Requests\Authorized;

use App\Models\AssignedLead;
use App\Rules\OwnedByAuthorizedUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreLeadNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check(); // Just check if user is logged in, lead ownership is checked in rules
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'note' => ['required', 'max:200'],
            'lead_id' => ['required', new OwnedByAuthorizedUser()], // Validate that the lead is owned by the current user
        ];
    }
    
    /**
     * Get data to be validated from the request.
     */
    public function validationData(): array
    {
        $lead = request()->route('lead');
        
        return array_merge($this->all(), [
            'lead_id' => $lead ? $lead->id : null,
        ]);
    }
}

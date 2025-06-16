<?php

namespace App\Rules;

use App\Models\AssignedLead;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class OwnedByAuthorizedUser implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        $lead = AssignedLead::where('lead_id', $value)
            ->where('user_id', Auth::user()->id)
            ->exists();

        if (!$lead) $fail('Unauthorized action.');
    }
}
